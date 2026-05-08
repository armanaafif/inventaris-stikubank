<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitMeasure;

class UnitMeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Daftar satuan inventaris
        |--------------------------------------------------------------------------
        */

        $units = [

            'PCS',
            'UNIT',
            'BOX',
            'PACK',
            'SET',
            'ROLL',

            'METER',
            'CM',
            'MM',

            'KG',
            'GRAM',

            'LITER',
            'ML',

            'BOTOL',
            'KALENG',
            'TABUNG',

            'LEMBAR',
            'RIM',
            'BUKU',

            'BIJI',
            'BUNGKUS',
            'KARUNG',

            'KABEL',
            'TONER',
            'CARTRIDGE'

        ];

        /*
        |--------------------------------------------------------------------------
        | Simpan data jika belum ada
        |--------------------------------------------------------------------------
        */

        foreach ($units as $unit) {

            UnitMeasure::firstOrCreate([
                'name' => $unit
            ]);

        }
    }
}