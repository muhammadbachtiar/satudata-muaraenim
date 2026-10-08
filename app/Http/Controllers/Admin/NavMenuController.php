<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NavMenuRequest;
use App\Models\NavMenu;
use App\Models\Page;

class NavMenuController extends Controller
{
    public function index()
    {
        $menus = NavMenu::with('children', 'page')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return view('admin.nav-menus.index', compact('menus'));
    }

    public function create()
    {
        $parents = NavMenu::whereNull('parent_id')->orderBy('order')->get();
        $pages = Page::published()->orderBy('title')->get();

        return view('admin.nav-menus.form', [
            'menu' => null,
            'parents' => $parents,
            'pages' => $pages,
        ]);
    }

    public function store(NavMenuRequest $request)
    {
        NavMenu::create($request->validated());

        return redirect()->route('admin.nav-menus.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(NavMenu $navMenu)
    {
        $parents = NavMenu::whereNull('parent_id')
            ->where('id', '!=', $navMenu->id)
            ->orderBy('order')
            ->get();
        $pages = Page::published()->orderBy('title')->get();

        return view('admin.nav-menus.form', [
            'menu' => $navMenu,
            'parents' => $parents,
            'pages' => $pages,
        ]);
    }

    public function update(NavMenuRequest $request, NavMenu $navMenu)
    {
        $navMenu->update($request->validated());

        return redirect()->route('admin.nav-menus.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(NavMenu $navMenu)
    {
        $navMenu->delete();

        return redirect()->route('admin.nav-menus.index')
            ->with('success', 'Menu berhasil dihapus.');
    }
}
