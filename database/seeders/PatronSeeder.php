<?php

namespace Database\Seeders;

use App\Models\Patron;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatronSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Patron::factory()->times(10)->create();
    }
}
