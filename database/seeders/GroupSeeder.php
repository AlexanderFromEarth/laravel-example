<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group = new Group([
            "name"        => "admins",
            "permissions" => ['animals.create','animals.read','animals.update','animals.delete'],
        ]);
        $group->save();
        $group = new Group([
            "name"        => "users",
            "permissions" => ['animals.create','animals.read'],
        ]);
        $group->save();
        $group = new Group([
            "name"        => "guests",
            "permissions" => [],
        ]);
        $group->save();
    }
}
