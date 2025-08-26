@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h4><i class="bi bi-speedometer2"></i> Selamat datang, {{ Auth::user()->name }}!</h4>
            <p>Sok manga cai-cai heula</p>
        </div>
    </div>
@endsection
