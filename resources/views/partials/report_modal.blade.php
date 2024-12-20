@if($type == 'user')
<div class="modal fade" id="reportUserModal-{{ $id }}" tabindex="-1" aria-labelledby="reportUserModalLabel-{{ $id }}" aria-hidden="true" style="display: none">
@elseif($type == 'post')
<div class="modal fade" id="reportPostModal-{{ $id }}" tabindex="-1" aria-labelledby="reportPostModalLabel-{{ $id }}" aria-hidden="true" style="display: none">
@elseif($type == 'comment')
<div class="modal fade" id="reportCommentModal-{{ $id }}" tabindex="-1" aria-labelledby="reportCommentModalLabel-{{ $id }}" aria-hidden="true" style="display: none">
@endif
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="width: 100%;">
                <h5 class="modal-title" id="reportModalLabel-{{ $id }}">Report {{$type}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: #007bff;"></button>
            </div>
            <div id="ReportModalContent">
                @if ($type == 'user')
                    <form action="{{ route('report.user', $id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div id="description">
                            <label for="content-{{ $id }}">Report motive:</label>
                            <textarea id="content-{{ $id }}" name="content" required></textarea>
                            @if ($errors->has('content'))
                            <span class="error">{{ $errors->first('content') }} <i class="fa-solid fa-circle-exclamation"></i></span>
                            @endif
                        </div>
                        <div id="modal-footer">
                            <button type="submit" id="report_user" >Report user</button>
                        </div>
 
                    </form>
                @elseif ($type == 'post')
                    <form action="{{ route('report.post', $id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div id="description">
                            <label for="content-{{ $id }}">Report motive:</label>
                            <textarea id="content-{{ $id }}" name="content" required></textarea>
                            @if ($errors->has('content'))
                            <span class="error">{{ $errors->first('content') }} <i class="fa-solid fa-circle-exclamation"></i></span>
                            @endif
                        </div>
                        <div id="modal-footer">
                            <button type="submit" id="report_post" >Report post</button>
                        </div>
                    </form>
                @elseif ($type == 'comment')
                    <form action="{{ route('report.comment', $id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div id="description">
                            <label for="content-{{ $id }}">Report motive:</label>
                            <textarea id="content-{{ $id }}" name="content" required></textarea>
                            @if ($errors->has('content'))
                            <span class="error">{{ $errors->first('content') }} <i class="fa-solid fa-circle-exclamation"></i></span>
                            @endif
                        </div>
                        <div id="modal-footer">
                        <button type="submit" id="report_comment" >Report comment</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>