@extends('common.layout')

@section('content')

<div class="col-md-7 col-lg-8">

    <div class="py-5 text-center">
        <h2>Analytics Demo</h2>
        <p class="lead">This is an analytics demo sign-in/sign-up page. Use the form below to select a username and
            password. We'll create the user if one does not already exist.</p>
    </div>

    @if ($errors)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{$errors}}
            <button type="button" class="btn btn-close" data-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif

    <form class="needs-validation" method="post" novalidate>

        <div class="row g-3">

            <div class="col-12">
                <label for="username" class="form-label">Username</label>
                <div class="input-group has-validation">
                    <span class="input-group-text">@</span>
                    <input type="text"
                           class="form-control"
                           id="username"
                           name="username"
                           placeholder="Username"
                           value="{{ $username }}"
                           required>
                    <div class="invalid-feedback">
                        Your username is required.
                    </div>
                </div>
            </div>

            <div class="col-12">
                <label for="password" class="form-label">Password</label>
                <input type="password"
                       class="form-control"
                       id="password"
                       name="password"
                       placeholder="********">
                <div class="invalid-feedback">
                    Password is invalid.
                </div>
            </div>

        </div>

        <br/>

        <div class="form-check">
            <input type="checkbox"
                   class="form-check-input"
                   name="agreement"
                   id="agreement">
            <label class="form-check-label"
                   for="agreement">I understand this is just a demo app and my data will be discarded after some time.</label>
        </div>

        <hr class="my-4">
        <button class="w-100 btn btn-primary btn-lg" type="submit">Let's Go!</button>
    </form>

</div>

@endsection
