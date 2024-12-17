@extends('layouts.app')

@section('content')
    <section id="reports-page">
        <h2 class="text-center">Reports</h2>
        <section id="reports-page-content">
            @if(!Auth::check() || (Auth::check() && !Auth::user()->isAdmin()))
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
            @if($userReports->isNotEmpty())
                <h3>Reported Users</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Content</th>
                            <th>Created At</th>
                            <th>Solved At</th>
                            <th>Target User ID</th>
                            <th>Author ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userReports as $group)
                            @foreach($group as $report)
                                <tr class="{{ count($group) >= 5 ? 'table-warning' : '' }}">
                                    <td>{{ $report->content }}</td>
                                    <td>{{ \Carbon\Carbon::parse($report->createdAt)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $report->solvedAt ? \Carbon\Carbon::parse($report->solvedAt)->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td>{{ $report->target_user_id ?? 'N/A' }}</td>
                                    <td>{{ $report->author_id }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if($postReports->isNotEmpty())
                <h3>Reported Posts</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Content</th>
                            <th>Created At</th>
                            <th>Solved At</th>
                            <th>Post ID</th>
                            <th>Author ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($postReports as $group)
                            @foreach($group as $report)
                                <tr class="{{ count($group) >= 5 ? 'table-warning' : '' }}">
                                    <td>{{ $report->content }}</td>
                                    <td>{{ \Carbon\Carbon::parse($report->createdAt)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $report->solvedAt ? \Carbon\Carbon::parse($report->solvedAt)->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td>{{ $report->post_id ?? 'N/A' }}</td>
                                    <td>{{ $report->author_id }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if($commentReports->isNotEmpty())
                <h3>Reported Comments</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Content</th>
                            <th>Created At</th>
                            <th>Solved At</th>
                            <th>Comment ID</th>
                            <th>Author ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($commentReports as $group)
                            @foreach($group as $report)
                                <tr class="{{ count($group) >= 5 ? 'table-warning' : '' }}">
                                    <td>{{ $report->content }}</td>
                                    <td>{{ \Carbon\Carbon::parse($report->createdAt)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $report->solvedAt ? \Carbon\Carbon::parse($report->solvedAt)->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td>{{ $report->comment_id ?? 'N/A' }}</td>
                                    <td>{{ $report->author_id }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @endif
            @endif
        </section>
    </section>
@endsection