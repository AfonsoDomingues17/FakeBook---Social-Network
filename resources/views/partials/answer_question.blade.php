<div class="modal fade" id="responseModal-{{ $question->id }}" tabindex="-1" role="dialog" aria-labelledby="responseModalLabel-{{ $question->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="responseModalLabel-{{ $question->id }}">Respond to:<br>{{ $question->name }} ({{ $question->email }})</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('question.response', $question->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <p><strong>Question:</strong><br>{{ $question->message }}</p>
                        <label for="response" class="form-label">Your Response</label>
                        <textarea class="form-control" id="response" name="response" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Response</button>
                </form>
            </div>
        </div>
    </div>
</div>
