<div class="modal fade" id="banUserModal" tabindex="-1" aria-labelledby="banUserModalLabel" aria-hidden="true" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="banUserModalLabel">Ban <?=$user->name?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.banlist.add', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="reason">Ban reason:</label>
                        <textarea id="reason" name="reason" required></textarea>
                        @error('reason')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" id="ban_user" class="btn btn-danger">Ban user</button>
                </form>
            </div>
        </div>
    </div>
</div>
