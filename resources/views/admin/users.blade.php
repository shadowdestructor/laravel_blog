@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
@endphp

@extends('layouts.admin')

@section('title', 'Kullanıcı Yönetimi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kullanıcı Yönetimi</h1>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Kullanıcı</th>
                <th>E-posta</th>
                <th>Rol</th>
                <th>Yazı Sayısı</th>
                <th>Kayıt Tarihi</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" 
                                     class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                            @else
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-0">{{ $user->name }}</h6>
                                @if($user->bio)
                                    <small class="text-muted">{{ Str::limit($user->bio, 50) }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.role', $user) }}" class="d-inline">
                            @csrf
                            @method('PUT')
                            <select name="role_id" class="form-select form-select-sm" 
                                    onchange="this.form.submit()" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" 
                                            {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $user->posts()->count() }}</span>
                    </td>
                    <td>{{ $user->created_at->format('d.m.Y') }}</td>
                    <td>
                        @if($user->id !== auth()->id())
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-primary" 
                                        onclick="viewUser({{ $user->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" 
                                        onclick="confirmDelete({{ $user->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        @else
                            <span class="badge bg-secondary">Siz</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $users->links() }}

@push('scripts')
<script>
function viewUser(userId) {
    // User detail modal or redirect
    alert('Kullanıcı detay sayfası yakında eklenecek.');
}

function confirmDelete(userId) {
    if (confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')) {
        // Delete user logic
        alert('Kullanıcı silme işlevi yakında eklenecek.');
    }
}
</script>
@endpush
@endsection