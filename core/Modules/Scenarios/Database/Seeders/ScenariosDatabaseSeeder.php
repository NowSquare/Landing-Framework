<?php

namespace Modules\Scenarios\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ScenariosDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");

        // Scenario if
        \DB::table('scenario_if')->insert([
            'sort' => 10,
            'name' => 'enters_region_of'
        ]);

        \DB::table('scenario_if')->insert([
            'sort' => 20,
            'name' => 'exits_region_of'
        ]);

        \DB::table('scenario_if')->insert([
            'sort' => 30,
            'name' => 'is_far_from'
        ]);

        \DB::table('scenario_if')->insert([
            'sort' => 40,
            'name' => 'is_near'
        ]);

        \DB::table('scenario_if')->insert([
            'sort' => 50,
            'name' => 'is_very_near'
        ]);

        // Scenario then
        \DB::table('scenario_then')->insert([
            'sort' => 10,
            'name' => 'only_for_analytics',
            'active' => false
        ]);

        \DB::table('scenario_then')->insert([
            'sort' => 20,
            'name' => 'show_image',
            'active' => true
        ]);

        \DB::table('scenario_then')->insert([
            'sort' => 30,
            'name' => 'show_template',
            'active' => true
        ]);

        \DB::table('scenario_then')->insert([
            'sort' => 40,
            'name' => 'open_url'
        ]);
    }
}
