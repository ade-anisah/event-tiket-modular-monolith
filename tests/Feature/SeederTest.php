<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_menghasilkan_data_sesuai_rancangan(): void
    {
        $this->seed();

        $this->assertDatabaseCount('users', 4);
        $this->assertDatabaseCount('categories', 3);
        $this->assertDatabaseCount('events', 3);
        $this->assertDatabaseCount('category_event', 4);
        $this->assertDatabaseCount('ticket_types', 4);
        $this->assertDatabaseCount('participants', 3);
        $this->assertDatabaseCount('registrations', 0);
        $this->assertDatabaseCount('tickets', 0);
        $this->assertDatabaseHas('events', ['id' => 3, 'status' => 'draft']);
    }
}
