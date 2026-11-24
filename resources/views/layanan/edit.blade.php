@extends('layouts.app')

@section('title', 'Edit Layanan')

@section('content')
    <div class="app-content">
        <div class="container-fluid app-container">
            <div class="form-page-heading">
                <a href="{{ route('layanan.index') }}" class="back-link">&larr; Kembali ke Layanan</a>
                <span class="page-eyebrow">KATALOG LAUNDRY</span>
                <h2 class="section-title">Edit layanan</h2>
                <p class="page-subtitle">Perbarui detail layanan {{ $layanan->nama_layanan }}.</p>
            </div>

            <section class="panel-card form-panel">
                <form action="{{ route('layanan.update', $layanan) }}" method="POST" class="app-form" data-loading-form>
                    @csrf
                    @method('PUT')
                    <div class="form-field">
                        <label class="form-label" for="nama_layanan">Nama layanan</label>
                        <input id="nama_layanan" name="nama_layanan" type="text"
                               class="form-control @error('nama_layanan') is-invalid @enderror"
                               value="{{ old('nama_layanan', $layanan->nama_layanan) }}" required>
                        @error('nama_layanan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label class="form-label" for="harga">Harga per kg</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input id="harga" name="harga" type="number" min="0" step="1"
                                       class="form-control @error('harga') is-invalid @enderror"
                                       value="{{ old('harga', $layanan->harga) }}" required>
                                @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-field">
                            <label class="form-label" for="estimasi">Estimasi pengerjaan</label>
                            <input id="estimasi" name="estimasi" type="text"
                                   class="form-control @error('estimasi') is-invalid @enderror"
                                   value="{{ old('estimasi', $layanan->estimasi) }}" required>
                            @error('estimasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary app-button" data-loading-button>Simpan Perubahan</button>
                        <a href="{{ route('layanan.index') }}" class="btn btn-light app-button">Batal</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
