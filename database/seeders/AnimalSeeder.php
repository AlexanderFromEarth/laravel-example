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
        if (config("app.env") === "dev") {
            foreach(range(1, 1000) as $i) {
                while (!$model->createRecord()) {
                }
            }
        }
    }
}
