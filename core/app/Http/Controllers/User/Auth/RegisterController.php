<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use App\Lib\Intended;
use App\Constants\Status;
use App\Models\UserLogin;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{

    use RegistersUsers;

    public function __construct()
    {
        parent::__construct();
    }

    public function showRegistrationForm()
    {
        $pageTitle = "Register";
        if (gs('registration')) {
            Intended::identifyRoute();

            $referParamSetting = str_replace(['?', '='], '', gs('referral_parameter') ?: 'reference');
            $rawReference = request()->query($referParamSetting);

            if (!$rawReference) {
                foreach(['invite', 'ref', 'reference', 'refer'] as $param) {
                    if ($rawReference = request()->query($param)) break;
                }
            }

            if (!$rawReference) {
                foreach (request()->query() as $key => $value) {
                    if (in_array(strtolower($key), ['invite', 'ref', 'reference', 'refer', strtolower($referParamSetting)])) {
                        $rawReference = $value;
                        break;
                    }
                }
            }

            if ($rawReference) {
                $decoded = base64_decode($rawReference, true);
                $reference = ($decoded && preg_match('/^[a-zA-Z0-9. @_+:-]+$/', $decoded))
                    ? $decoded
                    : $rawReference;
                session()->put('reference', $reference);
            }
            
            // Auto-detect Country & Mobile Code
            $ip = request()->ip();
            $country_code = 'PK';
            $mobile_code = '92';
            $country_name = 'Pakistan';

            // Try GeoPlugin
            $xml = @simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=".$ip);
            if ($xml && $xml->geoplugin_countryCode != null) {
                $country_code = (string) $xml->geoplugin_countryCode;
                $country_name = (string) $xml->geoplugin_countryName;
                // We need a map for mobile codes, or we rely on the JS frontend if available.
                // However, user said "+92 but country code always same", implying backend default.
                
                // Let's try to find the code from the JSON file
                $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')), true);
                if (isset($countries[$country_code])) {
                     $mobile_code = $countries[$country_code]['dial_code'];
                }
            } else {
                 // Fallback ipwho.is
                $ApiResponse = @json_decode(file_get_contents("http://ipwho.is/".$ip), true);
                if ($ApiResponse && $ApiResponse['success']) {
                    $country_code = $ApiResponse['country_code'];
                    $country_name = $ApiResponse['country'];
                    $mobile_code = str_replace('+', '', $ApiResponse['calling_code']);
                }
            }

            return view('Template::user.auth.register', compact('pageTitle', 'mobile_code', 'country_code', 'country_name'));
        } else {
            return view('Template::registration_disabled', compact('pageTitle'));
        }
    }


    protected function validator(array $data)
    {

        $passwordValidation = Password::min(6);

        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

       

        $validate     = Validator::make($data, [
            'username' => 'required|unique:users',
            'password'  => ['required', $passwordValidation],
        ],[
        ]);

        return $validate;
    }

    public function register(Request $request)
    {
        if (!gs('registration')) {
            $notify[] = ['error', 'Registration not allowed'];
            return back()->withNotify($notify);
        }
        $this->validator($request->all())->validate();

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }



    protected function create(array $data)
    {
        $general = gs();

        $referBy = @$data['referBy'] ?: session()->get('reference');
        $referUser = null;
        if ($referBy) {
            // Priority 1: Search as-is (Username or ID or Mobile)
            $referUser = User::where('username', $referBy)
                ->orWhere('id', $referBy)
                ->orWhere('mobile', $referBy)
                ->first();

            // Priority 2: Try decoding if Priority 1 failed
            if (!$referUser) {
                $decoded = base64_decode($referBy, true);
                if ($decoded && preg_match('/^[a-zA-Z0-9. @_+:-]+$/', $decoded)) {
                     $referUser = User::where('username', $decoded)
                        ->orWhere('id', $decoded)
                        ->orWhere('mobile', $decoded)
                        ->first();
                }
            }
        }
        // User Create
        $user = new User();
        
        // Auto-generate dummy email based on username to satisfy DB constraint
        $uniqueArg = $data['username']; 
        $user->email       = strtolower($uniqueArg.'@'.request()->getHost());
        
        $user->password    = Hash::make($data['password']);
        $user->username    = trim($data['username']);
        $user->ref_by      = $referUser ? $referUser->id : 0;
        $user->country_code = $data['country_code'];
        $user->country_name = $data['country_name'];
        $user->mobile       = $data['mobile_code'].$data['mobile'];
        $user->address      = [
            'address' => '',
            'state'   => '',
            'zip'     => '',
            'country' => isset($data['country_name']) ? $data['country_name'] : null,
            'city'    => ''
        ];
        $user->status = Status::USER_ACTIVE;
        $user->ev     = $general->ev ? Status::UNVERIFIED : Status::VERIFIED;
        $user->sv     = $general->sv ? Status::UNVERIFIED : Status::VERIFIED;
        $user->ts     = 0;
        $user->tv     = 1;

        // REAL DATA DETECTION BY IP
        $ip = getRealIP();
        $xml = @simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=".$ip);

        // Default Values
        $country = $data['country_name'];
        $city = '';
        $state = '';
        $zip = '';
        
        // Try GeoPlugin first
        if ($xml && $xml->geoplugin_countryName != null) {
            $country = (string) $xml->geoplugin_countryName;
            $city = (string) $xml->geoplugin_city;
            $state = (string) $xml->geoplugin_region;
            $zip = ''; // GeoPlugin doesn't reliably give zip
        } else {
            // Fallback to ipwho.is
             $ApiResponse = @json_decode(file_get_contents("http://ipwho.is/".$ip), true);
             if ($ApiResponse && $ApiResponse['success']) {
                 $country = $ApiResponse['country'];
                 $city = $ApiResponse['city'];
                 $state = $ApiResponse['region'];
                 $zip = $ApiResponse['postal'];
             }
        }

        $user->address = [
            'address' => '',
            'state'   => $state,
            'zip'     => $zip,
            'country' => $country,
            'city'    => $city
        ];

        // Explicitly save separate columns
        $user->dial_code = $data['mobile_code'];
        $user->city      = $city;
        $user->state     = $state;
        $user->zip       = $zip;
        
        $user->save();


        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New member registered';
        $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
        $adminNotification->save();


        //Login Log Create
        $ip        = getRealIP();
        $exist     = UserLogin::where('user_ip', $ip)->first();
        $userLogin = new UserLogin();

        //Check exist or not
        if ($exist) {
            $userLogin->longitude    = $exist->longitude;
            $userLogin->latitude     = $exist->latitude;
            $userLogin->city         = $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country      = $exist->country;
        } else {
            $info                    = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude    = is_array(@$info['long']) ? implode(',', $info['long']) : @$info['long'];
            $userLogin->latitude     = is_array(@$info['lat']) ? implode(',', $info['lat']) : @$info['lat'];
            $userLogin->city         = is_array(@$info['city']) ? implode(',', $info['city']) : @$info['city'];
            $userLogin->country_code = is_array(@$info['code']) ? implode(',', $info['code']) : @$info['code'];
            $userLogin->country      = is_array(@$info['country']) ? implode(',', $info['country']) : @$info['country'];
        }

        $userAgent          = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip = $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os      = @$userAgent['os_platform'];
        $userLogin->save();


        return $user;
    }



  public function registered(Request $request, $user)
{
    if (gs('signup_bonus_control') == Status::YES) {

        $user->interest_wallet += gs('signup_bonus_amount');
        $user->save();

        $transaction = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = gs('signup_bonus_amount');
        $transaction->charge       = 0;
        $transaction->post_balance = $user->interest_wallet;
        $transaction->trx_type     = '+';
        $transaction->trx          = getTrx();
        $transaction->wallet_type  = 'interest_wallet';
        $transaction->remark       = 'registration_bonus';
        $transaction->details      = 'You have got registration bonus';
        $transaction->save();
    }

    $parentUser = User::find($user->ref_by);

    if ($parentUser) {
        notify($parentUser, 'REFERRAL_JOIN', [
            'ref_username' => $user->username
        ]);
    }

    return to_route('user.home');
}


}
