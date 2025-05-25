<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Note;

class DeleteNoteTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_delete_a_note()
    {
        // Create a user    
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create a note for the user
        $note = Note::create([
            'judul' => 'Catatan yang akan dihapus',
            'isi' => 'Ini adalah catatan yang akan dihapus saat testing',
            'penulis_id' => $user->id,
        ]);

        // Log in and delete the note
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
                    ->assertSee($note->judul) // Verifikasi bahwa catatan ada
                    ->click('#delete-' . $note->id) // Klik tombol delete
                    ->assertPathIs('/notes')
                    ->assertDontSee($note->judul); // Verifikasi catatan sudah tidak ada(terhapus)
        });
    }
}