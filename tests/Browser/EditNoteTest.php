<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Note;

class EditNoteTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_edit_a_note()
    {
        // Create a user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create a note for the user
        $note = Note::create([
            'judul' => 'Original Note Title',
            'isi' => 'Original note description',
            'penulis_id' => $user->id,
        ]);

        // Log in and edit the note
        $this->browse(function (Browser $browser) use ($note) {
            $browser->visit('/')
                    ->clickLink('Log in')
                    ->assertPathIs('/login')
                    ->type('email', 'test@example.com')
                    ->type('password', 'password')
                    ->press('LOG IN')
                    ->assertPathIs('/dashboard')
                    ->clickLink('Notes')
                    ->assertPathIs('/notes')
                    ->click('@edit-' . $note->id)
                    ->assertPathIs('/edit-note-page/' . $note->id)
                    ->assertInputValue('title', 'Original Note Title')
                    ->assertValue('textarea[name="description"]', 'Original note description')
                    ->clear('title')
                    ->type('title', 'Updated Note Title')
                    ->clear('description')
                    ->type('description', 'Updated note description')
                    ->press('UPDATE')
                    ->assertPathIs('/notes')
                    ->assertSee('Note has been updated');
        });
    }
}