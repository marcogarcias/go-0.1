<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('zones')->insert([
        'idzone'=>1,
        'name'=>'General',
        'deleted'=>0,
        'created_at'=>date('Y-m-d h:i:s'),
        'updated_at'=>date('Y-m-d h:i:s')
      ]);

      DB::table('zones')->insert([
        'name'=>'Norte',
        'deleted'=>0,
        'created_at'=>date('Y-m-d h:i:s'),
        'updated_at'=>date('Y-m-d h:i:s')
      ]);

	    DB::table('zones')->insert([
        'name'=>'Sur',
        'deleted'=>0,
        'created_at'=>date('Y-m-d h:i:s'),
        'updated_at'=>date('Y-m-d h:i:s')
      ]);

	    DB::table('zones')->insert([
        'name'=>'Este',
        'deleted'=>0,
        'created_at'=>date('Y-m-d h:i:s'),
        'updated_at'=>date('Y-m-d h:i:s')
      ]);

	    DB::table('zones')->insert([
        'name'=>'Oeste',
        'deleted'=>0,
        'created_at'=>date('Y-m-d h:i:s'),
        'updated_at'=>date('Y-m-d h:i:s')
      ]);
    }
}
