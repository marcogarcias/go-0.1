<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('users')->insert([
      'name'=>'erwin',
      'email'=>'erwin@email.com',
      'password'=>'$2y$10$SvmXZHbynVbKOEtI7qGLKu4R.UlGZsubKeGT6movgcEiSYEuZgvy.',
      'zone_id'=>1,
      'profile_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);

    DB::table('users')->insert([
      'name'=>'marco',
      'email'=>'marco@email.com',
      'password'=>'$2y$10$SvmXZHbynVbKOEtI7qGLKu4R.UlGZsubKeGT6movgcEiSYEuZgvy.',
      'zone_id'=>1,
      'profile_id'=>1,
      'deleted'=>0,
      'created_at'=>date('Y-m-d h:i:s'),
      'updated_at'=>date('Y-m-d h:i:s')
    ]);
  }
}
