<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

uses(RefreshDatabase::class);

test('Un usuario no puede eliminarse a sí mismo', function () {

    //1) Crrear un usuario de prueba
    $user = User::factory()->create();

    //2) Simulamos que ya inició sesión
    $this->actingAs($user, 'web');

    //3) Simulamos una petición HTTP DELETE
    $response = $this->delete(route('admin.users.destroy', $user));

    //4) Esperamos que el servidor prohiba la acción (403 Forbidden)
    $response->assertStatus(403);

    //5) Verificar que el usuario sigue existiendo en BD
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
    ]);
});
