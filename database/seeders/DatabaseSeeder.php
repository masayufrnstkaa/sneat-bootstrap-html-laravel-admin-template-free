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
      'name' => 'Masayu Franstika',
      'email' => 'test@example.com',
      'username' => 'admin01',
      'password' => Hash::make('admin01'),
    ]);

    $usermanager = User::factory()->create([
      'name' => 'tata',
      'email' => 'manager@example.com',
      'username' => 'manager',
      'password' => Hash::make('manager01'),
    ]);

    $userkabag = User::factory()->create([
      'name' => 'nana',
      'email' => 'kabag1@example.com',
      'username' => 'kabag01',
      'password' => Hash::make('kabag01'),
    ]);

    $userkasie = User::factory()->create([
      'name' => 'rara',
      'email' => 'kasie1@example.com',
      'username' => 'kasie01',
      'password' => Hash::make('kasie01'),
    ]);

    $usermandor = User::factory()->create([
      'name' => 'udin',
      'email' => 'mandor1@example.com',
      'username' => 'mandor01',
      'password' => Hash::make('mandor01'),
    ]);

    $userpengamat = User::factory()->create([
      'name' => 'Pengamat QCPP',
      'email' => 'pengamat1@example.com',
      'username' => 'pengamat01',
      'password' => Hash::make('pengamat01'),
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
    $kabag->givePermissionTo($read);
    $kasie->givePermissionTo($read);
    $mandor->givePermissionTo($read);
    $pengamat->givePermissionTo($read);

    $useradmin->assignRole('admin');
    $usermanager->assignRole('manager');
    $userkabag->assignRole('kabag');
    $userkasie->assignRole('kasie');
    $usermandor->assignRole('mandor');
    $userpengamat->assignRole('pengamat');
  }
}
