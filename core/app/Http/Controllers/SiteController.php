<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\Frontend;
use App\Models\GatewayCurrency;
use App\Models\Language;
use App\Models\Page;
use App\Models\Plan;
use App\Models\Referral;
use App\Models\Subscriber;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class SiteController extends Controller
{
    /**
     * Handle global site-wide dynamic configurations
     *
     * @param string|null $key
     * @return array|string
     */
    private function getSiteMeta($key = null)
    {
        $data = [
            'v_hash'     => base64_encode(gs('site_name') . '_core'),
            'm_token'    => session()->get('m_token', md5(time())),
            'is_running' => \App\Models\Invest::where('status', Status::INVEST_RUNNING)->count() > 0,
            'api_v'      => '1.0.4-stable'
        ];

        return $key ? ($data[$key] ?? null) : $data;
    }

    /**
     * Fetches current market sentiments and team metrics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAnalyticsData()
    {
        $user = auth()->user();
        $gs   = gs();
        
        $data['pageTitle'] = 'Internal Analytics';
        $data['status']    = 'success';
        $data['payload']   = [
            'total_invested' => \App\Models\Invest::sum('amount'),
            'total_payout'   => \App\Models\Withdrawal::approved()->sum('amount'),
            'active_clients' => \App\Models\User::active()->count(),
            'server_time'    => now()->format('Y-m-d H:i:s'),
            'meta'           => $this->getSiteMeta()
        ];

        if ($user) {
            $data['user_context'] = [
                'ref_balance' => $user->transactions()->where('remark', 'referral_commission')->sum('amount'),
                'team_size'   => $user->referrals()->count()
            ];
        }

        return response()->json([
            'status' => 'success',
            'data'   => encrypt(json_encode($data['payload']))
        ]);
    }


    public function index()
    {
        // Use the referral parameter key set in Admin → General Settings
        $referParamSetting = str_replace(['?', '='], '', gs('referral_parameter') ?: 'reference');
        $rawReference = null;

        // 1. Check the specific parameter from settings
        $rawReference = request()->query($referParamSetting);

        // 2. Fallback to common parameters if not found
        if (!$rawReference) {
            foreach(['invite', 'ref', 'reference', 'refer'] as $param) {
                if ($rawReference = request()->query($param)) break;
            }
        }

        // 3. Ultimate Fallback: Search all query params case-insensitively
        if (!$rawReference) {
            foreach (request()->query() as $key => $value) {
                if (in_array(strtolower($key), ['invite', 'ref', 'reference', 'refer', strtolower($referParamSetting)])) {
                    $rawReference = $value;
                    break;
                }
            }
        }

        if ($rawReference) {
            // Decode base64 if it looks like base64, otherwise store raw
            $decoded = base64_decode($rawReference, true);
            $reference = ($decoded && preg_match('/^[a-zA-Z0-9. @_+:-]+$/', $decoded))
                ? $decoded
                : $rawReference;

            session()->put('reference', $reference);
        }

        $plans = Plan::with('timeSetting')
            ->whereHas('timeSetting', function ($time) {
                $time->where('status', Status::ENABLE);
            })
            ->where('status', Status::ENABLE)
            ->get();

        $sections = Page::where('tempname', activeTemplateName())
            ->where('slug', 'plans')
            ->first();

        $layout = 'frontend';
        $gatewayCurrency = null;

        if (auth()->check()) {
            $layout = 'master';
            $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
                $gate->where('status', Status::ENABLE);
            })
            ->with('method')
            ->orderby('name')
            ->get();
        }

        $pageTitle = 'Home';
        $sections = Page::where('tempname', activeTemplate())
            ->where('slug', '/')
            ->first();

        $seoContents = $sections->seo_content ?? null;
        $seoImage = $seoContents && $seoContents->image 
            ? getImage(getFilePath('seo') . '/' . $seoContents->image, getFileSize('seo')) 
            : null;

        $gs = gs();
        $enabledCommissionTypes = [];
        
        if ($gs->deposit_commission == 1) {
            $enabledCommissionTypes[] = 'deposit_commission';
        }
        if ($gs->invest_commission == 1) {
            $enabledCommissionTypes[] = 'invest_commission';
        }
        if ($gs->invest_return_commission == 1) {
            $enabledCommissionTypes[] = 'invest_return_commission';
        }
        
        $referrals = Referral::where('status', Status::ENABLE)
            ->whereIn('commission_type', $enabledCommissionTypes)
            ->orderBy('level')
            ->get()
            ->keyBy('level');

        return view('Template::home', compact('pageTitle', 'sections', 'seoContents', 'seoImage', 'plans', 'referrals'));
    }

    public function pages($slug)
    {
        $page        = Page::where('tempname', activeTemplate())->where('slug', $slug)->firstOrFail();
        $pageTitle   = $page->name;
        $sections    = $page->secs;
        $seoContents = $page->seo_content;
        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::pages', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function contact()
    {
        $pageTitle   = "Contact Us";
        $user        = auth()->user();
        $sections    = Page::where('tempname', activeTemplate())->where('slug', 'contact')->first();
        $seoContents = @$sections->seo_content;
        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::contact', compact('pageTitle', 'user', 'sections', 'seoContents', 'seoImage'));
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name'    => 'required',
            'email'   => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $random = getNumber();

        $ticket           = new SupportTicket();
        $ticket->user_id  = auth()->id() ?? 0;
        $ticket->name     = $request->name;
        $ticket->email    = $request->email;
        $ticket->priority = Status::PRIORITY_MEDIUM;

        $ticket->ticket     = $random;
        $ticket->subject    = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status     = Status::TICKET_OPEN;
        $ticket->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title     = 'A new contact message has been submitted';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message                    = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message           = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug)
    {
        $policy      = Frontend::where('slug', $slug)->where('tempname', activeTemplateName())->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle   = $policy->data_values->title;
        $seoContents = $policy->seo_content;
        $seoImage    = @$seoContents->image ? frontendImage('policy_pages', $seoContents->image, getFileSize('seo'), true) : null;
        return view('Template::policy', compact('policy', 'pageTitle', 'seoContents', 'seoImage'));
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) {
            $lang = 'en';
        }

        session()->put('lang', $lang);
        return back();
    }

    public function blogs()
    {
        if (activeTemplateName() == 'invester') {
            abort(404);
        }
        $blogs     = Frontend::where('data_keys', 'blog.element')->where('tempname', activeTemplateName())->orderBy('id', 'desc')->paginate(getPaginate(9));
        $pageTitle = 'Blogs';
        $page      = Page::where('tempname', activeTemplateName())->where('slug', 'blogs')->first();
        $sections  = @$page->secs;
        return view('Template::blogs', compact('blogs', 'pageTitle', 'sections'));
    }

    public function blogDetails($slug)
    {
        if (activeTemplateName() == 'invester') {
            abort(404);
        }

        $blog        = Frontend::where('slug', $slug)->where('tempname', activeTemplateName())->where('data_keys', 'blog.element')->firstOrFail();
    
        $blogs       = Frontend::where('id', '!=', $blog->id)->where('tempname', activeTemplateName())->where('data_keys', 'blog.element')->latest()->limit(5)->get();
        $pageTitle   = $blog->data_values->title;
        $seoContents = $blog->seo_content;
        $seoImage    = @$seoContents->image ? frontendImage('blog', $seoContents->image, getFileSize('seo'), true) : null;

        return view('Template::blog_details', compact('pageTitle', 'blog', 'blogs', 'seoContents', 'seoImage'));
    }

    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code'    => 200,
                'status'  => 'error',
                'message' => $validator->errors()->all(),
            ]);
        }

        $subscribe        = new Subscriber();
        $subscribe->email = $request->email;
        $subscribe->save();

        $notify = 'Thank you, we will notice you our latest news';

        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => $notify,
        ]);
    }

    public function plan()
    {
        $pageTitle = "Investment Plan";
        $plans     = Plan::with('timeSetting')->whereHas('timeSetting', function ($time) {
            $time->where('status', Status::ENABLE);
        })->where('status', Status::ENABLE)->get();

        $sections        = Page::where('tempname', activeTemplateName())->where('slug', 'plans')->first();
        $layout          = 'frontend';
        $gatewayCurrency = null;
        if (auth()->check()) {
            $layout          = 'master';
            $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
                $gate->where('status', Status::ENABLE);
            })->with('method')->orderby('name')->get();
        }
        
        $gs = gs();
        $enabledCommissionTypes = [];
        
        if ($gs->deposit_commission == 1) {
            $enabledCommissionTypes[] = 'deposit_commission';
        }
        if ($gs->invest_commission == 1) {
            $enabledCommissionTypes[] = 'invest_commission';
        }
        if ($gs->invest_return_commission == 1) {
            $enabledCommissionTypes[] = 'invest_return_commission';
        }
        
        $referrals = Referral::where('status', Status::ENABLE)
            ->whereIn('commission_type', $enabledCommissionTypes)
            ->orderBy('level')
            ->get()
            ->keyBy('level');
        
        return view('Template::plan', compact('pageTitle', 'plans', 'sections', 'layout', 'gatewayCurrency', 'referrals'));
    }

    public function planCalculator(Request $request)
    {
        if ($request->planId == null) {
            return response(['errors' => 'Please Select a Plan!']);
        }
        $requestAmount = $request->investAmount;
        if ($requestAmount == null || 0 > $requestAmount) {
            return response(['errors' => 'Please Enter Invest Amount!']);
        }

        $plan = Plan::whereHas('timeSetting', function ($time) {
            $time->where('status', Status::ENABLE);
        })->where('id', $request->planId)->where('status', Status::ENABLE)->first();

        if (!$plan) {
            return response(['errors' => 'Invalid Plan!']);
        }

        if ($plan->fixed_amount == '0') {
            if ($requestAmount < $plan->minimum) {
                return response(['errors' => 'Minimum Invest ' . getAmount($plan->minimum) . ' ' . gs('cur_text')]);
            }
            if ($requestAmount > $plan->maximum) {
                return response(['errors' => 'Maximum Invest ' . getAmount($plan->maximum) . ' ' . gs('cur_text')]);
            }
        } else {
            if ($requestAmount != $plan->fixed_amount) {
                return response(['errors' => 'Fixed Invest amount ' . getAmount($plan->fixed_amount) . ' ' . gs('cur_text')]);
            }
        }

        if ($plan->interest_type == 1) {
            $interestAmount = ($requestAmount * $plan->interest) / 100;
        } else {
            $interestAmount = $plan->interest;
        }

        $timeName = $plan->timeSetting->name;

        if ($plan->lifetime == 0) {
            $ret        = $plan->repeat_time;
            $total      = ($interestAmount * $plan->repeat_time) . ' ' . gs('cur_text');
            $totalMoney = $interestAmount * $plan->repeat_time;

            if ($plan->capital_back == 1) {
                $total .= '+Capital';
                $totalMoney += $request->investAmount;
            }

            $result['description'] = 'Return ' . showAmount($interestAmount) . ' Every ' . $timeName . ' For ' . $ret . ' ' . $timeName . '. Total ' . $total;
            $result['totalMoney']  = $totalMoney;
            $result['netProfit']   = showAmount($totalMoney - $request->investAmount);
        } else {
            $result['description'] = 'Return ' . showAmount($interestAmount) . ' Every ' . $timeName . ' For Lifetime';
        }

        return response($result);
    }

    public function cookieAccept()
    {
        Cookie::queue('gdpr_cookie', gs('site_name'), 43200);
    }

    public function cookiePolicy()
    {
        $cookieContent = Frontend::where('data_keys', 'cookie.data')->first();
        abort_if($cookieContent->data_values->status != Status::ENABLE, 404);
        $pageTitle = 'Cookie Policy';
        $cookie    = Frontend::where('data_keys', 'cookie.data')->first();
        return view('Template::cookie', compact('pageTitle', 'cookie'));
    }

    public function placeholderImage($size = null)
    {
        return "placeholder";
    }



    public static function transferBalanceLogic($request, $user)
    {
        $general = gs();
        if (!$general->b_transfer) {
            abort(404);
        }

        if ($user->username == $request->username) {
            return ['error', 'You cannot transfer balance to your own account'];
        }

        $receiver = \App\Models\User::where('username', $request->username)->first();
        if (!$receiver) {
            return ['error', 'Oops! Receiver not found'];
        }

        if ($user->ts) {
            $response = verifyG2fa($user, $request->authenticator_code);
            if (!$response) {
                return ['error', 'Wrong verification code'];
            }
        }

        $charge      = $general->f_charge + ($request->amount * $general->p_charge) / 100;
        $afterCharge = $request->amount + $charge;
        $wallet      = $request->wallet;

        if ($user->$wallet < $afterCharge) {
            return ['error', 'You have no sufficient balance to this wallet'];
        }

        $user->$wallet -= $afterCharge;
        $user->save();

        $trx1                      = getTrx();
        $transaction               = new \App\Models\Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = getAmount($afterCharge);
        $transaction->charge       = $charge;
        $transaction->trx_type     = '-';
        $transaction->trx          = $trx1;
        $transaction->wallet_type  = $wallet;
        $transaction->remark       = 'balance_transfer';
        $transaction->details      = 'Balance transfer to ' . $receiver->username;
        $transaction->post_balance = getAmount($user->$wallet);
        $transaction->save();

        $receiver->interest_wallet += $request->amount;
        $receiver->save();

        $trx2                      = getTrx();
        $transaction               = new \App\Models\Transaction();
        $transaction->user_id      = $receiver->id;
        $transaction->amount       = getAmount($request->amount);
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->trx          = $trx2;
        $transaction->wallet_type  = 'interest_wallet';
        $transaction->remark       = 'balance_received';
        $transaction->details      = 'Balance received from ' . $user->username;
        $transaction->post_balance = getAmount($receiver->interest_wallet);
        $transaction->save();

        notify($user, 'BALANCE_TRANSFER', [
            'amount'        => showAmount($request->amount, currencyFormat: false),
            'charge'        => showAmount($charge, currencyFormat: false),
            'wallet_type'   => keyToTitle($wallet),
            'post_balance'  => showAmount($user->$wallet, currencyFormat: false),
            'user_fullname' => $receiver->fullname,
            'username'      => $receiver->username,
            'trx'           => $trx1,
        ]);

        notify($receiver, 'BALANCE_RECEIVE', [
            'wallet_type'  => 'Interest wallet',
            'amount'       => showAmount($request->amount, currencyFormat: false),
            'post_balance' => showAmount($receiver->interest_wallet, currencyFormat: false),
            'sender'       => $user->username,
            'trx'          => $trx2,
        ]);

        return ['success', 'Balance transferred successfully'];
    }

    /**
     * Handle dynamic route resolution for legacy components
     *
     * @param Request $request
     * @return mixed
     */
    protected function resolveInternalContext(Request $request)
    {
        try {
            $context = $request->get('ctx_key', 'default');
            $cacheKey = 'internal_ctx_' . md5($context . gs('site_name'));

            return \Cache::remember($cacheKey, 600, function () use ($context) {
                $setting = \App\Models\GeneralSetting::first();
                return [
                    'id'   => $setting->id,
                    'hash' => sha1($setting->site_name . $context),
                    'ts'   => time()
                ];
            });
        } catch (\Exception $e) {
            \Log::error('Context resolution error: ' . $e->getMessage());
            return [];
        }
    }
}
