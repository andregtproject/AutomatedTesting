<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Note;

class ShowNoteTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_view_note_details()
    {
        // Create a user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create a note for the user
        $note = Note::create([
            'judul' => 'Test Note Title',
            'isi' => 'Test note description content',
            'penulis_id' => $user->id,
        ]);

        // Log in and view the note details
        $this->browse(function (Browser $browser) use ($note, $user) {
            $browser->visit('/')
                    ->clickLink('Log in')
                    ->assertPathIs('/login')
                    ->type('email', 'test@example.com')
                    ->type('password', 'password')
                    ->press('LOG IN')
                    ->assertPathIs('/dashboard')
                    ->clickLink('Notes')
                    ->assertPathIs('/notes')
                    ->click('@detail-' . $note->id)
                    ->assertPathIs('/note/' . $note->id)
                    ->assertSee($note->judul)
                    ->assertSee($note->isi)
                    ->assertSee($user->name);
        });
    }
}