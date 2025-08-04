@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <article class="mb-4">
                <!-- Post Header -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge" style="background-color: {{ $post->category->color }}">
                            {{ $post->category->name }}
                        </span>
                        <div class="text-muted">
                            <small><i class="fas fa-eye"></i> {{ $post->views_count }} görüntüleme</small>
                        </div>
                    </div>
                    
                    <h1 class="mb-3">{{ $post->title }}</h1>
                    
                    <div class="d-flex align-items-center text-muted mb-3">
                        <div class="me-4">
                            <i class="fas fa-user"></i> {{ $post->user->name }}
                        </div>
                        <div class="me-4">
                            <i class="fas fa-calendar"></i> {{ $post->created_at->format('d F Y') }}
                        </div>
                        <div>
                            <i class="fas fa-comments"></i> {{ $post->approvedComments->count() }} yorum
                        </div>
                    </div>

                    @can('update', $post)
                        <div class="mb-3">
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit"></i> Düzenle
                            </a>
                            <form method="POST" action="{{ route('posts.destroy', $post) }}" 
                                  class="d-inline ms-2" onsubmit="return confirm('Bu yazıyı silmek istediğinizden emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-trash"></i> Sil
                                </button>
                            </form>
                        </div>
                    @endcan
                </div>

                <!-- Featured Image -->
                @if($post->featured_image)
                    <div class="mb-4">
                        <img src="{{ Storage::url($post->featured_image) }}" 
                             class="img-fluid rounded" alt="{{ $post->title }}">
                    </div>
                @endif

                <!-- Post Content -->
                <div class="post-content">
                    {!! nl2br(e($post->content)) !!}
                </div>
            </article>

            <!-- Comments Section -->
            <div class="comments-section">
                <h4 class="mb-4">Yorumlar ({{ $post->approvedComments->count() }})</h4>

                @auth
                    <!-- Comment Form -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="POST" action="{{ route('comments.store', $post) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="content" class="form-label">Yorumunuz</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" name="content" rows="4" 
                                              placeholder="Yorumunuzu yazın..." required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-comment"></i> Yorum Yap
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        Yorum yapabilmek için <a href="{{ route('login') }}">giriş yapın</a> veya 
                        <a href="{{ route('register') }}">kayıt olun</a>.
                    </div>
                @endauth

                <!-- Comments List -->
                @if($post->approvedComments->count() > 0)
                    <div class="comments-list">
                        @foreach($post->approvedComments->whereNull('parent_id') as $comment)
                            @include('components.comment', ['comment' => $comment])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-comments fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Henüz yorum yapılmamış. İlk yorumu siz yapın!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Author Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Yazar Hakkında</h5>
                </div>
                <div class="card-body">
                    <h6>{{ $post->user->name }}</h6>
                    @if($post->user->bio)
                        <p class="text-muted">{{ $post->user->bio }}</p>
                    @endif
                    <small class="text-muted">
                        {{ $post->user->posts()->published()->count() }} yazı yayınladı
                    </small>
                </div>
            </div>

            <!-- Related Posts -->
            @php
                $relatedPosts = App\Models\Post::published()
                    ->where('category_id', $post->category_id)
                    ->where('id', '!=', $post->id)
                    ->limit(3)
                    ->get();
            @endphp

            @if($relatedPosts->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-newspaper"></i> İlgili Yazılar</h5>
                    </div>
                    <div class="card-body">
                        @foreach($relatedPosts as $relatedPost)
                            <div class="mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                <h6>
                                    <a href="{{ route('posts.show', $relatedPost) }}" class="text-decoration-none">
                                        {{ $relatedPost->title }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> {{ $relatedPost->created_at->format('d.m.Y') }}
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection