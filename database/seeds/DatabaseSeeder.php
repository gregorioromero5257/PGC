<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CompanyTableSeeder::class,
            PrivacyOptionTableSeeder::class,
            StackeholderTableSeeder::class,
            LocationTableSeeder::class,
            TypeTableSeeder::class,
            MethodologyTableSeeder::class,
            TopicTableSeeder::class,
            ShDescriptionTableSeeder::class,
            ShTypeTableSeeder::class,
            CurrencyTableSeeder::class,
            ContractTableSeeder::class,
            SatisfactionDocumentTableSeeder::class,
            RoleTableSeeder::class,
            PositionTableSeeder::class,
            DepartmentsTableSeeder::class,
        ]);
    }
}
