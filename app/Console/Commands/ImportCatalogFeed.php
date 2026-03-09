<?php

namespace App\Console\Commands;

use App\Catalog\Analysis\CatalogHealthAnalyzer;
use App\Catalog\Import\CatalogFeedClient;
use App\Catalog\Import\CatalogImporter;
use Illuminate\Console\Command;

class ImportCatalogFeed extends Command
{
    protected $signature   = 'catalog:import';
    protected $description = 'Importeer de productfeed en analyseer de cataloguskwaliteit';

    public function handle(): int
    {
        $this->info('Feed inladen en analyseren...');

        $importer = new CatalogImporter(
            new CatalogFeedClient(),
            CatalogHealthAnalyzer::standaard(),
        );

        $aantal = $importer->importeer();

        $this->info("Klaar! {$aantal} product(en) geïmporteerd en geanalyseerd.");
        $this->line('Open /admin om het dashboard te bekijken.');

        return self::SUCCESS;
    }
}
