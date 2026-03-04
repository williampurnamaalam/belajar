@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2>Selamat Datang, {{ auth()->user()->name }} 👋</h2>
@endsection