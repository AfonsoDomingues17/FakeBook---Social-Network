@extends('layouts.app')

@section('content')
    <section id="not_found" style="display: flex; flex-direction: column">
        <h1>This page no longer exists.</h1>
        @if ($errors->has('token'))
            <div class="alert alert-danger" style="text-align: center">
                {{ $errors->first('token') }}
            </div>
        @endif
    </section>
@endsection