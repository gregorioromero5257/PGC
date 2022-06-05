<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sh_types = [
            [
                'name' => 'Cliente',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Financiador',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Proveedor',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ];

        DB::table('sh_types')->insert($sh_types);
    }
}
