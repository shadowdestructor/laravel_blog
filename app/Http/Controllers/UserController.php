<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
class UserController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $stats = [
            'posts' => $user->posts()->count(),
            'published_posts' => $user->posts()->where('status', 'published')->count(),
            'draft_posts' => $user->posts()->where('status', 'draft')->count(),
            'comments' => $user->comments()->count(),
            'total_views' => $user->posts()->sum('views_count')
        ];

        $recentPosts = $user->posts()->with('category')->latest()->limit(5)->get();
        $recentComments = $user->comments()->with('post')->latest()->limit(5)->get();

        return view('dashboard', compact('stats', 'recentPosts', 'recentComments'));
    }

    public function profile()
    {
        return view('profile');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        // Update basic info
        $user->name = $request->name;
        $user->email = $request->email;
        $user->bio = $request->bio;

        // Update password if provided
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mevcut şifre yanlış.']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil bilgileriniz güncellendi.');
    }
}