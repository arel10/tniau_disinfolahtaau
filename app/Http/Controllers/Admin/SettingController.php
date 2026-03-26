<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function background()
    {
        $settings = [
            'hero_title'         => setting('hero_title',         'TNI AU - Disinfolahtaau'),
            'hero_subtitle'      => setting('hero_subtitle',      'Dinas Informasi dan Pengolahan Data TNI Angkatan Udara'),
            'hero_media_type'    => setting('hero_media_type',    'video'),   // 'video' or 'image'
            'hero_video'           => setting('hero_video',           'assets/video/backround.mp4'),
            'hero_image'           => setting('hero_image',           ''),
            'hero_media_rotation'  => (int) setting('hero_media_rotation',  180),
            'page_hero_bg'         => setting('page_hero_bg',         'assets/image/pesawat.jpg'),
            'page_hero_bg_type'    => setting('page_hero_bg_type',    'image'),
            'page_hero_bg_rotation'=> (int) setting('page_hero_bg_rotation', 0),
            'page_hero_bg_position'=> setting('page_hero_bg_position', 'center 20%'),
            'footer_description'   => setting('footer_description',   'Dinas Informasi & Pengolahan Data TNI Angkatan Udara.'),
            'login_bg'             => setting('login_bg',             'assets/video/backround.mp4'),
            'login_bg_type'        => setting('login_bg_type',        'video'),
            'login_bg_rotation'    => (int) setting('login_bg_rotation', 0),
        ];

        return view('admin.background.index', compact('settings'));
    }

    public function backgroundUpdate(Request $request)
    {
        $request->validate([
            'hero_title'         => 'required|string|max:255',
            'hero_subtitle'      => 'required|string|max:500',
            'hero_media_file'      => 'nullable|file|mimes:mp4,webm,ogv,jpg,jpeg,png,gif,webp',
            'hero_media_rotation'  => 'nullable|integer|in:0,90,180,270',
            'page_hero_bg_file'    => 'nullable|file|mimes:mp4,webm,ogv,jpg,jpeg,png,gif,webp',
            'page_hero_bg_rotation'=> 'nullable|integer|in:0,90,180,270',
            'page_hero_bg_position'=> 'nullable|string|max:50',
            'login_bg_file'        => 'nullable|file|mimes:mp4,webm,ogv,jpg,jpeg,png,gif,webp',
            'login_bg_rotation'    => 'nullable|integer|in:0,90,180,270',
            'footer_description'   => 'nullable|string|max:500',
        ]);

        $data = [
            'hero_title'           => $request->hero_title,
            'hero_subtitle'        => $request->hero_subtitle,
            'hero_media_rotation'  => (int) $request->hero_media_rotation,
            'page_hero_bg_rotation'=> (int) $request->page_hero_bg_rotation,
            'page_hero_bg_position'=> $request->page_hero_bg_position ?? 'center 20%',
            'login_bg_rotation'    => (int) $request->login_bg_rotation,
            'footer_description'   => $request->footer_description ?? '',
        ];

        // Handle hero media (video or image)
        if ($request->hasFile('hero_media_file')) {
            $file = $request->file('hero_media_file');
            $mime = $file->getMimeType();
            $isVideo = str_starts_with($mime, 'video/');

            if ($isVideo) {
                $path = $file->store('assets/video', 'public');
                $data['hero_video']      = 'storage/' . $path;
                $data['hero_media_type'] = 'video';
            } else {
                $path = $file->store('assets/image', 'public');
                $data['hero_image']      = 'storage/' . $path;
                $data['hero_media_type'] = 'image';
            }
        }

        // Handle page hero background (image or video)
        if ($request->hasFile('page_hero_bg_file')) {
            $bgFile  = $request->file('page_hero_bg_file');
            $bgMime  = $bgFile->getMimeType();
            $bgIsVid = str_starts_with($bgMime, 'video/');

            if ($bgIsVid) {
                $bgPath = $bgFile->store('assets/video', 'public');
                $data['page_hero_bg']      = 'storage/' . $bgPath;
                $data['page_hero_bg_type'] = 'video';
            } else {
                $bgPath = $bgFile->store('assets/image', 'public');
                $data['page_hero_bg']      = 'storage/' . $bgPath;
                $data['page_hero_bg_type'] = 'image';
            }
        }

        // Handle login background (image or video)
        if ($request->hasFile('login_bg_file')) {
            $loginFile  = $request->file('login_bg_file');
            $loginMime  = $loginFile->getMimeType();
            $loginIsVid = str_starts_with($loginMime, 'video/');

            if ($loginIsVid) {
                $loginPath = $loginFile->store('assets/video', 'public');
                $data['login_bg']      = 'storage/' . $loginPath;
                $data['login_bg_type'] = 'video';
            } else {
                $loginPath = $loginFile->store('assets/image', 'public');
                $data['login_bg']      = 'storage/' . $loginPath;
                $data['login_bg_type'] = 'image';
            }
        }

        foreach ($data as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now(), 'created_at' => now()]
            );
        }

        return redirect()->route('admin.setting.background')
            ->with('success', __('messages.flash_saved'));
    }

    public function alamat()
    {
        return view('admin.setting.alamat');
    }

    public function alamatUpdate(Request $request)
    {
        $request->validate([
            'alamat_text' => 'required|string|max:500',
            'alamat_link' => 'nullable|url|max:500',
        ]);

        foreach (['alamat_text', 'alamat_link'] as $key) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $request->$key ?? '', 'updated_at' => now(), 'created_at' => now()]
            );
        }

        return redirect()->route('admin.setting.alamat')
            ->with('success', __('messages.flash_saved'));
    }
}
