<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Crear roles en español como en tu seeder
    Role::firstOrCreate(['name' => 'Administrador']);
    Role::firstOrCreate(['name' => 'Paciente']);
    Role::firstOrCreate(['name' => 'Doctor']); // Si existe
});

test('Cannot delete role assigned to users', function () {
    // 1) Crear rol de prueba
    $role = Role::create(['name' => 'Rol de Prueba']);

    // 2) Crear usuario
    $user = User::factory()->create();

    // 3) Asignar el rol al usuario
    $user->assignRole($role);

    // 4) Autenticar como administrador (en español)
    $admin = User::factory()->create();
    $adminRole = Role::where('name', 'Administrador')->first();
    $admin->assignRole($adminRole);
    $this->actingAs($admin);

    // 5) Intentar eliminar el rol
    $response = $this->delete(route('admin.roles.destroy', $role));

    // 6) Verificar que redirige (status 302)
    $response->assertStatus(302);

    // 7) Verificar que hay mensaje SWAL (como en tu UserController)
    // Tu controlador usa session()->flash('swal')
    $response->assertSessionHas('swal');

    // Verificar contenido del mensaje SWAL si quieres
    $swal = session('swal');
    if ($swal) {
        $this->assertEquals('error', $swal['icon'] ?? null);
    }

    // 8) Verificar que el rol sigue en la base de datos
    $this->assertDatabaseHas('roles', [
        'id' => $role->id,
        'name' => 'Rol de Prueba'
    ]);
});

test('Can delete role not assigned to users', function () {
    // 1) Crear un rol temporal que no esté asignado
    $role = Role::create(['name' => 'Rol Temporal']);

    // 2) Autenticar como administrador
    $admin = User::factory()->create();
    $adminRole = Role::where('name', 'Administrador')->first();
    $admin->assignRole($adminRole);
    $this->actingAs($admin);

    // 3) Intentar eliminar el rol
    $response = $this->delete(route('admin.roles.destroy', $role));

    // 4) Verificar que redirige (status 302)
    $response->assertStatus(302);

    // 5) Verificar mensaje SWAL de éxito
    $response->assertSessionHas('swal');

    $swal = session('swal');
    if ($swal) {
        $this->assertEquals('success', $swal['icon'] ?? null);
    }

    // 6) Verificar que el rol ya no está en la base de datos
    $this->assertDatabaseMissing('roles', [
        'id' => $role->id
    ]);
});
