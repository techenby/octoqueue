<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\DeleteUserForm;
use Livewire\Livewire;
use Tests\TestCase;

class DeleteAccountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_accounts_can_be_deleted()
    {
        if (! Features::hasAccountDeletionFeatures()) {
            return $this->markTestSkipped('Account deletion is not enabled.');
        }

        $this->actingAs($user = User::factory()->create());

        $component = Livewire::test(DeleteUserForm::class)
            ->set('password', 'password')
            ->call('deleteUser');

        $this->assertNull($user->fresh());
    }

    /**
     * @test
     */
    public function correct_password_must_be_provided_before_account_can_be_deleted()
    {
        if (! Features::hasAccountDeletionFeatures()) {
            return $this->markTestSkipped('Account deletion is not enabled.');
        }

        $this->actingAs($user = User::factory()->create());

        Livewire::test(DeleteUserForm::class)
            ->set('password', 'wrong-password')
            ->call('deleteUser')
            ->assertHasErrors(['password']);

        $this->assertNotNull($user->fresh());
    }
}
