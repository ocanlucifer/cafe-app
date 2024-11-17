@extends('layouts.app')

@section('content')
    <h1>Dashboard</h1>
    <div class="container mt-5">
        <h3>Selamat Datang, {{ Auth::user()->name }}</h3>
        {{-- <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form> --}}
    </div>
@endsection
