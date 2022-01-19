<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('tags')->insert([
      'name'=>'Comida Casera',
      'description'=>'Lugares de comida.',
      'image'=>'btn-comidaCasera-small.png',
      'section_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Cafetería',
      'description'=>'Cafeterías.',
      'image'=>'btn-cafeteria-small.png',
      'section_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'China Food',
      'description'=>'Lugares de comida china.',
      'image'=>'btn-chinaFood-small.png',
      'section_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Sushi',
      'description'=>'Lugares de comida sushi.',
      'image'=>'btn-sushi-small.png',
      'section_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Mariscos',
      'description'=>'Lugares de mariscos.',
      'image'=>'btn-mariscos-small.png',
      'section_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Rostizados',
      'description'=>'Lugares de comida rostizada.',
      'image'=>'btn-rostizados-small.png',
      'section_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Tortas',
      'description'=>'Lugares de tortas.',
      'image'=>'btn-tortas-small.png',
      'section_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Tacos',
      'description'=>'Lugares de tacos.',
      'image'=>'btn-tacos-small.png',
      'section_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Quesadillas',
      'description'=>'Lugares de quesadillas.',
      'image'=>'btn-quesadillas-small.png',
      'section_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Pizza',
      'description'=>'Pizzerías.',
      'image'=>'btn-pizza-small.png',
      'section_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Hamburguesas',
      'description'=>'Lugares de hamburguesas.',
      'image'=>'btn-hamburguesas-small.png',
      'section_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Alitas',
      'description'=>'Lugares de alitas.',
      'image'=>'btn-alitas-small.png',
      'section_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Postres',
      'description'=>'Lugares de postres.',
      'image'=>'btn-postres-small.png',
      'section_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Helados',
      'description'=>'Lugares de helados.',
      'image'=>'btn-helados-small.png',
      'section_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Pan dulce',
      'description'=>'Lugares de pan dulce.',
      'image'=>'btn-panDulce-small.png',
      'section_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Crepas',
      'description'=>'Lugares de crepas.',
      'image'=>'btn-crepas-small.png',
      'section_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Costillas BBQ',
      'description'=>'Lugares de costillas.',
      'image'=>'btn-costillasBbq-small.png',
      'section_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Verduras',
      'description'=>'Lugares de verduras.',
      'image'=>'btn-verduras-small.png',
      'section_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Carnes',
      'description'=>'Lugares de carnes.',
      'image'=>'btn-carnes-small.png',
      'section_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Ropa',
      'description'=>'ropa.',
      'image'=>'btn-ropa-small.png',
      'section_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Calzado',
      'description'=>'Lugares de calzado.',
      'image'=>'btn-calzado-small.png',
      'section_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Belleza',
      'description'=>'Lugares de belleza.',
      'image'=>'btn-belleza-small.png',
      'section_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Fitness',
      'description'=>'Lugares de fitness.',
      'image'=>'btn-fitness-small.png',
      'section_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Madera',
      'description'=>'Lugares de maderas.',
      'image'=>'btn-madera-small.png',
      'section_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Refacciones Auto',
      'description'=>'Lugares de refacciones para autos.',
      'image'=>'btn-refacciones-small.png',
      'section_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Tapiceria',
      'description'=>'Lugares de tapiceria.',
      'image'=>'btn-tapiceria-small.png',
      'section_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Muebles',
      'description'=>'Lugares de muebles.',
      'image'=>'btn-muebles-small.png',
      'section_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Lavanderia',
      'description'=>'Lugares de lavanderia.',
      'image'=>'btn-lavanderia-small.png',
      'section_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Tintorería',
      'description'=>'Lugares de tintorería.',
      'image'=>'btn-tintoreria-small.png',
      'section_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Celulares',
      'description'=>'Reparación de celulares.',
      'image'=>'btn-celulares-small.png',
      'section_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Material de construccción',
      'description'=>'Materiales de construccción.',
      'image'=>'btn-materiales-small.png',
      'section_id'=>4,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Baile',
      'description'=>'Lugares de baile.',
      'image'=>'btn-baile-small.png',
      'section_id'=>2,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Bebidas',
      'description'=>'Lugares de bebidas.',
      'image'=>'btn-bebidas-small.png',
      'section_id'=>2,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Billar',
      'description'=>'Lugares de billar.',
      'image'=>'btn-billar-small.png',
      'section_id'=>2,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Música en vivo',
      'description'=>'Lugares de música.',
      'image'=>'btn-musicaEnVivo-small.png',
      'section_id'=>2,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Renta de consolas',
      'description'=>'Lugares de renta de consolas.',
      'image'=>'btn-rentaDeConsolas-small.png',
      'section_id'=>2,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Variedad',
      'description'=>'Lugares de variedad.',
      'image'=>'btn-variedad-small.png',
      'section_id'=>2,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Hotel',
      'description'=>'Lugares de hoteles.',
      'image'=>'btn-hotel-small.png',
      'section_id'=>3,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Motel',
      'description'=>'Lugares de moteles.',
      'image'=>'btn-motel-small.png',
      'section_id'=>3,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Servicio al cuarto',
      'description'=>'Lugares de servicio al cuarto.',
      'image'=>'btn-servicioAlCuarto-small.png',
      'section_id'=>3,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Consultorio Médico',
      'description'=>'Lugares de consultorios médicos.',
      'image'=>'btn-consultorioMedico-small.png',
      'section_id'=>5,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Farmacia',
      'description'=>'Lugares de farmacias.',
      'image'=>'btn-farmacia-small.png',
      'section_id'=>5,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Laboratorio',
      'description'=>'Lugares de laboratorio.',
      'image'=>'btn-laboratorio-small.png',
      'section_id'=>5,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);
    // TAGS DE ESTILO
    DB::table('tags')->insert([
      'name'=>'Peluquería',
      'description'=>'Cortes de cabello.',
      'image'=>'',
      'section_id'=>6,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Maquillaje',
      'description'=>'Trabajos de maquillaje.',
      'image'=>'',
      'section_id'=>6,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Manicura',
      'description'=>'Trabajos de uñas.',
      'image'=>'',
      'section_id'=>6,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Pedicura',
      'description'=>'Trabajos de los pies.',
      'image'=>'',
      'section_id'=>6,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Tatuajes',
      'description'=>'Trabajos de tatuajes.',
      'image'=>'',
      'section_id'=>6,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('tags')->insert([
      'name'=>'Perforaciones',
      'description'=>'Trabajos de perforaciones.',
      'image'=>'',
      'section_id'=>6,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);
  }
}
