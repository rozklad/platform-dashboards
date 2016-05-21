<?php namespace Sanatorium\Dashboards\Database\Seeds;

use DB;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class DashboardsTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// $faker = Faker::create();

		DB::table('dashboards')->truncate();

		foreach(range(1, 1) as $index)
		{
			DB::table('dashboards')->insert([
			 	'name' => 'Main',
				'slug' => 'main',
				'roles' => json_encode(['admin']),
			]);
		}
	}

}
