<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;

class LogoutTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_logout()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            // Login
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('LOG IN')
                    ->assertPathIs('/dashboard');

            // Logout
            $browser->click('.inline-flex.items-center.px-3.py-2.border.border-transparent') // Klik tombol dropdown logout
                    ->clickLink('Log Out'); // Klik link Log Out
        

            // Verifikasi bahwa pengguna telah berhasil logout
            $browser->visit('/login')
                    ->assertSee('LOG IN'); // Memastikan bahwa halaman login muncul kembali setelah berhasil logout
        });
    }
}