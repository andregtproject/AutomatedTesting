<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisTest extends DuskTestCase
{
    use RefreshDatabase;

    public function user_can_register_successfully()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                   ->type('name', 'Test User')
                   ->type('email', 'test@example.com')
                   ->type('password', 'password123')
                   ->type('password_confirmation', 'password123')
                   ->press('REGISTER')
                   ->assertPathIs('/dashboard')
                   ->assertSee('Dashboard');
        });
    }
}
