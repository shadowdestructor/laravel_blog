@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', 'Yazılarım')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Yazılarım</h1>
        <a href="{{ route('posts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Yazı
        </a>
    </div>

    @if($posts->count() > 0)
        <div class="row">
            @foreach($posts as $post)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card post-card h-100">
                        @if($post->featured_image)
                            <img src="{{ Storage::url($post->featured_image) }}" 
                                 class="card-img-top" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge" style="background-color: {{ $post->category->color }}">
                                    {{ $post->category->name }}
                                </span>
                                <span class="badge bg-{{ $post->status === 'published' ? 'success' : ($post->status === 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </div>
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text flex-grow-1">{{ $post->excerpt }}</p>
                            <div class="mt-auto">
                                <small class="text-muted d-block mb-2">
                                    <i class="fas fa-eye"></i> {{ $post->views_count }} görüntüleme
                                    <i class="fas fa-calendar ms-2"></i> {{ $post->created_at->format('d.m.Y') }}
                                </small>
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Görüntüle
                                    </a>
                                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-edit"></i> Düzenle
                                    </a>
                                    <form method="POST" action="{{ route('posts.destroy', $post) }}" 
                                          class="d-inline" onsubmit="return confirm('Bu yazıyı silmek istediğinizden emin misiniz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $posts->links() }}
    @else
        <div class="text-center py-5">
            <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
            <h4>Henüz yazınız bulunmamaktadır.</h4>
            <p class="text-muted">İlk yazınızı oluşturmak için aşağıdaki butona tıklayın.</p>
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> İlk Yazımı Oluştur
            </a>
        </div>
    @endif
</div>
@endsection