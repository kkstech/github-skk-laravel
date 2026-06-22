<?php

namespace Tests\Feature;

use App\Models\Certificate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CertificateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_certificate_with_valid_dates(): void
    {
        $data = [
            'nama' => 'JOHN DOE',
            'provinsi' => 'DKI Jakarta',
            'kabupaten' => 'Jakarta Selatan',
            'klasifikasi' => 'SIPIL',
            'subklasifikasi' => 'Ahli Teknik Jalan',
            'kualifikasi' => 'Ahli',
            'kode_jabatan' => 'SI011002',
            'jabatan_kerja' => 'Ahli Teknik Jalan',
            'nomor_registrasi' => 'REG-123456',
            'nama_lsp' => 'LSP INDONESIA',
            'nama_asosiasi' => 'ASTEKINDO',
            'tanggal_ditetapkan' => '2026-06-11T14:35',
            'tanggal_berlaku' => '2029-06-11T15:40',
        ];

        $response = $this->postJson(route('certificates.store'), $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Sertifikat berhasil ditambahkan.',
                 ]);

        $cert = Certificate::first();
        $this->assertEquals('JOHN DOE', $cert->nama);
        $this->assertEquals('2026-06-11 14:35:00', $cert->tanggal_ditetapkan->format('Y-m-d H:i:s'));
        $this->assertEquals('2029-06-11 15:40:00', $cert->tanggal_berlaku->format('Y-m-d H:i:s'));
    }

    public function test_cannot_create_certificate_with_invalid_dates(): void
    {
        $data = [
            'nama' => 'JOHN DOE',
            'provinsi' => 'DKI Jakarta',
            'kabupaten' => 'Jakarta Selatan',
            'klasifikasi' => 'SIPIL',
            'subklasifikasi' => 'Ahli Teknik Jalan',
            'kualifikasi' => 'Ahli',
            'kode_jabatan' => 'SI011002',
            'jabatan_kerja' => 'Ahli Teknik Jalan',
            'nomor_registrasi' => 'REG-123456',
            'nama_lsp' => 'LSP INDONESIA',
            'nama_asosiasi' => 'ASTEKINDO',
            'tanggal_ditetapkan' => 'invalid-date',
            'tanggal_berlaku' => '2029-06-11',
        ];

        $response = $this->postJson(route('certificates.store'), $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['tanggal_ditetapkan']);
    }

    public function test_can_update_certificate_dates(): void
    {
        $certificate = Certificate::create([
            'nama' => 'JOHN DOE',
            'provinsi' => 'DKI Jakarta',
            'kabupaten' => 'Jakarta Selatan',
            'klasifikasi' => 'SIPIL',
            'subklasifikasi' => 'Ahli Teknik Jalan',
            'kualifikasi' => 'Ahli',
            'kode_jabatan' => 'SI011002',
            'jabatan_kerja' => 'Ahli Teknik Jalan',
            'nomor_registrasi' => 'REG-123456',
            'nama_lsp' => 'LSP INDONESIA',
            'nama_asosiasi' => 'ASTEKINDO',
            'tanggal_ditetapkan' => '2026-06-11 14:35:00',
            'tanggal_berlaku' => '2029-06-11 15:40:00',
        ]);

        $updateData = array_merge($certificate->toArray(), [
            'tanggal_ditetapkan' => '2026-07-20T10:15',
            'tanggal_berlaku' => '2029-07-20T11:25',
        ]);

        $response = $this->putJson(route('certificates.update', $certificate->id), $updateData);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Sertifikat berhasil diupdate.',
                 ]);

        $cert = $certificate->fresh();
        $this->assertEquals('2026-07-20 10:15:00', $cert->tanggal_ditetapkan->format('Y-m-d H:i:s'));
        $this->assertEquals('2029-07-20 11:25:00', $cert->tanggal_berlaku->format('Y-m-d H:i:s'));
    }
}
