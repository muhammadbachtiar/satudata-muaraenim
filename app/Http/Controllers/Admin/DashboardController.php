<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavMenu;
use App\Models\Page;
use App\Models\Publication;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'menuCount' => NavMenu::count(),
            'pageCount' => Page::count(),
            'beritaCount' => Publication::berita()->count(),
            'infografisCount' => Publication::infografis()->count(),
            'recentPublications' => Publication::orderBy('published_at', 'desc')->take(5)->get(),
            'navMenus' => NavMenu::with('children')->whereNull('parent_id')->orderBy('order')->get(),
        ]);
    }
}
