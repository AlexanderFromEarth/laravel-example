<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new \App\Models\Animal();
        foreach(range(1, (config("app.env") !== "testing" ? 1000 : 100000)) as $i) {
            while (!$model->createRecord()) {
            }
        }
    }
}
