<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the users with chart data.
     */
    public function index()
    {
        // Fetch all users
        $users = User::with('roles')->get();

        // Prepare data for the chart: count users by role
        $userRoleCounts = $users->groupBy(function ($user) {
            return $user->roles->pluck('name')->implode(', ');
        })->map->count();

        // Return the view with both users and userRoleCounts for the chart
        return view('content.management.user', compact('users', 'userRoleCounts'));
    }
}
