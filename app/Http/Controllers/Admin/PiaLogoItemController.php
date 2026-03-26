<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLogoItemRequest;
use App\Http\Requests\UpdateLogoItemRequest;
use App\Models\PiaLogoItem;
use App\Models\PiaPage;
use Illuminate\Support\Facades\Storage;

class PiaLogoItemController extends Controller
{
    public function store(StoreLogoItemRequest $request)
    {
        $page = PiaPage::firstOrFail();

        $logoPath = $request->file('logo')->store('pia/logos', 'public');

        $maxPosition = PiaLogoItem::where('pia_page_id', $page->id)->max('position') ?? 0;

        PiaLogoItem::create([
            'pia_page_id' => $page->id,
            'title'       => $request->title,
            'link_url'    => $request->link_url,
            'logo_path'   => $logoPath,
            'position'    => $maxPosition + 1,
        ]);

        return redirect()->route('admin.pia.index')->with('success', __('messages.flash_created', ['item' => 'Logo']));
    }

    public function update(UpdateLogoItemRequest $request, PiaLogoItem $logoItem)
    {
        $data = [
            'title'    => $request->title,
            'link_url' => $request->link_url,
        ];

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($logoItem->logo_path) {
                Storage::disk('public')->delete($logoItem->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('pia/logos', 'public');
        }

        $logoItem->update($data);

        return redirect()->route('admin.pia.index')->with('success', __('messages.flash_updated', ['item' => 'Logo']));
    }

    public function destroy(PiaLogoItem $logoItem)
    {
        if ($logoItem->logo_path) {
            Storage::disk('public')->delete($logoItem->logo_path);
        }

        $logoItem->delete();

        return redirect()->route('admin.pia.index')->with('success', __('messages.flash_deleted', ['item' => 'Logo']));
    }
}
