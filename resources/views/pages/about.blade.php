@extends('layouts.app')

@section('content')
    <section id="about-page">
        <h2>About Us</h2>
        <section id="about-page-content">
            <h3>Welcome to FakeBook!</h3>
            <p>
                FakeBook is a social network designed to connect people from around the world.
                Created by a team of four students as part of a university project, FakeBook aims
                to provide a platform for users to share their stories, interact, and build communities.
            </p>
            <p class="about-page-spacing">On FakeBook, you can:</p>
            <ul>
                <li>Share posts in text or multimedia formats.</li>
                <li>Comment on and react to content.</li>
                <li>Tag others in posts and comments.</li>
                <li>Form or join groups with shared interests.</li>
            </ul>
            <p class="about-page-spacing">
                <strong>Privacy matters to us</strong>—users can choose between public and private profiles,
                ensuring their content is seen only by approved followers. Notifications keep you updated on likes,
                comments, and follow requests, while administrators work to maintain a safe and welcoming environment.
            </p>
            <p>Whether you're here to connect, share, or explore, FakeBook is your space to make it happen.</p>
            <p id="about-page-ending"><strong>Join us and be part of the FakeBook community!</strong></p>
        </section>
    </section>
@endsection