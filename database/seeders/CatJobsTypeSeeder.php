<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatJobsTypeSeeder extends Seeder{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(){
    DB::table('cat_jobs_type')->insert([
      'name'=>'Restaurantes',
      'description'=>'',
      'order'=>0,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_type')->insert([
      'name'=>'Hotelería',
      'description'=>'',
      'order'=>0,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_type')->insert([
      'name'=>'Tecnología',
      'description'=>'',
      'order'=>0,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_type')->insert([
      'name'=>'Salud',
      'description'=>'',
      'order'=>0,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_type')->insert([
      'name'=>'Belleza',
      'description'=>'',
      'order'=>0,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_type')->insert([
      'name'=>'Ventas',
      'description'=>'',
      'order'=>0,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_type')->insert([
      'name'=>'Otros',
      'description'=>'',
      'order'=>100,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);
  }
}
