<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('sections')->insert([
      'name'=>'Comida',
      'description'=>'Sección de comida.',
      'image'=>'btn-comida.png',
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('sections')->insert([
      'name'=>'Diversión',
      'description'=>'Sección de diversión.',
      'image'=>'btn-diversion.png',
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('sections')->insert([
      'name'=>'Hospedaje',
      'description'=>'Sección de hospedaje.',
      'image'=>'btn-hospedaje.png',
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('sections')->insert([
      'name'=>'Negocios',
      'description'=>'Sección de negocios.',
      'image'=>'btn-negocios.png',
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('sections')->insert([
      'name'=>'Salud',
      'description'=>'Sección de salud.',
      'image'=>'btn-salud.png',
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('sections')->insert([
      'name'=>'Estilo',
      'description'=>'Sección de estilo.',
      'image'=>'btn-estilo.png',
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);
  }
}
