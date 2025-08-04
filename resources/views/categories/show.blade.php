@extends('layouts.app')

@section('title', $category->name . ' Kategorisi')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex align-items-center mb-4">
                <span class="badge me-3" style="background-color: {{ $category->color }}; color: white; font-size: 1.2rem;">
                    {{ $category->name }}
                </span>
                <div>
                    <h1 class="h3 mb-0">{{ $category->name }} Kategorisi</h1>
                    @if($category->description)
                        <p class="text-muted mb-0">{{ $category->description }}</p>
                    @endif
                </div>
            </div>

            @if($posts->count() > 0)
                <div class="row">
                    @foreach($posts as $post)
                        <div class="col-md-6 col-lg-4 mb-4">
                            @include('components.post-card', ['post' => $post])
                        </div>
                    @endforeach
                </div>

                {{ $posts->links() }}
            @else
                <div class="text-center py-5">
                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                    <h4>Bu kategoride henüz yazı bulunmamaktadır.</h4>
                    <p class="text-muted">İlk yazıyı siz yazabilirsiniz!</p>
                    @auth
                        @if(auth()->user()->isAuthor())
                            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Yeni Yazı Yaz
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </div>
</div>
@endsection