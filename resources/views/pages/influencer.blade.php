@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $user->username }}'s Influencer Dashboard</h1>

    <div class="btn-group" role="group" aria-label="View Switcher">
        <button type="button" class="btn btn-primary" onclick="showView('followers')">Followers</button>
        <button type="button" class="btn btn-secondary" onclick="showView('posts')">Posts</button>
    </div>

    <div id="followers-view" class="view">
        <div class="chart-container" data-chart-type="followersByCountry" data-chart-data="{{ json_encode($followersByCountry) }}">
            <h2>Followers by Country</h2>
            <button type="button" id="orderFollowersByCountry" class="btn btn-info">Toggle Order ↓</button>
            <input type="text" id="countrySearch" placeholder="Search by country">
            <select id="followersByCountryRange">
                <option value="all">All</option>
                <option value="5">Top 5</option>
                <option value="10">Top 10</option>
            </select>
            <div style="width: 75%">
                <x-chartjs-component :chart="$followersByCountryChart" />
            </div>
        </div>

        <div class="chart-container" data-chart-type="followersByAge" data-chart-data="{{ json_encode($followersByAge) }}">
            <h2>Followers by Age</h2>
            <button type="button" id="orderFollowersByAge" class="btn btn-info">Toggle Order ↓</button>
            <input type="number" id="ageSearch" placeholder="Search by age">
            <select id="followersByAgeRange">
                <option value="all">All</option>
                <option value="5">Top 5</option>
                <option value="10">Top 10</option>
            </select>
            <div style="width: 75%">
                <x-chartjs-component :chart="$followersByAgeChart" />
            </div>
        </div>

        <div class="chart-container" data-chart-type="followersByGender" data-chart-data="{{ json_encode($followersByGender) }}">
            <h2>Followers by Gender</h2>
            <div style="width: 75%">
                <x-chartjs-component :chart="$followersByGenderChart" />
            </div>
        </div>
    </div>

    <div id="posts-view" class="view" style="display: none;">
        <div class="chart-container" data-chart-type="postLikes" data-chart-data="{{ json_encode($postLikes) }}">
            <h2>Post Likes</h2>
            <div style="width: 75%">
                <x-chartjs-component :chart="$postLikesChart" />
            </div>
        </div>

        <div class="chart-container" data-chart-type="postComments" data-chart-data="{{ json_encode($postComments) }}">
            <h2>Post Comments</h2>
            <div style="width: 75%">
                <x-chartjs-component :chart="$postCommentsChart" />
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/chartViewSwitcher.js') }}" defer></script>
<script src="{{ asset('js/dashboard.js') }}" defer></script>
@endsection