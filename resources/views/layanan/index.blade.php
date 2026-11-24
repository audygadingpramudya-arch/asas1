@extends('layouts.app')

@section('title', 'Data Layanan')

@section('content')
    <div class="app-content">
        <div class="container-fluid app-container">
            @if (session('success'))
                <div class="alert app-alert app-alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert app-alert app-alert-error">{{ session('error') }}</div>
            @endif

            <section class="service-toolbar">
                <div>
                    <span class="page-eyebrow">KATALOG LAUNDRY</span>
                    <h2 class="section-title">Layanan yang tersedia</h2>
                    <p class="page-subtitle"><span data-service-count>{{ $layanans->count() }}</span> pilihan layanan untuk transaksi laundry Anda.</p>
                </div>
                <div class="service-toolbar-actions">
                    <label class="table-search service-search" for="service-search">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="10.8" cy="10.8" r="6.8" stroke="currentColor" stroke-width="1.7"/>
                            <path d="m16 16 4 4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                        </svg>
                        <input id="service-search" type="search" data-service-search placeholder="Cari layanan..." autocomplete="off">
                    </label>
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('layanan.create') }}" class="btn btn-primary app-button">
                            <span aria-hidden="true">+</span> Tambah Layanan
                        </a>
                    @endif
                </div>
            </section>

            <section class="service-grid" aria-label="Daftar layanan">
                @forelse ($layanans as $layanan)
                    <article class="service-plan" data-service-card>
                        <div class="service-plan-top">
                            <span class="service-plan-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M7 4.5h10A1.5 1.5 0 0 1 18.5 6v14l-2.2-1.5L14 20l-2-1.5L10 20l-2.2-1.5L5.5 20V6A1.5 1.5 0 0 1 7 4.5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                                    <path d="M8.5 9h7M8.5 12.5h5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <span class="service-plan-estimate"><span aria-hidden="true">◷</span> {{ $layanan->estimasi }}</span>
                        </div>
                        <h3>{{ $layanan->nama_layanan }}</h3>
                        <p class="service-plan-price">Rp {{ number_format($layanan->harga, 0, ',', '.') }}<span>/ kg</span></p>
                        <p class="service-plan-description">Layanan {{ strtolower($layanan->nama_layanan) }} dengan hasil bersih, wangi, dan terawat.</p>
                        <div class="service-plan-meta">
                            <span>Estimasi pengerjaan</span>
                            <strong>{{ $layanan->estimasi }}</strong>
                        </div>
                        <div class="service-plan-meta">
                            <span>Dipakai pada</span>
                            <strong>{{ $layanan->transaksis_count }} transaksi</strong>
                        </div>
                        @if (Auth::user()->isAdmin())
                            <div class="service-plan-actions">
                                <a href="{{ route('layanan.edit', $layanan) }}" class="btn btn-outline-primary app-button">
                                    <span aria-hidden="true">✎</span> Edit
                                </a>
                                @if ($layanan->transaksis_count)
                                    <button type="button" class="btn btn-light app-button" disabled title="Layanan yang sudah digunakan tidak dapat dihapus">Sudah digunakan</button>
                                @else
                                    <form action="{{ route('layanan.destroy', $layanan) }}" method="POST"
                                          onsubmit="return confirm('Hapus layanan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger app-button">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="panel-card empty-state service-empty-state">
                        <strong>Belum ada layanan</strong>
                        <span>Tambahkan jenis layanan agar transaksi dapat dicatat.</span>
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('layanan.create') }}" class="btn btn-primary app-button">Tambah layanan pertama</a>
                        @endif
                    </div>
                @endforelse
            </section>
            @if ($layanans->isNotEmpty())
                <div class="service-no-results" data-service-empty hidden>
                    <strong>Layanan tidak ditemukan</strong>
                    <span>Coba kata kunci lain untuk mencari layanan.</span>
                </div>
            @endif
        </div>
    </div>
@endsection
