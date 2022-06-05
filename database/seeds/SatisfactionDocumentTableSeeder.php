<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SatisfactionDocumentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $satisfaction_documents = [
            [
                'name' => 'Carta de satisfacción genérica y/o liberación de fianza',
                'active' => 0,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Carta de satisfacción con nombres',
                'active' => 0,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];

        DB::table('satisfaction_documents')->insert($satisfaction_documents);
    }
}
