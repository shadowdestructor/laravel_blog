@extends('layouts.app')

@section('title', 'Ana Sayfa')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-primary text-white rounded p-5 text-center">
                <h1 class="display-4 mb-3">Laravel Blog Sistemi</h1>
                <p class="lead">Laravel ile geliştirilmiş modern blog platformu. Yazılarınızı paylaşın, okuyucularınızla etkileşime geçin.</p>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg me-2">Kayıt Ol</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Giriş Yap</a>
                @else
                    @if(auth()->user()->isAuthor())
                        <a href="{{ route('posts.create') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-plus"></i> Yeni Yazı Yaz
                        </a>
                    @endif
                @endguest
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Son Yazılar</h2>
                <a href="{{ route('posts.search') }}" class="btn btn-outline-primary">
                    <i class="fas fa-search"></i> Tüm Yazılar
                </a>
            </div>

            @if($posts->count() > 0)
                <div class="row">
                    @foreach($posts as $post)
                        <div class="col-md-6 mb-4">
                            @include('components.post-card', ['post' => $post])
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                    <h4>Henüz yazı bulunmamaktadır.</h4>
                    <p class="text-muted">İlk yazıyı siz yazabilirsiniz!</p>
                    @auth
                        @if(auth()->user()->isAuthor())
                            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> İlk Yazıyı Yaz
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Search -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-search"></i> Arama</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('posts.search') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" 
                                   placeholder="Yazı ara..." value="{{ request('q') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Categories -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tags"></i> Kategoriler</h5>
                </div>
                <div class="card-body">
                    @if($categories->count() > 0)
                        @foreach($categories as $category)
                            <a href="{{ route('categories.show', $category) }}" 
                               class="d-flex justify-content-between align-items-center text-decoration-none mb-2">
                                <span>
                                    <span class="badge me-2" style="background-color: {{ $category->color }}">
                                        {{ $category->name }}
                                    </span>
                                </span>
                                <span class="badge bg-light text-dark">{{ $category->posts_count }}</span>
                            </a>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">Henüz kategori bulunmamaktadır.</p>
                    @endif
                </div>
            </div>

            <!-- Stats -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> İstatistikler</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary">{{ $posts->total() }}</h4>
                            <small class="text-muted">Toplam Yazı</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $categories->count() }}</h4>
                            <small class="text-muted">Kategori</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection