<?php

namespace Azuriom\Plugin\Review\Commands;

use Azuriom\Plugin\Review\Services\ReviewImporter;
use Azuriom\Plugin\Review\Sources\SourceManager;
use Illuminate\Console\Command;
use Throwable;

class ImportReviewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'review:import {--source= : The domain of the listing to import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the reviews published on the configured server listings';

    public function handle(SourceManager $sources, ReviewImporter $importer): int
    {
        $domain = $this->option('source');

        if ($domain !== null) {
            $source = $sources->get($domain);

            if ($source === null || ! $source->isSupported()) {
                $this->error("Unknown review source: {$domain}");

                return self::FAILURE;
            }

            $selected = [$source];
        } else {
            $selected = $sources->enabled();
        }

        $status = self::SUCCESS;

        foreach ($selected as $source) {
            try {
                $count = $importer->import($source);

                $this->info("{$source->name()}: {$count} reviews imported.");
            } catch (Throwable $e) {
                $this->error("{$source->name()}: {$e->getMessage()}");

                $status = self::FAILURE;
            }
        }

        return $status;
    }
}
