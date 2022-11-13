<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatJobsSubTypeSeeder extends Seeder{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(){
    DB::table('cat_jobs_subtype')->insert([
      'name'=>'Chef',
      'description'=>'',
      'order'=>0,
      'jobType_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_subtype')->insert([
      'name'=>'Lava platos',
      'description'=>'',
      'order'=>0,
      'jobType_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_subtype')->insert([
      'name'=>'Mesero',
      'description'=>'',
      'order'=>0,
      'jobType_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_subtype')->insert([
      'name'=>'Tablajero',
      'description'=>'',
      'order'=>0,
      'jobType_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_subtype')->insert([
      'name'=>'Cajero',
      'description'=>'',
      'order'=>0,
      'jobType_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_subtype')->insert([
      'name'=>'Intendencia',
      'description'=>'',
      'order'=>0,
      'jobType_id'=>2,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_subtype')->insert([
      'name'=>'Recepcionista',
      'description'=>'',
      'order'=>0,
      'jobType_id'=>2,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_subtype')->insert([
      'name'=>'Ama de llaves',
      'description'=>'',
      'order'=>0,
      'jobType_id'=>2,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_subtype')->insert([
      'name'=>'Botones',
      'description'=>'',
      'order'=>0,
      'jobType_id'=>2,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_subtype')->insert([
      'name'=>'Elevadorista',
      'description'=>'',
      'order'=>0,
      'jobType_id'=>2,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_subtype')->insert([
      'name'=>'Enfermera',
      'description'=>'',
      'order'=>0,
      'jobType_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_subtype')->insert([
      'name'=>'Médico general',
      'description'=>'',
      'order'=>0,
      'jobType_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_subtype')->insert([
      'name'=>'Camillero',
      'description'=>'',
      'order'=>0,
      'jobType_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_subtype')->insert([
      'name'=>'Odontólogo',
      'description'=>'',
      'order'=>0,
      'jobType_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('cat_jobs_subtype')->insert([
      'name'=>'Médico internista',
      'description'=>'',
      'order'=>0,
      'jobType_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);
  }
}
