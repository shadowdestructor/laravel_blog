@extends('layouts.app')

@section('title', 'Arama Sonuçları')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <div class="mb-4">
                <h1>Arama Sonuçları</h1>
                @if($query)
                    <p class="text-muted">"{{ $query }}" için {{ $posts->total() }} sonuç bulundu</p>
                @endif
            </div>

            <!-- Search Form -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('posts.search') }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="q" class="form-label">Arama Terimi</label>
                                    <input type="text" class="form-control" id="q" name="q" 
                                           value="{{ $query }}" placeholder="Yazı ara...">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Kategori</label>
                                    <select class="form-select" id="category" name="category">
                                        <option value="">Tüm Kategoriler</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ $categoryId == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary d-block w-100">
                                        <i class="fas fa-search"></i> Ara
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Search Results -->
            @if($posts->count() > 0)
                <div class="row">
                    @foreach($posts as $post)
                        <div class="col-md-6 mb-4">
                            @include('components.post-card', ['post' => $post])
                        </div>
                    @endforeach
                </div>

                {{ $posts->appends(request()->query())->links() }}
            @else
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>Arama sonucu bulunamadı</h4>
                    <p class="text-muted">Farklı anahtar kelimeler deneyebilir veya kategori filtresini değiştirebilirsiniz.</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Popular Categories -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tags"></i> Popüler Kategoriler</h5>
                </div>
                <div class="card-body">
                    @foreach($categories->sortByDesc('posts_count')->take(5) as $category)
                        <a href="{{ route('posts.search', ['category' => $category->id]) }}" 
                           class="d-flex justify-content-between align-items-center text-decoration-none mb-2">
                            <span>
                                <span class="badge me-2" style="background-color: {{ $category->color }}">
                                    {{ $category->name }}
                                </span>
                            </span>
                            <span class="badge bg-light text-dark">{{ $category->posts_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Search Tips -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Arama İpuçları</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">• Birden fazla kelime kullanarak arama yapabilirsiniz</li>
                        <li class="mb-2">• Kategori filtresi ile sonuçları daraltabilirsiniz</li>
                        <li class="mb-2">• Arama hem başlıkta hem içerikte yapılır</li>
                        <li>• Büyük/küçük harf duyarlılığı yoktur</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection