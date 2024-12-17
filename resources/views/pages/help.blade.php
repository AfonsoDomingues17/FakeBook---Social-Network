@extends('layouts.app')

@section('content')
    <section id="help-contacts-page">
        <h2>Help/FAQ</h2>
        <div class="accordion accordion-flush" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingAccountManagement">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseAccountManagement" aria-expanded="false" aria-controls="flush-collapseAccountManagement">
                        Account Management
                    </button>
                </h2>
                <div id="flush-collapseAccountManagement" class="accordion-collapse collapse" aria-labelledby="flush-headingAccountManagement">
                    <div class="accordion-body">
                        <strong>How do I create an account?</strong>
                        <p>Click on the <em>Sign Up</em> button on the home page and fill out the registration form with your details.</p>
                        <br>
                        <strong>I forgot my password. How can I reset it?</strong>
                        <p>Use the <em>Forgot Password?</em> link on the login page. Enter your email, and we'll send you a reset link.</p>
                        <br>
                        <strong>How do I edit my account details?</strong>
                        <p>Click on the <em>Edit Profile</em> button on your profile page and edit your account details by filling the form</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingPostsAndComments">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsePostsAndComments" aria-expanded="false" aria-controls="flush-collapsePostsAndComments">
                        Posts and Comments
                    </button>
                </h2>
                <div id="flush-collapsePostsAndComments" class="accordion-collapse collapse" aria-labelledby="flush-headingPostsAndComments">
                    <div class="accordion-body">
                        <strong>How can I react to posts or comments?</strong>
                        <p>Click the <em>Like</em> button below the content you want to react to.</p>
                        <br>
                        <strong>What type of content can I share?</strong>
                        <p>You can share text or multimedia posts, but ensure your content adheres to our community guidelines.</p>
                        <br>
                        <strong>How can I tag someone in a post or comment?</strong>
                        <p>Use the "@" symbol followed by the username of the user you want to tag when creating a post or comment.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingGroups">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseGroups" aria-expanded="false" aria-controls="flush-collapseGroups">
                        Groups
                    </button>
                </h2>
                <div id="flush-collapseGroups" class="accordion-collapse collapse" aria-labelledby="flush-headingGroups">
                    <div class="accordion-body">
                        <strong>How can I join a group?</strong>
                        <p>Search for the group you want to join and click the <em>Request to Join</em> button. The group owner must approve your request before joining their group.</p>
                        <br>
                        <strong>How can I leave a group?</strong>
                        <p>On the group page, click the <em>Leave Group</em> button.</p>
                        <br>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingNotifications">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseNotifications" aria-expanded="false" aria-controls="flush-collapseNotifications">
                        Notifications
                    </button>
                </h2>
                <div id="flush-collapseNotifications" class="accordion-collapse collapse" aria-labelledby="flush-headingNotifications">
                    <div class="accordion-body">
                        <strong>What type of notifications will I receive?</strong>
                        <p>You will receive notifications for likes, comments, follow requests, group requests, messages and tags.</p>
                        <br>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingSupport">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSupport" aria-expanded="false" aria-controls="flush-collapseSupport">
                        Support
                    </button>
                </h2>
                <div id="flush-collapseSupport" class="accordion-collapse collapse" aria-labelledby="flush-headingSupport">
                    <div class="accordion-body">
                        <strong>How can report inappropriate content?</strong>
                        <p>Click the<em>Report</em> button and fill a form with the reason for the report. Our admin team will proceed to review that content.</p>
                        <br>
                        <strong>How can I contact support?</strong>
                        <p>You can either contact through one of the methods below or fill the <em>Contact Form</em> at the end of the page to ask a question to which our team will be more than happy to answer.</p>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <h2>Contact Information</h2>
        <div class="contact-methods">
            <ul>
                <li>Afonso Domingues: <a href="mailto:up202207313@up.pt">up202207313@up.pt</a></li>
                <li>David Gonçalves: <a href="mailto:up202208795@up.pt">up202208795@up.pt</a></li>
                <li>João Lamas: <a href="mailto:up202208948@up.pt">up202208948@up.pt</a></li>
                <li>Tomás Marques: <a href="mailto:up202206667@up.pt">up202206667@up.pt</a></li>
            </ul>
        </div>
        <h2>Contact Form</h2>
        <div ">
            <form action="{{ route('help.form') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                @if (Auth::check() && Auth::user()->isBanned())
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_unban" id="unban_request" value="true">
                    <label class="form-check-label" for="unban_request">I want to request my unban</label>
                </div>
                @endif
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea name="message" id="message" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </section>
@endsection