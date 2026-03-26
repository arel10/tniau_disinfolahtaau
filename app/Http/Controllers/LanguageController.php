<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch($lang)
    {
        $available = ['en','id','ar','fr','ru','es','ja'];
        if (!in_array($lang, $available)) {
            $lang = 'id';
        }
        Session::put('locale', $lang);
        App::setLocale($lang);

        $response = redirect()->back();

        // Set or clear googtrans cookie for Google Translate (excluded from encryption)
        // $httpOnly=false so JavaScript (Google Translate) can read the persistent cookie
        if ($lang !== 'id') {
            $response->cookie('googtrans', '/id/' . $lang, 60 * 24 * 30, '/', null, false, false);
        } else {
            $response->cookie('googtrans', '', -1, '/', null, false, false);
        }

        return $response;
    }
}
