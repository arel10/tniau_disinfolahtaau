<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuUtama;
use Illuminate\Http\Request;

class MenuUtamaController extends Controller
{
    public function index()
    {
        $items   = MenuUtama::orderBy('sort_order')->orderBy('id')->get();
        $options = MenuUtama::$options;
        return view('admin.setting.menu-utama', compact('items', 'options'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:100',
            'route_name' => 'required|string|max:100',
        ]);

        $maxOrder = MenuUtama::max('sort_order') ?? 0;

        MenuUtama::create([
            'nama'       => $request->nama,
            'route_name' => $request->route_name,
            'sort_order' => $maxOrder + 1,
        ]);

        return back()->with('success', __('messages.flash_created', ['item' => 'Menu']));
    }

    public function destroy(MenuUtama $menuUtama)
    {
        $menuUtama->delete();
        return back()->with('success', __('messages.flash_deleted', ['item' => 'Menu']));
    }
}
