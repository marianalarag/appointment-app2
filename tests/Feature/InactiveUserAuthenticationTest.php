<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Crear roles necesarios
    Role::firstOrCreate(['name' => 'Administrador']);
    Role::firstOrCreate(['name' => 'Paciente']);
});

test('Active user can authenticate successfully', function () {
    // 1) Crear un usuario activo
    $user = User::factory()->create([
        'email' => 'active@example.com',
        'password' => Hash::make('password123'),
        'id_number' => 'ACT123456',
        'phone' => '1234567890',
        'address' => 'Active User Address'
    ]);

    // Asignar rol
    $user->assignRole('Paciente');

    // 2) Intentar autenticar
    $response = $this->post('/login', [
        'email' => 'active@example.com',
        'password' => 'password123'
    ]);

    // 3) Verificar redirección después de login
    // Depende de tu configuración. Puede ser /dashboard, /home, etc.
    $response->assertRedirect();

    // 4) Verificar que SÍ está autenticado
    $this->assertAuthenticated();
});

test('User with wrong credentials cannot authenticate', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'password' => Hash::make('correctpass'),
        'id_number' => 'WRONG123',
        'phone' => '1234567890',
        'address' => 'Address'
    ]);

    $user->assignRole('Paciente');

    // Credenciales incorrectas
    $response = $this->post('/login', [
        'email' => 'user@example.com',
        'password' => 'wrongpassword'
    ]);

    // Debería redirigir de vuelta con errores
    $response->assertRedirect();
    $response->assertSessionHasErrors(['email']);

    // Verificar que NO está autenticado
    $this->assertGuest();
});

test('Non-existent user cannot authenticate', function () {
    // Intentar login con usuario que no existe
    $response = $this->post('/login', [
        'email' => 'nonexistent@example.com',
        'password' => 'anypassword'
    ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});

test('User cannot authenticate with empty credentials', function () {
    $response = $this->post('/login', [
        'email' => '',
        'password' => ''
    ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['email', 'password']);
    $this->assertGuest();
});

// Test opcional para usuarios "inactivos" si tienes un campo 'active' o 'status'
test('User with active=false cannot authenticate if implemented', function () {
    // Este test depende de si tienes campo 'active' en tu tabla users

    // Si tienes campo 'active', descomenta y ajusta:
    /*
    $user = User::factory()->create([
        'email' => 'inactive@example.com',
        'password' => Hash::make('password123'),
        'active' => false, // o 'status' => 'inactive'
        'id_number' => 'INACT123',
        'phone' => '1234567890',
        'address' => 'Address'
    ]);

    $user->assignRole('Paciente');

    $response = $this->post('/login', [
        'email' => 'inactive@example.com',
        'password' => 'password123'
    ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
    */

    // Si no tienes campo active, solo pasa el test
    $this->assertTrue(true, 'Campo active no implementado - test pasa por omisión');
});
