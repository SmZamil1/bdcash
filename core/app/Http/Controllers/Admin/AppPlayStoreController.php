<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppPlayStoreController extends Controller
{
    public function index()
    {
        $pageTitle = 'App Play Store Settings';
        $setting = \App\Models\AppPlayStoreSetting::firstOrNew();
        return view('admin.app_settings.index', compact('pageTitle', 'setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string',
            'developer' => 'required|string',
            'version' => 'required|string',
            'size' => 'required|string',
            'downloads' => 'required|string',
            'rating' => 'required|string',
            'reviews_count' => 'required|string',
            'download_link' => 'required|url',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'screenshots.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $setting = \App\Models\AppPlayStoreSetting::firstOrNew();
        
        $setting->app_name = $request->app_name;
        $setting->developer = $request->developer;
        $setting->version = $request->version;
        $setting->size = $request->size;
        $setting->downloads = $request->downloads;
        $setting->rating = $request->rating;
        $setting->reviews_count = $request->reviews_count;
        $setting->description = $request->description;
        $setting->download_link = $request->download_link;
        // Tags can be comma separated string from input
        $setting->tags = explode(',', $request->tags);

        // Logo Upload
        if ($request->hasFile('app_logo')) {
            try {
                $path = getFilePath('app_logo');
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $setting->app_logo = fileUploader($request->app_logo, $path, null, $setting->app_logo);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Could not upload app logo'];
                return back()->withNotify($notify);
            }
        }

        // Screenshots Upload
        if ($request->hasFile('screenshots')) {
            try {
                $path = getFilePath('app_screenshots');
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                
                $currentScreenshots = $setting->screenshots ?? [];
                
                foreach ($request->file('screenshots') as $file) {
                    $filename = fileUploader($file, $path);
                    $currentScreenshots[] = $filename;
                }
                
                $setting->screenshots = $currentScreenshots;

            } catch (\Exception $exp) {
                $notify[] = ['error', 'Could not upload screenshots: ' . $exp->getMessage()];
                return back()->withNotify($notify);
            }
        }

        // Handle screenshot deletion if provided
        if ($request->has('delete_screenshots')) {
            $path = getFilePath('app_screenshots');
            $currentScreenshots = $setting->screenshots ?? [];
            foreach ($request->delete_screenshots as $filename) {
                if (($key = array_search($filename, $currentScreenshots)) !== false) {
                    fileManager()->removeFile($path . '/' . $filename);
                    unset($currentScreenshots[$key]);
                }
            }
             $setting->screenshots = array_values($currentScreenshots);
        }

        $setting->save();

        $notify[] = ['success', 'App settings updated successfully'];
        return back()->withNotify($notify);
    }
}
