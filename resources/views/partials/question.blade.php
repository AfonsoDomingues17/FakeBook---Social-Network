<div class="individual-question">
    <strong>{{ $question->name }}</strong> ({{ $question->email }}):<br>
    {{ $question->message }}
    <button type="button" class="btn btn-primary open-modal" data-bs-toggle="modal" data-bs-target="#responseModal-{{ $question->id }}">
        Respond
    </button>
    @if ($question->is_unban)
    <form action="{{ route('admin.unban.request', ['id' => $question->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to unban this account?');">
        @csrf
        <button type="submit">Unban account</button>
    </form>
    @endif
</div>