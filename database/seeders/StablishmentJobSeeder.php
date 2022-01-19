<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StablishmentJobSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('stablishments_jobs')->insert([
      'name'=>'Ayudante General',
      'description'=>'Se solicita ayudante general.',
      'requirements'=>'Edad entre 18 a 40 años. Sexo indistinto. Escolaridad: secundaria. Disponibilidad de tiempo.',
      'documentation'=>'solicitud de empleo',
      'stablishment_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('stablishments_jobs')->insert([
      'name'=>'Vendedor de mostrador',
      'description'=>'Se solicita vendedor de mostrador.',
      'requirements'=>'Edad entre 18 a 25 años. Sexo: femenino. Escolaridad: secundaria. Disponibilidad de tiempo.',
      'documentation'=>'solicitud de empleo',
      'stablishment_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('stablishments_jobs')->insert([
      'name'=>'Secretaria',
      'description'=>'Se solicita secretaria.',
      'requirements'=>'Edad entre 22 a 30 años. Sexo: femenino. Escolaridad: preparatoria. Disponibilidad de tiempo.',
      'documentation'=>'cv',
      'stablishment_id'=>2,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);
  }
}
