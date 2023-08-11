<?php

use App\System;
use Illuminate\Database\Seeder;

class SystemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [ 'version' => config('author.app_version') ];

        foreach ($datas as $key => $value) {
            System::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
