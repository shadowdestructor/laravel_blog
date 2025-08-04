@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
@endphp

@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                <h3>{{ $stats['total_users'] }}</h3>
                <small class="text-muted">Toplam Kullanıcı</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-newspaper fa-2x text-success mb-2"></i>
                <h3>{{ $stats['total_posts'] }}</h3>
                <small class="text-muted">Toplam Yazı</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                <h3>{{ $stats['pending_posts'] }}</h3>
                <small class="text-muted">Onay Bekleyen</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-comments fa-2x text-info mb-2"></i>
                <h3>{{ $stats['total_comments'] }}</h3>
                <small class="text-muted">Toplam Yorum</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Users -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-users"></i> Son Kullanıcılar</h5>
                <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">Tümü</a>
            </div>
            <div class="card-body">
                @foreach($recentUsers as $user)
                    <div class="d-flex align-items-center mb-3">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" 
                                 class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                        @else
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-0">{{ $user->name }}</h6>
                            <small class="text-muted">{{ $user->role->name }} • {{ $user->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Posts -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-newspaper"></i> Son Yazılar</h5>
                <a href="{{ route('admin.posts') }}" class="btn btn-sm btn-outline-primary">Tümü</a>
            </div>
            <div class="card-body">
                @foreach($recentPosts as $post)
                    <div class="mb-3">
                        <h6 class="mb-1">
                            <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                                {{ Str::limit($post->title, 40) }}
                            </a>
                        </h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">{{ $post->user->name }}</small>
                            <span class="badge bg-{{ $post->status === 'published' ? 'success' : ($post->status === 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Comments -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-comments"></i> Son Yorumlar</h5>
            </div>
            <div class="card-body">
                @foreach($recentComments as $comment)
                    <div class="mb-3">
                        <p class="mb-1 small">{{ Str::limit($comment->content, 60) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">{{ $comment->user->name }}</small>
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection