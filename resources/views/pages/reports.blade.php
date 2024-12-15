@extends('layouts.app')

@section('content')
    <section id="reports-page">
        <h2 class="text-center">Reports</h2>
        <section id="reports-page-content">
            @if(!Auth::user()->isAdmin())
            <h3>Reported Content</h3>
            <p>
                FakeBook is committed to maintaining a safe and welcoming environment for all users.
                If you encounter content that violates our community guidelines, please report it to our team.
                We review all reports and take appropriate action to ensure FakeBook remains a positive space for everyone.
            </p>
            <p class="reports-page-spacing">Reported content may include:</p>
            <ul>
                <li>Posts containing hate speech or violence.</li>
                <li>Images or videos that are inappropriate or offensive.</li>
                <li>Accounts that impersonate others or engage in spam.</li>
                <li>Comments that are abusive or harassing.</li>
            </ul>
            <p class="reports-page-spacing">
                <strong>How to report content:</strong> Click the "Report" button on the post, user, or comment.
                You will be prompted to provide a reason for the report, which will be reviewed by our team.
                We appreciate your help in keeping FakeBook a safe and respectful community.
            </p>
            <p>If you have any questions or concerns about reporting content, please contact our support team.</p>
            <p id="reports-page-ending"><strong>Thank you for helping us maintain a positive environment on FakeBook!</strong></p>
            @else
            <div class="accordion" id="reportsAccordion">
                @if($userReports->isNotEmpty())
                    <h3>Users needing Review</h3>
                    @foreach($userReports as $group)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingUserReport-{{ $group->first()->target_user_id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUserReport-{{ $group->first()->target_user_id }}" aria-expanded="false" aria-controls="collapseUserReport-{{ $group->first()->target_user_id }}">
                                    Reported User: {{ $group->first()->target_user_id }}
                                </button>
                            </h2>
                            <div id="collapseUserReport-{{ $group->first()->target_user_id }}" class="accordion-collapse collapse" aria-labelledby="headingUserReport-{{ $group->first()->target_user_id }}" data-bs-parent="#reportsAccordion">
                                <div class="accordion-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Content</th>
                                                <th>Created At</th>
                                                <th>Solved At</th>
                                                <th>ID</th>
                                                <th>Author ID</th>
                                            </tr>
                                        </thead>
                                        <tbody id="details-user-{{ $group->first()->target_user_id }}">
                                            @foreach($group as $report)
                                                <tr>
                                                    <td>{{ $report->content }}</td>
                                                    <td>{{ $report->createdat }}</td>
                                                    <td>{{ $report->solvedat ?? 'N/A' }}</td>
                                                    <td>{{ $report->id }}</td>
                                                    <td>{{ $report->author_id }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button class="btn btn-primary mt-3" onclick="window.location.href='{{ route('profile', ['user_id' => $group->first()->target_user_id]) }}'">Go to User page</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                @if($postReports->isNotEmpty())
                    <h3>Posts needing Review</h3>
                    @foreach($postReports as $group)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingPostReport-{{ $group->first()->post_id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePostReport-{{ $group->first()->post_id }}" aria-expanded="false" aria-controls="collapsePostReport-{{ $group->first()->post_id }}">
                                    Reported Post: {{ $group->first()->post_id }}
                                </button>
                            </h2>
                            <div id="collapsePostReport-{{ $group->first()->post_id }}" class="accordion-collapse collapse" aria-labelledby="headingPostReport-{{ $group->first()->post_id }}" data-bs-parent="#reportsAccordion">
                                <div class="accordion-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Content</th>
                                                <th>Created At</th>
                                                <th>Solved At</th>
                                                <th>ID</th>
                                                <th>Author ID</th>
                                            </tr>
                                        </thead>
                                        <tbody id="details-post-{{ $group->first()->post_id }}">
                                            @foreach($group as $report)
                                                <tr>
                                                    <td>{{ $report->content }}</td>
                                                    <td>{{ $report->createdat }}</td>
                                                    <td>{{ $report->solvedat ?? 'N/A' }}</td>
                                                    <td>{{ $report->id }}</td>
                                                    <td>{{ $report->author_id }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button class="btn btn-primary mt-3" onclick="window.location.href='{{ route('profile', ['user_id' => $group->first()->post->owner_id]) }}'">Go to User page</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                @if($commentReports->isNotEmpty())
                    <h3>Reported Comments</h3>
                    @foreach($commentReports as $group)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingCommentReport-{{ $group->first()->comment_id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCommentReport-{{ $group->first()->comment_id }}" aria-expanded="false" aria-controls="collapseCommentReport-{{ $group->first()->comment_id }}">
                                    Reported Comment: {{ $group->first()->comment_id }}
                                </button>
                            </h2>
                            <div id="collapseCommentReport-{{ $group->first()->comment_id }}" class="accordion-collapse collapse" aria-labelledby="headingCommentReport-{{ $group->first()->comment_id }}" data-bs-parent="#reportsAccordion">
                                <div class="accordion-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Content</th>
                                                <th>Created At</th>
                                                <th>Solved At</th>
                                                <th>ID</th>
                                                <th>Author ID</th>
                                            </tr>
                                        </thead>
                                        <tbody id="details-comment-{{ $group->first()->comment_id }}">
                                            @foreach($group as $report)
                                                <tr>
                                                    <td>{{ $report->content }}</td>
                                                    <td>{{ $report->createdat }}</td>
                                                    <td>{{ $report->solvedat ?? 'N/A' }}</td>
                                                    <td>{{ $report->id }}</td>
                                                    <td>{{ $report->author_id }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button class="btn btn-primary mt-3" onclick="window.location.href='{{ route('profile', ['user_id' => $group->first()->comment->author_id]) }}'">Go to User page</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            @endif
        </section>
    </section>
@endsection