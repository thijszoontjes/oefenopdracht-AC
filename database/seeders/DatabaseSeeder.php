<?php

namespace Database\Seeders;

use App\Catalog\Analysis\CatalogHealthAnalyzer;
use App\Catalog\Import\CatalogFeedClient;
use App\Catalog\Import\CatalogImporter;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Demoaccount voor Filament-login
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        // Productfeed inladen en analyseren
        $importer = new CatalogImporter(
            new CatalogFeedClient(),
            CatalogHealthAnalyzer::standaard(),
        );

        $aantal = $importer->importeer();

        $this->command->info("Feed geïmporteerd: {$aantal} producten.");
    }
}
