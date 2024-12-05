<div class="individual-question">
    <strong>{{ $question->name }}</strong> ({{ $question->email }}):<br>
    {{ $question->message }}
    <button type="button" class="btn btn-primary open-modal" data-bs-toggle="modal" data-bs-target="#responseModal-{{ $question->id }}">
        Respond
    </button>
</div>