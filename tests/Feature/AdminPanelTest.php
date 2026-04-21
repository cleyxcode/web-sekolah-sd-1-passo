<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    public function test_can_access_all_filament_resources()
    {
        $user = User::where('email', 'admin@admin.com')->first();
        $this->actingAs($user);

        $resources = [
            'beritas', 'galeris', 'gurus', 'jadwal-pelajarans', 'kalender-akademiks',
            'kelas', 'mata-pelajarans', 'nilais', 'orang-tuas', 'presensis', 
            'profil-sekolahs', 'rapors', 'setting-sekolahs', 'siswas', 
            'tahun-ajarans', 'users'
        ];

        foreach ($resources as $resource) {
            $response = $this->get('/admin/' . $resource);
            
            if ($response->status() !== 200) {
                echo "\n[ERROR] Failed to load /admin/{$resource}. Status: " . $response->status() . "\n";
                if ($response->exception) {
                    echo "Exception: " . $response->exception->getMessage() . "\n";
                    echo "File: " . $response->exception->getFile() . ":" . $response->exception->getLine() . "\n";
                }
            }
            
            $response->assertStatus(200);
        }
    }
}
