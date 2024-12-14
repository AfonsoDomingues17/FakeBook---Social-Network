@if($type == 'user')
<div class="modal fade" id="reportUserModal-{{ $id }}" tabindex="-1" aria-labelledby="reportUserModalLabel-{{ $id }}" aria-hidden="true" style="display: none">
@elseif($type == 'post')
<div class="modal fade" id="reportPostModal-{{ $id }}" tabindex="-1" aria-labelledby="reportPostModalLabel-{{ $id }}" aria-hidden="true" style="display: none">
@elseif($type == 'comment')
<div class="modal fade" id="reportCommentModal-{{ $id }}" tabindex="-1" aria-labelledby="reportCommentModalLabel-{{ $id }}" aria-hidden="true" style="display: none">
@endif
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel-{{ $id }}">Report {{$type}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($type == 'user')
                    <form action="{{ route('report.user', $id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="content">Report motive:</label>
                            <textarea id="content" name="content" required></textarea>
                            @error('content')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" id="report_user" class="btn btn-danger">Report user</button>
                    </form>
                @elseif ($type == 'post')
                    <form action="{{ route('report.post', $id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="content">Report motive:</label>
                            <textarea id="content" name="content" required></textarea>
                            @error('content')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" id="report_post" class="btn btn-danger">Report post</button>
                    </form>
                @elseif ($type == 'comment')
                    <form action="{{ route('report.comment', $id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="content">Report motive:</label>
                            <textarea id="content" name="content" required></textarea>
                            @error('content')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" id="report_comment" class="btn btn-danger">Report comment</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>