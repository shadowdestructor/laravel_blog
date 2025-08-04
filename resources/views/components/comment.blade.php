<div class="comment mb-4" id="comment-{{ $comment->id }}">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="d-flex align-items-center">
                    <strong>{{ $comment->user->name }}</strong>
                    <small class="text-muted ms-2">
                        <i class="fas fa-clock"></i> {{ $comment->created_at->diffForHumans() }}
                    </small>
                </div>
                
                @can('update', $comment)
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <button class="dropdown-item" onclick="editComment({{ $comment->id }})">
                                    <i class="fas fa-edit"></i> Düzenle
                                </button>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('comments.destroy', $comment) }}" 
                                      class="d-inline" onsubmit="return confirm('Bu yorumu silmek istediğinizden emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-trash"></i> Sil
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endcan
            </div>
            
            <div class="comment-content" id="comment-content-{{ $comment->id }}">
                <p class="mb-2">{{ $comment->content }}</p>
            </div>
            
            <!-- Edit Form (Hidden by default) -->
            <div class="comment-edit-form d-none" id="comment-edit-{{ $comment->id }}">
                <form method="POST" action="{{ route('comments.update', $comment) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-2">
                        <textarea class="form-control" name="content" rows="3" required>{{ $comment->content }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">Güncelle</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="cancelEdit({{ $comment->id }})">İptal</button>
                    </div>
                </form>
            </div>
            
            @auth
                <div class="mt-2">
                    <button class="btn btn-link btn-sm p-0" onclick="showReplyForm({{ $comment->id }})">
                        <i class="fas fa-reply"></i> Yanıtla
                    </button>
                </div>
                
                <!-- Reply Form (Hidden by default) -->
                <div class="reply-form mt-3 d-none" id="reply-form-{{ $comment->id }}">
                    <form method="POST" action="{{ route('comments.store', $comment->post) }}">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <div class="mb-2">
                            <textarea class="form-control" name="content" rows="3" 
                                      placeholder="Yanıtınızı yazın..." required></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">Yanıtla</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="hideReplyForm({{ $comment->id }})">İptal</button>
                        </div>
                    </form>
                </div>
            @endauth
        </div>
    </div>
    
    <!-- Replies -->
    @if($comment->replies->count() > 0)
        <div class="comment-replies mt-3">
            @foreach($comment->replies as $reply)
                @include('components.comment', ['comment' => $reply])
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script>
function editComment(commentId) {
    document.getElementById('comment-content-' + commentId).classList.add('d-none');
    document.getElementById('comment-edit-' + commentId).classList.remove('d-none');
}

function cancelEdit(commentId) {
    document.getElementById('comment-content-' + commentId).classList.remove('d-none');
    document.getElementById('comment-edit-' + commentId).classList.add('d-none');
}

function showReplyForm(commentId) {
    document.getElementById('reply-form-' + commentId).classList.remove('d-none');
}

function hideReplyForm(commentId) {
    document.getElementById('reply-form-' + commentId).classList.add('d-none');
}
</script>
@endpush