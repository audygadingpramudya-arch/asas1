<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaundryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_see_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee('staff-sidebar');
        $response->assertSee('Transaksi Laundry');
    }

    public function test_kasir_can_login_and_see_dashboard(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $response = $this->actingAs($kasir)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Kasir');
    }

    public function test_dashboard_displays_revenue_chart_and_recent_transaction_summary(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $layanan = Layanan::create(['nama_layanan' => 'Reguler', 'harga' => 7000]);
        $transaksi = Transaksi::create([
            'layanan_id' => $layanan->id,
            'user_id' => $admin->id,
            'nama_pelanggan' => 'Budi Santoso',
            'no_hp' => '08123456789',
            'berat' => 2,
            'total_harga' => 14000,
            'tanggal_masuk' => now()->toDateString(),
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertOk()
            ->assertSee('Grafik Pendapatan')
            ->assertSee('Status Laundry')
            ->assertSee('Budi Santoso')
            ->assertSee('Rp 14.000')
            ->assertSee('TRX'.now()->format('ymd').str_pad((string) $transaksi->id, 4, '0', STR_PAD_LEFT));
    }

    public function test_customer_booking_pages_are_not_available_in_staff_only_workflow(): void
    {
        $this->get('/pesan')->assertNotFound();
        $this->get('/layanan-kami')->assertNotFound();
        $this->get('/pesanan-saya')->assertNotFound();
    }

    public function test_users_without_a_staff_role_cannot_open_the_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)->get('/dashboard')->assertForbidden();
    }

    public function test_admin_can_create_layanan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/layanan', [
            'nama_layanan' => 'Superkilat',
            'harga' => 25000,
            'estimasi' => '5 jam',
        ]);

        $response->assertRedirect(route('layanan.index'));
        $this->assertDatabaseHas('layanans', ['nama_layanan' => 'Superkilat', 'harga' => 25000]);
    }

    public function test_layanan_page_displays_searchable_service_cards(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Layanan::create([
            'nama_layanan' => 'Reguler',
            'harga' => 7000,
            'estimasi' => '3-4 hari',
        ]);

        $this->actingAs($admin)
            ->get(route('layanan.index'))
            ->assertOk()
            ->assertSee('Cari layanan...')
            ->assertSee('Reguler')
            ->assertSee('data-service-card', false);
    }

    public function test_admin_can_update_and_delete_unused_layanan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $layanan = Layanan::create([
            'nama_layanan' => 'Reguler',
            'harga' => 7000,
            'estimasi' => '3-4 hari',
        ]);

        $this->actingAs($admin)
            ->put(route('layanan.update', $layanan), [
                'nama_layanan' => 'Reguler Plus',
                'harga' => 9000,
                'estimasi' => '2-3 hari',
            ])
            ->assertRedirect(route('layanan.index'));

        $this->assertDatabaseHas('layanans', [
            'id' => $layanan->id,
            'nama_layanan' => 'Reguler Plus',
            'harga' => 9000,
            'estimasi' => '2-3 hari',
        ]);

        $this->delete(route('layanan.destroy', $layanan))
            ->assertRedirect(route('layanan.index'));

        $this->assertDatabaseMissing('layanans', ['id' => $layanan->id]);
    }

    public function test_kasir_cannot_create_layanan(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $response = $this->actingAs($kasir)->post('/layanan', [
            'nama_layanan' => 'Reguler',
            'harga' => 7000,
            'estimasi' => '3-4 hari',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('layanans', ['nama_layanan' => 'Reguler']);
    }

    public function test_transaksi_is_linked_to_layanan_and_computes_total(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $layanan = Layanan::create(['nama_layanan' => 'Reguler', 'harga' => 7000]);

        $response = $this->actingAs($kasir)->post('/transaksi', [
            'layanan_id' => $layanan->id,
            'nama_pelanggan' => 'Budi',
            'no_hp' => '08123456789',
            'berat' => 3,
            'tanggal_masuk' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('transaksi.index'));

        $transaksi = Transaksi::firstOrFail();
        $this->assertSame($layanan->id, $transaksi->layanan_id);
        $this->assertSame($kasir->id, $transaksi->user_id);
        $this->assertSame('proses', $transaksi->status);
        $this->assertEquals(21000, $transaksi->total_harga);
    }

    public function test_transaction_total_is_calculated_for_fractional_weight(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $layanan = Layanan::create(['nama_layanan' => 'Reguler', 'harga' => 7000]);

        $this->actingAs($kasir)->post('/transaksi', [
            'layanan_id' => $layanan->id,
            'nama_pelanggan' => 'Budi',
            'no_hp' => '08123456789',
            'berat' => 1.5,
            'tanggal_masuk' => now()->toDateString(),
        ])->assertRedirect(route('transaksi.index'));

        $transaksi = Transaksi::firstOrFail();
        $this->assertEquals(1.5, $transaksi->berat);
        $this->assertEquals(10500, $transaksi->total_harga);
    }

    public function test_layanan_with_transactions_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $layanan = Layanan::create(['nama_layanan' => 'Reguler', 'harga' => 7000]);
        $transaksi = Transaksi::create([
            'user_id' => $admin->id,
            'layanan_id' => $layanan->id,
            'nama_pelanggan' => 'Siti',
            'no_hp' => '0899999999',
            'berat' => 2,
            'total_harga' => 14000,
            'tanggal_masuk' => now()->toDateString(),
            'status' => 'proses',
        ]);

        $this->actingAs($admin)
            ->delete(route('layanan.destroy', $layanan))
            ->assertRedirect(route('layanan.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('transaksis', ['id' => $transaksi->id]);
    }

    public function test_status_can_be_updated_from_proses_to_selesai(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $layanan = Layanan::create(['nama_layanan' => 'Ekspress', 'harga' => 12000]);
        $transaksi = Transaksi::create([
            'layanan_id' => $layanan->id,
            'user_id' => $kasir->id,
            'nama_pelanggan' => 'Siti',
            'no_hp' => '0899999999',
            'berat' => 2,
            'total_harga' => 24000,
            'tanggal_masuk' => now()->toDateString(),
            'status' => 'proses',
        ]);

        $this->actingAs($kasir)
            ->patch(route('transaksi.status', $transaksi))
            ->assertRedirect(route('transaksi.index'));

        $this->assertSame('selesai', $transaksi->fresh()->status);
    }

    public function test_laporan_shows_transaksi_with_relation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $layanan = Layanan::create(['nama_layanan' => 'Superkilat', 'harga' => 25000]);
        Transaksi::create([
            'layanan_id' => $layanan->id,
            'user_id' => $admin->id,
            'nama_pelanggan' => 'Andi',
            'no_hp' => '0811111111',
            'berat' => 1,
            'total_harga' => 25000,
            'tanggal_masuk' => now()->toDateString(),
            'status' => 'selesai',
        ]);
        Transaksi::create([
            'layanan_id' => $layanan->id,
            'user_id' => $admin->id,
            'nama_pelanggan' => 'Rina',
            'no_hp' => '0822222222',
            'berat' => 1,
            'total_harga' => 25000,
            'tanggal_masuk' => now()->toDateString(),
            'status' => 'proses',
        ]);

        $response = $this->actingAs($admin)->get('/laporan');

        $response->assertStatus(200);
        $response->assertSee('Rekap Transaksi');
        $response->assertSee('Superkilat');
        $response->assertSee('Andi');
        $response->assertSee('50.000');
        $response->assertSee('Cetak Laporan');
        $response->assertSee('data-print-report', false);
    }

    public function test_laporan_can_be_filtered_by_date_and_service(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $reguler = Layanan::create(['nama_layanan' => 'Reguler', 'harga' => 7000]);
        $ekspres = Layanan::create(['nama_layanan' => 'Ekspres', 'harga' => 12000]);

        Transaksi::create([
            'layanan_id' => $reguler->id,
            'user_id' => $admin->id,
            'nama_pelanggan' => 'Sesuai Filter',
            'no_hp' => '0811111111',
            'berat' => 1,
            'total_harga' => 7000,
            'tanggal_masuk' => '2025-01-15',
            'status' => 'proses',
        ]);
        Transaksi::create([
            'layanan_id' => $ekspres->id,
            'user_id' => $admin->id,
            'nama_pelanggan' => 'Layanan Berbeda',
            'no_hp' => '0822222222',
            'berat' => 1,
            'total_harga' => 12000,
            'tanggal_masuk' => '2025-01-15',
            'status' => 'selesai',
        ]);
        Transaksi::create([
            'layanan_id' => $reguler->id,
            'user_id' => $admin->id,
            'nama_pelanggan' => 'Tanggal Berbeda',
            'no_hp' => '0833333333',
            'berat' => 1,
            'total_harga' => 7000,
            'tanggal_masuk' => '2025-01-16',
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($admin)->get(route('laporan.index', [
            'dari' => '2025-01-15',
            'sampai' => '2025-01-15',
            'layanan_id' => $reguler->id,
        ]));

        $response->assertOk()
            ->assertSee('Sesuai Filter')
            ->assertDontSee('Layanan Berbeda')
            ->assertDontSee('Tanggal Berbeda')
            ->assertSee('data-counter="1"', false)
            ->assertSee('data-counter="7000"', false);
    }

    public function test_transaction_can_be_created_with_completed_status(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $layanan = Layanan::create(['nama_layanan' => 'Reguler', 'harga' => 7000]);

        $this->actingAs($kasir)->post(route('transaksi.store'), [
            'layanan_id' => $layanan->id,
            'nama_pelanggan' => 'Budi',
            'no_hp' => '08123456789',
            'berat' => 2,
            'tanggal_masuk' => now()->toDateString(),
            'status' => 'selesai',
        ])->assertRedirect(route('transaksi.index'));

        $this->assertDatabaseHas('transaksis', [
            'nama_pelanggan' => 'Budi',
            'status' => 'selesai',
            'total_harga' => 14000,
        ]);
    }
}
