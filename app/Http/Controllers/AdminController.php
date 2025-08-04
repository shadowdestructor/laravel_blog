<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'pending_posts' => Post::where('status', 'pending')->count(),
            'total_comments' => Comment::count(),
            'pending_comments' => Comment::where('status', 'pending')->count(),
            'total_categories' => Category::count()
        ];

        $recentUsers = User::with('role')->latest()->limit(5)->get();
        $recentPosts = Post::with(['user', 'category'])->latest()->limit(5)->get();
        $recentComments = Comment::with(['user', 'post'])->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentPosts', 'recentComments'));
    }

    public function users()
    {
        $users = User::with('role')->paginate(15);
        $roles = Role::all();

        return view('admin.users', compact('users', 'roles'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        $user->update(['role_id' => $request->role_id]);

        return back()->with('success', 'Kullanıcı rolü güncellendi.');
    }

    public function posts()
    {
        $posts = Post::with(['user', 'category'])
            ->when(request('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->latest()
            ->paginate(15);

        return view('admin.posts', compact('posts'));
    }

    public function approvePost(Post $post)
    {
        $post->update(['status' => 'published']);

        return back()->with('success', 'Yazı onaylandı ve yayınlandı.');
    }

    public function rejectPost(Request $request, Post $post)
    {
        $post->update(['status' => 'draft']);

        return back()->with('success', 'Yazı reddedildi ve taslak durumuna alındı.');
    }
}