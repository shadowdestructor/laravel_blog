@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Hoş geldiniz, {{ auth()->user()->name }}!</h1>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary">{{ $stats['posts'] }}</h3>
                    <small class="text-muted">Toplam Yazı</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">{{ $stats['published_posts'] }}</h3>
                    <small class="text-muted">Yayınlanan</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-warning">{{ $stats['draft_posts'] }}</h3>
                    <small class="text-muted">Taslak</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info">{{ $stats['comments'] }}</h3>
                    <small class="text-muted">Yorumlarım</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-secondary">{{ $stats['total_views'] }}</h3>
                    <small class="text-muted">Toplam Görüntüleme</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i><br>Yeni Yazı
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Posts -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-newspaper"></i> Son Yazılarım</h5>
                    <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-primary">
                        Tümünü Gör
                    </a>
                </div>
                <div class="card-body">
                    @if($recentPosts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Başlık</th>
                                        <th>Kategori</th>
                                        <th>Durum</th>
                                        <th>Görüntüleme</th>
                                        <th>Tarih</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPosts as $post)
                                        <tr>
                                            <td>
                                                <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                                                    {{ Str::limit($post->title, 40) }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge" style="background-color: {{ $post->category->color }}">
                                                    {{ $post->category->name }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $post->status === 'published' ? 'success' : ($post->status === 'pending' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($post->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $post->views_count }}</td>
                                            <td>{{ $post->created_at->format('d.m.Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('posts.edit', $post) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('posts.show', $post) }}" 
                                                       class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-newspaper fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Henüz yazınız bulunmamaktadır.</p>
                            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> İlk Yazınızı Oluşturun
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Comments & Profile -->
        <div class="col-lg-4">
            <!-- Profile Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Profil</h5>
                </div>
                <div class="card-body text-center">
                    @if(auth()->user()->avatar)
                        <img src="{{ Storage::url(auth()->user()->avatar) }}" 
                             class="rounded-circle mb-3" width="80" height="80" style="object-fit: cover;">
                    @else
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x text-white"></i>
                        </div>
                    @endif
                    <h6>{{ auth()->user()->name }}</h6>
                    <p class="text-muted small">{{ auth()->user()->role->name }}</p>
                    @if(auth()->user()->bio)
                        <p class="small">{{ auth()->user()->bio }}</p>
                    @endif
                    <a href="{{ route('profile') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit"></i> Profili Düzenle
                    </a>
                </div>
            </div>

            <!-- Recent Comments -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-comments"></i> Son Yorumlarım</h5>
                </div>
                <div class="card-body">
                    @if($recentComments->count() > 0)
                        @foreach($recentComments as $comment)
                            <div class="mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                <p class="small mb-1">{{ Str::limit($comment->content, 80) }}</p>
                                <small class="text-muted">
                                    <a href="{{ route('posts.show', $comment->post) }}" class="text-decoration-none">
                                        {{ Str::limit($comment->post->title, 30) }}
                                    </a>
                                    • {{ $comment->created_at->diffForHumans() }}
                                </small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">Henüz yorum yapmadınız.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection