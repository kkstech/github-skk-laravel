<?php

namespace Database\Seeders;

use App\Models\Classification;
use App\Models\Subclassification;
use App\Models\Qualification;
use App\Models\WorkPosition;
use App\Models\Lsp;
use App\Models\Association;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Classifications, Subclassifications & Work Positions
        $classifications = [
            'SIPIL' => [
                'Gedung' => [
                    ['SI011001', 'Ahli Madya Pelaksana Konstruksi Bangunan Gedung'],
                    ['SI011002', 'Ahli Muda Perencana Struktur Bangunan Gedung'],
                ],
                'Jalan' => [
                    ['SI021001', 'Ahli Madya Teknik Jalan'],
                    ['SI021002', 'Ahli Muda Teknik Jalan'],
                ],
                'Jembatan' => [
                    ['SI031001', 'Ahli Madya Teknik Jembatan'],
                ]
            ],
            'ARSITEKTUR' => [
                'Arsitektur Gedung' => [
                    ['AR011001', 'Ahli Madya Arsitek Gedung'],
                    ['AR011002', 'Ahli Muda Arsitek Gedung'],
                ],
                'Lansekap' => [
                    ['AR021001', 'Ahli Madya Arsitektur Lansekap'],
                ]
            ],
            'MEKANIKAL' => [
                'Utilitas Gedung' => [
                    ['MK011001', 'Ahli Madya Sistem Utilitas Gedung'],
                ]
            ],
            'ELEKTRIKAL' => [
                'Ketenagalistrikan' => [
                    ['EL011001', 'Ahli Madya Teknik Ketenagalistrikan'],
                ]
            ]
        ];

        foreach ($classifications as $clsName => $subs) {
            $cls = Classification::firstOrCreate(['nama' => $clsName]);
            foreach ($subs as $subName => $wps) {
                $sub = Subclassification::firstOrCreate([
                    'classification_id' => $cls->id,
                    'nama' => $subName
                ]);
                foreach ($wps as $wp) {
                    WorkPosition::firstOrCreate([
                        'subclassification_id' => $sub->id,
                        'kode_jabatan' => $wp[0]
                    ], [
                        'nama' => $wp[1]
                    ]);
                }
            }
        }

        // 2. Qualifications
        $qualifications = [
            'Ahli Utama',
            'Ahli Madya',
            'Ahli Muda',
            'Teknisi / Analis',
            'Operator'
        ];
        foreach ($qualifications as $qual) {
            Qualification::firstOrCreate(['nama' => $qual]);
        }

        // 3. Lsps
        $lsps = [
            'LSP ASTEKINDO KONSTRUKSI MANDIRI',
            'LSP GATAKI KONSTRUKSI MANDIRI',
            'LSP PETAKINDO',
            'LSP KONSULTAN KONSTRUKSI MANDIRI'
        ];
        foreach ($lsps as $lsp) {
            Lsp::firstOrCreate(['nama' => $lsp]);
        }

        // 4. Associations
        $associations = [
            'ASTEKINDO',
            'GATAKI',
            'PERPAKOM',
            'HATHI'
        ];
        foreach ($associations as $assoc) {
            Association::firstOrCreate(['nama' => $assoc]);
        }
    }
}
