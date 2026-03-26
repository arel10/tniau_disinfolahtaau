<?php

namespace App\Http\Controllers;

use App\Models\CustomMenu;

class CustomPageController extends Controller
{
    /**
     * Show a custom page by slug.
     * URL: /halaman/{slug} or /halaman/{parentSlug}/{childSlug}
     */
    public function show($slug, $childSlug = null)
    {
        if ($childSlug) {
            // Sub-menu page
            $parent = CustomMenu::where('slug', $slug)->published()->firstOrFail();
            $menu = CustomMenu::where('slug', $childSlug)
                ->where('parent_id', $parent->id)
                ->published()
                ->with('activeWidgets.media')
                ->firstOrFail();
        } else {
            // Top-level page (or direct slug)
            $menu = CustomMenu::where('slug', $slug)
                ->published()
                ->with(['activeWidgets.media', 'children' => fn($q) => $q->published()->ordered()])
                ->firstOrFail();

            // If top-level has children but no widgets, redirect to first child
            if ($menu->activeWidgets->isEmpty() && $menu->children->count()) {
                $firstChild = $menu->children->first();
                return redirect('/halaman/' . $menu->slug . '/' . $firstChild->slug);
            }
        }

        $parent = $menu->parent;

        return response()
            ->view('public.custom-page.show', compact('menu', 'parent'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
