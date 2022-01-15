@extends('user.layout')

@section('content')

<h1>Hello, {{ $name }}</h1>

<form method="POST" action="/user/record">
    <label for="message">Record your message:</label>
    <input type="text" name="message">
    <input type="submit" value="Submit">
</form>

@endsection
