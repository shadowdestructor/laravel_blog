@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.admin')

@section('title', 'Yazı Yönetimi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Yazı Yönetimi</h1>
</div>

<!-- Filter Tabs -->
<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ !request('status') ? 'active' : '' }}" 
           href="{{ route('admin.posts') }}">
            Tümü
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') === 'published' ? 'active' : '' }}" 
           href="{{ route('admin.posts', ['status' => 'published']) }}">
            Yayınlanan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') === 'pending' ? 'active' : '' }}" 
           href="{{ route('admin.posts', ['status' => 'pending']) }}">
            Onay Bekleyen
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') === 'draft' ? 'active' : '' }}" 
           href="{{ route('admin.posts', ['status' => 'draft']) }}">
            Taslak
        </a>
    </li>
</ul>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Başlık</th>
                <th>Yazar</th>
                <th>Kategori</th>
                <th>Durum</th>
                <th>Görüntüleme</th>
                <th>Tarih</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
                <tr>
                    <td>
                        <div>
                            <h6 class="mb-0">
                                <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                                    {{ Str::limit($post->title, 50) }}
                                </a>
                            </h6>
                            @if($post->featured_image)
                                <small class="text-muted">
                                    <i class="fas fa-image"></i> Görselli
                                </small>
                            @endif
                        </div>
                    </td>
                    <td>{{ $post->user->name }}</td>
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
                            <a href="{{ route('posts.show', $post) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            @if($post->status === 'pending')
                                <form method="POST" action="{{ route('admin.posts.approve', $post) }}" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-success" 
                                            title="Onayla">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('admin.posts.reject', $post) }}" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            title="Reddet">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('posts.edit', $post) }}" 
                               class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Yazı bulunamadı.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $posts->appends(request()->query())->links() }}
@endsection