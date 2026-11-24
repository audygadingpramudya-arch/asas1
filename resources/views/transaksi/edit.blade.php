@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('content')
    <div class="app-content">
        <div class="container-fluid app-container">
            <div class="form-page-heading">
                <a href="{{ route('transaksi.index') }}" class="back-link">&larr; Kembali ke Transaksi</a>
                <span class="page-eyebrow">TRANSAKSI LAUNDRY</span>
                <h2 class="section-title">Edit transaksi #{{ $transaksi->id }}</h2>
                <p class="page-subtitle">Perbarui data pelanggan, layanan, berat, atau status.</p>
            </div>

            <section class="panel-card form-panel">
                <form action="{{ route('transaksi.update', $transaksi) }}" method="POST" class="app-form" data-loading-form data-price-form>
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="form-field">
                            <label class="form-label" for="nama_pelanggan">Nama pelanggan</label>
                            <input id="nama_pelanggan" name="nama_pelanggan" type="text"
                                   class="form-control @error('nama_pelanggan') is-invalid @enderror"
                                   value="{{ old('nama_pelanggan', $transaksi->nama_pelanggan) }}" required>
                            @error('nama_pelanggan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-field">
                            <label class="form-label" for="no_hp">Nomor HP</label>
                            <input id="no_hp" name="no_hp" type="tel"
                                   class="form-control @error('no_hp') is-invalid @enderror"
                                   value="{{ old('no_hp', $transaksi->no_hp) }}" required>
                            @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label class="form-label" for="layanan_id">Jenis layanan</label>
                            <select id="layanan_id" name="layanan_id"
                                    class="form-select @error('layanan_id') is-invalid @enderror" required>
                                @foreach ($layanans as $layanan)
                                    <option value="{{ $layanan->id }}" data-harga="{{ $layanan->harga }}"
                                            @selected(old('layanan_id', $transaksi->layanan_id) == $layanan->id)>
                                        {{ $layanan->nama_layanan }} · Rp {{ number_format($layanan->harga, 0, ',', '.') }}/kg · {{ $layanan->estimasi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('layanan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-field">
                            <label class="form-label" for="berat">Berat laundry</label>
                            <div class="input-group">
                                <input id="berat" name="berat" type="number" min="0.01" max="999999.99" step="0.01"
                                       class="form-control @error('berat') is-invalid @enderror"
                                       value="{{ old('berat', $transaksi->berat) }}" required>
                                <span class="input-group-text">kg</span>
                                @error('berat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label class="form-label" for="tanggal_masuk">Tanggal masuk</label>
                            <input id="tanggal_masuk" name="tanggal_masuk" type="date"
                                   class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                   value="{{ old('tanggal_masuk', $transaksi->tanggal_masuk->format('Y-m-d')) }}" required>
                            @error('tanggal_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-field">
                            <label class="form-label" for="status">Status transaksi</label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="proses" @selected(old('status', $transaksi->status) === 'proses')>Proses</option>
                                <option value="selesai" @selected(old('status', $transaksi->status) === 'selesai')>Selesai</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="price-summary">
                        <span class="price-summary-icon" aria-hidden="true">Rp</span>
                        <span class="price-summary-copy">
                            <small>Estimasi total harga</small>
                            <strong data-price-total aria-live="polite">Rp 0</strong>
                        </span>
                        <span class="price-summary-note">Harga dihitung dari tarif layanan × berat</span>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary app-button" data-loading-button>Simpan Perubahan</button>
                        <a href="{{ route('transaksi.index') }}" class="btn btn-light app-button">Batal</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
