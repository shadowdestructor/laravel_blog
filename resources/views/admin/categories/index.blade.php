@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.admin')

@section('title', 'Kategoriler')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kategoriler</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Yeni Kategori
    </a>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Renk</th>
                <th>Ad</th>
                <th>Slug</th>
                <th>Açıklama</th>
                <th>Yazı Sayısı</th>
                <th>Oluşturulma</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>
                        <span class="badge" style="background-color: {{ $category->color }}; color: white;">
                            {{ $category->color }}
                        </span>
                    </td>
                    <td>{{ $category->name }}</td>
                    <td><code>{{ $category->slug }}</code></td>
                    <td>{{ Str::limit($category->description, 50) }}</td>
                    <td>
                        <span class="badge bg-info">{{ $category->posts_count }}</span>
                    </td>
                    <td>{{ $category->created_at->format('d.m.Y') }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.categories.edit', $category) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" 
                                  class="d-inline" onsubmit="return confirm('Bu kategoriyi silmek istediğinizden emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Henüz kategori bulunmamaktadır.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $categories->links() }}
@endsection