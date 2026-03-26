<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstansiTerkait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstansiTerkaitController extends Controller
{
    public function index()
    {
        $instansi = InstansiTerkait::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.setting.instansi-terkait', compact('instansi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'nullable|string|max:255',
            'logo'  => 'required|image|max:4096',
            'link'  => 'nullable|url|max:500',
        ]);

        $path = $request->file('logo')->store('assets/image/instansi', 'public');

        InstansiTerkait::create([
            'nama'       => $request->nama,
            'logo'       => 'storage/' . $path,
            'link'       => $request->link,
            'sort_order' => InstansiTerkait::max('sort_order') + 1,
        ]);

        return redirect()->route('admin.setting.instansi-terkait')
            ->with('success', __('messages.flash_created', ['item' => __('messages.admin_instansi_terkait')]));
    }

    public function update(Request $request, InstansiTerkait $instansiTerkait)
    {
        $request->validate([
            'nama' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:4096',
            'link' => 'nullable|url|max:500',
        ]);

        $data = [
            'nama' => $request->nama,
            'link' => $request->link,
        ];

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('assets/image/instansi', 'public');
            $data['logo'] = 'storage/' . $path;
        }

        $instansiTerkait->update($data);

        return redirect()->route('admin.setting.instansi-terkait')
            ->with('success', __('messages.flash_updated', ['item' => __('messages.admin_instansi_terkait')]));
    }

    public function destroy(InstansiTerkait $instansiTerkait)
    {
        $instansiTerkait->delete();
        return redirect()->route('admin.setting.instansi-terkait')
            ->with('success', __('messages.flash_deleted', ['item' => __('messages.admin_instansi_terkait')]));
    }
}
