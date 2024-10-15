@extends('layouts.header_footer')

@section('title', 'Administratora galvenā lapa')

@section('content')
    <h1>Administratora lapa</h1>
    <h2>hello, {{ Auth::user()->login }}</h2>
@endsection