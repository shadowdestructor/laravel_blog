@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', 'Yazı Düzenle')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Yazı Düzenle: {{ $post->title }}</h1>
        <a href="{{ route('posts.show', $post) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Başlık</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $post->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">Kategori seçin</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($post->featured_image)
                            <div class="mb-3">
                                <label class="form-label">Mevcut Görsel</label>
                                <div>
                                    <img src="{{ Storage::url($post->featured_image) }}" 
                                         class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="featured_image" class="form-label">
                                {{ $post->featured_image ? 'Yeni Görsel (Opsiyonel)' : 'Öne Çıkan Görsel' }}
                            </label>
                            <input type="file" class="form-control @error('featured_image') is-invalid @enderror" 
                                   id="featured_image" name="featured_image" accept="image/*">
                            @error('featured_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">İçerik</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="15" required>{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Durum</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Taslak</option>
                                <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Yayınla</option>
                                <option value="pending" {{ old('status', $post->status) === 'pending' ? 'selected' : '' }}>Onay Bekliyor</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('posts.show', $post) }}" class="btn btn-secondary me-md-2">İptal</a>
                            <button type="submit" class="btn btn-primary">Yazı Güncelle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Yazı İstatistikleri</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li><strong>Görüntüleme:</strong> {{ $post->views_count }}</li>
                        <li><strong>Yorum:</strong> {{ $post->comments()->count() }}</li>
                        <li><strong>Oluşturulma:</strong> {{ $post->created_at->format('d.m.Y H:i') }}</li>
                        <li><strong>Güncellenme:</strong> {{ $post->updated_at->format('d.m.Y H:i') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection