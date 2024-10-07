<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // User::factory(10)->create();

    $useradmin = User::factory()->create([
      'name' => 'Test User',
      'email' => 'test@example.com',
      'username' => '120450016',
      'password' => Hash::make('user01'),
    ]);

    $usermanager = User::factory()->create([
      'name' => 'udin',
      'email' => 'test1@example.com',
      'username' => '121450016',
      'password' => Hash::make('user02'),
    ]);

    $admin = Role::create(['name' => 'admin']);
    $manager = Role::create(['name' => 'manager']);
    $kabag = Role::create(['name' => 'kabag']);
    $kasie = Role::create(['name' => 'kasie']);
    $mandor = Role::create(['name' => 'mandor']);
    $pengamat = Role::create(['name' => 'pengamat']);

    $create = Permission::create(['name' => 'create table']);
    $read = Permission::create(['name' => 'read table']);
    $update = Permission::create(['name' => 'update table']);
    $delete = Permission::create(['name' => 'delete table']);


    $admin->givePermissionTo($create, $read, $update, $delete);
    $manager->givePermissionTo($read);

    $useradmin->assignRole('admin');
    $usermanager->assignRole('manager');
  }
}
