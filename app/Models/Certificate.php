<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'nama',
        'provinsi',
        'kabupaten',
        'klasifikasi',
        'subklasifikasi',
        'kualifikasi',
        'kode_jabatan',
        'jabatan_kerja',
        'nomor_registrasi',
        'nama_lsp',
        'nama_asosiasi',
        'tanggal_ditetapkan',
        'tanggal_berlaku',
    ];

    protected $casts = [
        'tanggal_ditetapkan' => 'datetime:Y-m-d H:i:s',
        'tanggal_berlaku'    => 'datetime:Y-m-d H:i:s',
    ];
}
