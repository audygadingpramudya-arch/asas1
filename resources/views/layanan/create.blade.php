@extends('layouts.app')

@section('title', 'Tambah Layanan')

@section('content')
    <div class="app-content">
        <div class="container-fluid app-container">
            <div class="form-page-heading">
                <a href="{{ route('layanan.index') }}" class="back-link">&larr; Kembali ke Layanan</a>
                <span class="page-eyebrow">KATALOG LAUNDRY</span>
                <h2 class="section-title">Tambah layanan</h2>
                <p class="page-subtitle">Isi informasi harga dan estimasi pengerjaan layanan baru.</p>
            </div>

            <section class="panel-card form-panel">
                <form action="{{ route('layanan.store') }}" method="POST" class="app-form" data-loading-form>
                    @csrf
                    <div class="form-field">
                        <label class="form-label" for="nama_layanan">Nama layanan</label>
                        <input id="nama_layanan" name="nama_layanan" type="text"
                               class="form-control @error('nama_layanan') is-invalid @enderror"
                               value="{{ old('nama_layanan') }}" placeholder="Contoh: Reguler" required>
                        @error('nama_layanan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label class="form-label" for="harga">Harga per kg</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input id="harga" name="harga" type="number" min="0" step="1"
                                       class="form-control @error('harga') is-invalid @enderror"
                                       value="{{ old('harga') }}" placeholder="7000" required>
                                @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-field">
                            <label class="form-label" for="estimasi">Estimasi pengerjaan</label>
                            <input id="estimasi" name="estimasi" type="text"
                                   class="form-control @error('estimasi') is-invalid @enderror"
                                   value="{{ old('estimasi') }}" placeholder="Contoh: 3-4 hari" required>
                            @error('estimasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary app-button" data-loading-button>Simpan Layanan</button>
                        <a href="{{ route('layanan.index') }}" class="btn btn-light app-button">Batal</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
