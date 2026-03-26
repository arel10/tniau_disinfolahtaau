<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateHistoryRequest;
use App\Models\PiaHistoryRevision;
use App\Models\PiaPage;
use Illuminate\Support\Facades\Auth;

class PiaAdminController extends Controller
{
    public function index()
    {
        $page = PiaPage::with('logoItems')->firstOrFail();
        return view('admin.pia.index', compact('page'));
    }

    public function updateHistory(UpdateHistoryRequest $request)
    {
        $page = PiaPage::firstOrFail();

        // Save old version to revisions before updating
        if ($page->history_title || $page->history_content) {
            PiaHistoryRevision::create([
                'pia_page_id'        => $page->id,
                'old_history_title'  => $page->history_title,
                'old_history_content'=> $page->history_content,
                'edited_by'          => Auth::id(),
                'edited_at'          => now(),
            ]);
        }

        $page->update($request->validated());

        return redirect()->route('admin.pia.index')->with('success', __('messages.flash_updated', ['item' => __('messages.admin_sejarah')]));
    }

    public function destroyHistory()
    {
        $page = PiaPage::firstOrFail();

        // Save old version to revisions before clearing
        if ($page->history_title || $page->history_content) {
            PiaHistoryRevision::create([
                'pia_page_id'        => $page->id,
                'old_history_title'  => $page->history_title,
                'old_history_content'=> $page->history_content,
                'edited_by'          => Auth::id(),
                'edited_at'          => now(),
            ]);
        }

        $page->update([
            'history_title'   => null,
            'history_content' => null,
        ]);

        return redirect()->route('admin.pia.index')->with('success', __('messages.flash_deleted', ['item' => __('messages.admin_sejarah')]));
    }

    public function revisions()
    {
        $page = PiaPage::firstOrFail();
        $revisions = $page->revisions()->with('editor')->paginate(20);
        return view('admin.pia.revisions', compact('page', 'revisions'));
    }
}
