<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StablishmentAdSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('stablishments_ads')->insert([
      'name'=>'Anuncio de ejemplo',
      'description'=>'Próxima semana descuentos en toda la tienda.',
      'stablishment_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('stablishments_ads')->insert([
      'name'=>'',
      'description'=>'Feliz día de la independencia, les deseamos a todos.',
      'stablishment_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('stablishments_ads')->insert([
      'name'=>'',
      'description'=>'El próximo día 15 no habrá labores, gracias..',
      'stablishment_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);
  }
}
