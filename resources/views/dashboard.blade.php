@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h4><i class="bi bi-speedometer2"></i> Wilujeng sumping, {{ Auth::user()->name }}!</h4>
            @php
                $user = Auth::user();
                $divisiName = null;

                if ($user->subDivisis->isNotEmpty()) {
                    $divisiName = $user->subDivisis->first()->divisi->nama;
                } elseif ($user->divisi) {
                    $divisiName = $user->divisi->nama;
                }
            @endphp

            <p>Anda login sebagai {{ $divisiName ?? 'Belum ada divisi' }}, sok manga cai-cai heula</p>
        </div>
    </div>
@endsection
