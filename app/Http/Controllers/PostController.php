<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
class PostController extends Controller
{
    public function index()
    {
        $posts = auth()->user()->User::posts()
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:100',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:draft,published,pending'
        ]);

        $featuredImage = null;
        if ($request->hasFile('featured_image')) {
            $featuredImage = $request->file('featured_image')->store('posts', 'public');
        }

        $post = Post::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->User::content,
            'excerpt' => Str::limit(strip_tags($request->User::content), 200),
            'featured_image' => $featuredImage,
            'status' => $request->status,
            'user_id' => auth()->id(),
            'category_id' => $request->category_id
        ]);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Yazı başarıyla oluşturuldu.');
    }

    public function show(Post $post)
    {
        // Increment view count
        $post->incrementViews();

        // Load relationships
        $post->load(['user', 'category', 'approvedComments.user', 'approvedComments.replies.user']);

        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:100',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:draft,published,pending'
        ]);

        $featuredImage = $post->featured_image;
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($featuredImage) {
                Storage::disk('public')->delete($featuredImage);
            }
            $featuredImage = $request->file('featured_image')->store('posts', 'public');
        }

        $post->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'excerpt' => Str::limit(strip_tags($request->content), 200),
            'featured_image' => $featuredImage,
            'status' => $request->status,
            'category_id' => $request->category_id
        ]);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Yazı başarıyla güncellendi.');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        // Delete featured image
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Yazı başarıyla silindi.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $categoryId = $request->get('category');

        $posts = Post::published()
            ->with(['user', 'category'])
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->latest()
            ->paginate(12);

        $categories = Category::all();

        return view('posts.search', compact('posts', 'categories', 'query', 'categoryId'));
    }
}