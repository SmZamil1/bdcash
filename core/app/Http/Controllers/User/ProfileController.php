<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function profile()
    {
        $pageTitle = "Profile Setting";
        $user = auth()->user();
        return view('Template::user.profile_setting', compact('pageTitle','user'));
    }

    public function submitProfile(Request $request)
    {
        $request->validate([
            'image' => [
                'nullable',
                'image',
                new \App\Rules\FileTypeValidate(['jpg', 'jpeg', 'png'])
            ],
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'mobile' => 'required|string|max:20|unique:users,mobile,' . auth()->id(),
        ]);
        
        $user = auth()->user();

        if ($request->hasFile('image')) {
            try {
                $old = $user->image;
                $user->image = fileUploader($request->image, getFilePath('userProfile'), getFileSize('userProfile'), $old);
            } catch (\Exception $exp) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Couldn\'t upload your image']);
                }
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        // Update email and mobile
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->save();
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
        }

        $notify[] = ['success', 'Profile updated successfully'];
        return back()->withNotify($notify);
    }

    public function changePassword()
    {
        $pageTitle = 'Change Password';
        $user = auth()->user();
        return view('Template::user.password', compact('pageTitle', 'user'));
    }

    public function submitPassword(Request $request)
    {

        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $request->validate([
            'current_password' => 'required',
            'password' => ['required','confirmed',$passwordValidation]
        ]);

        $user = auth()->user();
        if (Hash::check($request->current_password, $user->password)) {
            $password = Hash::make($request->password);
            $user->password = $password;
            $user->save();
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Password changed successfully']);
            }

            $notify[] = ['success', 'Password changed successfully'];
            return back()->withNotify($notify);
        } else {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'The password doesn\'t match!']);
            }
            $notify[] = ['error', 'The password doesn\'t match!'];
            return back()->withNotify($notify);
        }
    }
}
