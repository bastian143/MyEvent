<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_events' => Event::count(),
            'pending_events' => Event::where('status', 'pending')->count(),
            'total_categories' => Category::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
