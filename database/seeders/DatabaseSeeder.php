<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
     //\App\Models\User::factory(10)->create();
    $this->call([
      ProfileSeeder::class,
      EstadoSeeder::class,
      MunicipioSeeder::class,
      ZoneSeeder::class,
      SectionSeeder::class,
      TagSeeder::class,
      UserSeeder::class,
      StablishmentSeeder::class,
      StablishmentTagSeeder::class,
      StablishmentJobSeeder::class,
      StablishmentAdSeeder::class,
      AdvertisementsSeeder::class
    ]);
  }
}
