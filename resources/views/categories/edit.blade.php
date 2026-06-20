@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card mx-auto shadow-sm" style="max-width: 600px; border: 2px solid #4B5320;">
        <div class="card-header text-white fw-bold" style="background-color: #4B5320;">Edit Kategori</div>
        <div class="card-body bg-light">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="fw-bold">Nama Kategori</label>
                    <input type="text" name="name" class="form-control border-dark" value="{{ old('name', $category->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Slug (opsional)</label>
                    <input type="text" name="slug" class="form-control border-dark" value="{{ old('slug', $category->slug) }}">
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold">UPDATE KATEGORI</button>
            </form>
        </div>
    </div>
</div>
@endsection
