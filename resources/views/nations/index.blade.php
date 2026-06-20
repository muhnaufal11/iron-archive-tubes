@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: #4B5320; font-family: 'Courier New', monospace;">
            🌍 MANAJEMEN NEGARA
        </h2>
        <a href="{{ route('nations.create') }}" class="btn text-white fw-bold" style="background-color: #4B5320;">
            + TAMBAH NEGARA
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm" style="border: 2px solid #3F3B2E;">
        <div class="card-header text-white fw-bold" style="background-color: #3F3B2E;">Daftar Negara</div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead style="background-color: #DAD3C1;">
                    <tr>
                        <th class="ps-3">Nama Negara</th>
                        <th>Flag</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nations as $nation)
                    <tr>
                        <td class="ps-3 align-middle fw-bold">{{ $nation->name }}</td>
                        <td class="align-middle text-muted">{{ $nation->flag }}</td>
                        <td class="text-end pe-3 align-middle">
                            <a href="{{ route('nations.edit', $nation->id) }}" class="btn btn-warning btn-sm">✏️</a>
                            <form action="{{ route('nations.destroy', $nation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus negara ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
