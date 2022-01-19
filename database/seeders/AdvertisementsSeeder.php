<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdvertisementsSeeder extends Seeder
{
  /**
    * Run the database seeds.
    *
    * @return void
    */
  public function run()
  {
    DB::table('advertisements')->insert([
      'name'=>'Anuncio 1',
      'description'=>'Esta es la descripción del anuncio 1 y aquí se ponen los detalles del mismo.',
      'data'=>'Aquí van datos del anuncio 1.',
      'notes'=>'Aquí van notas del anuncio 1.',
      'image'=>'anuncio_1.png',
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('advertisements')->insert([
      'name'=>'Anuncio 2',
      'description'=>'Esta es la descripción del anuncio 2 y aquí se ponen los detalles del mismo.',
      'data'=>'Aquí van datos del anuncio 2.',
      'notes'=>'Aquí van notas del anuncio 2.',
      'image'=>'anuncio_2.png',
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('advertisements')->insert([
      'name'=>'Anuncio 3',
      'description'=>'Esta es la descripción del anuncio 3 y aquí se ponen los detalles del mismo.',
      'data'=>'Aquí van datos del anuncio 3.',
      'notes'=>'Aquí van notas del anuncio 3.',
      'image'=>'anuncio_3.png',
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('advertisements')->insert([
      'name'=>'Anuncio 4',
      'description'=>'Esta es la descripción del anuncio 4 y aquí se ponen los detalles del mismo.',
      'data'=>'Aquí van datos del anuncio 4.',
      'notes'=>'Aquí van notas del anuncio 4.',
      'image'=>'anuncio_4.png',
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('advertisements')->insert([
      'name'=>'Anuncio 5',
      'description'=>'Esta es la descripción del anuncio 4 y aquí se ponen los detalles del mismo.',
      'data'=>'Aquí van datos del anuncio 4.',
      'notes'=>'Aquí van notas del anuncio 4.',
      'image'=>'anuncio_5.png',
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);
  }
}
