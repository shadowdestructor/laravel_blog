@php
use Illuminate\Support\Facades\Storage;
@endphp

<div class="card post-card h-100">
    @if($post->featured_image)
        <img src="{{ Storage::url($post->featured_image) }}" 
             class="card-img-top" style="height: 200px; object-fit: cover;">
    @endif
    <div class="card-body d-flex flex-column">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <a href="{{ route('categories.show', $post->category) }}" class="text-decoration-none">
                <span class="badge" style="background-color: {{ $post->category->color }}">
                    {{ $post->category->name }}
                </span>
            </a>
            <small class="text-muted">
                <i class="fas fa-eye"></i> {{ $post->views_count }}
            </small>
        </div>
        
        <h5 class="card-title">
            <a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-dark">
                {{ $post->title }}
            </a>
        </h5>
        
        <p class="card-text flex-grow-1">{{ $post->excerpt }}</p>
        
        <div class="mt-auto">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="fas fa-user"></i> {{ $post->user->name }}
                </small>
                <small class="text-muted">
                    <i class="fas fa-calendar"></i> {{ $post->created_at->format('d.m.Y') }}
                </small>
            </div>
            <div class="mt-2">
                <a href="{{ route('posts.show', $post) }}" class="btn btn-primary btn-sm">
                    Devamını Oku <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>