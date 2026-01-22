<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'Administrador']);
    Role::firstOrCreate(['name' => 'Paciente']);
    Role::firstOrCreate(['name' => 'Doctor']);
});

test('User must provide current password when changing email', function () {
    $user = User::factory()->create([
        'name' => 'Usuario Original',
        'email' => 'original@example.com',
        'password' => Hash::make('CurrentPass123!'),
        'id_number' => 'ABC123456',
        'phone' => '1234567890',
        'address' => 'Dirección original'
    ]);

    $userRole = Role::where('name', 'Paciente')->first();
    $user->assignRole($userRole);
    $this->actingAs($user);

    $response = $this->put(route('admin.users.update', $user), [
        'name' => 'Usuario Original',
        'email' => 'nuevo@example.com', // Email DIFERENTE
        'id_number' => 'ABC123456',
        'phone' => '1234567890',
        'address' => 'Dirección original',
        'role_id' => $userRole->id,
        // SIN current_password - debería fallar
    ]);

    // DEBUG: Ver qué errores hay
    // dd(session('errors'));

    $response->assertSessionHasErrors(['current_password']);
    $this->assertDatabaseHas('users', ['id' => $user->id, 'email' => 'original@example.com']);
});

test('User can change email with correct current password', function () {
    $user = User::factory()->create([
        'name' => 'Usuario Test',
        'email' => 'old@example.com',
        'password' => Hash::make('MyCurrentPass123'),
        'id_number' => 'XYZ789012',
        'phone' => '1234567890',
        'address' => 'Dirección actual'
    ]);

    $userRole = Role::where('name', 'Paciente')->first();
    $user->assignRole($userRole);
    $this->actingAs($user);

    $response = $this->put(route('admin.users.update', $user), [
        'name' => 'Nombre Actualizado',
        'email' => 'newemail@example.com',
        'id_number' => 'XYZ789012',
        'phone' => '1234567890',
        'address' => 'Nueva Dirección',
        'role_id' => $userRole->id,
        'current_password' => 'MyCurrentPass123'
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('swal');
    $this->assertDatabaseHas('users', ['id' => $user->id, 'email' => 'newemail@example.com']);
});

test('User cannot change email with incorrect current password', function () {
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('CorrectPass123'),
        'id_number' => 'ID123456',
        'phone' => '1234567890',
        'address' => 'Test Address'
    ]);

    $userRole = Role::where('name', 'Paciente')->first();
    $user->assignRole($userRole);
    $this->actingAs($user);

    $response = $this->put(route('admin.users.update', $user), [
        'name' => 'Test User',
        'email' => 'changed@example.com',
        'id_number' => 'ID123456',
        'phone' => '1234567890',
        'address' => 'Test Address',
        'role_id' => $userRole->id,
        'current_password' => 'WrongPassword123'
    ]);

    $response->assertSessionHasErrors(['current_password']);
    $this->assertDatabaseHas('users', ['id' => $user->id, 'email' => 'test@example.com']);
});

test('User can update other fields without current password if email stays same', function () {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'same@example.com',
        'password' => Hash::make('SomePassword123'),
        'id_number' => 'TEST12345',
        'phone' => '1234567890',
        'address' => 'Old Address'
    ]);

    $userRole = Role::where('name', 'Paciente')->first();
    $user->assignRole($userRole);
    $this->actingAs($user);

    // Email IGUAL - no necesita current_password
    $response = $this->put(route('admin.users.update', $user), [
        'name' => 'Nuevo Nombre',
        'email' => 'same@example.com', // MISMO email
        'id_number' => 'TEST12345',
        'phone' => '0987654321',
        'address' => 'Nueva Dirección',
        'role_id' => $userRole->id,
        // No current_password needed
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('swal');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'email' => 'same@example.com',
        'address' => 'Nueva Dirección',
        'phone' => '0987654321'
    ]);
});

test('Current password validation when changing password field', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'password' => Hash::make('OldPassword123'),
        'id_number' => 'PASS12345',
        'phone' => '1234567890',
        'address' => 'Address'
    ]);

    $userRole = Role::where('name', 'Paciente')->first();
    $user->assignRole($userRole);
    $this->actingAs($user);

    // Cambiar contraseña SIN current_password
    $response = $this->put(route('admin.users.update', $user), [
        'name' => $user->name,
        'email' => 'user@example.com', // Mismo email
        'id_number' => 'PASS12345',
        'phone' => '1234567890',
        'address' => 'Address',
        'role_id' => $userRole->id,
        'password' => 'NewPassword123',
        'password_confirmation' => 'NewPassword123',
        // SIN current_password - ¿debería fallar?
    ]);

    // Depende de tu implementación. Si quieres que current_password
    // sea requerido también para cambiar password, ajusta la regla
    $response->assertRedirect(); // O assertSessionHasErrors
});
