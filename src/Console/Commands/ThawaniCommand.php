<?php

namespace Ialtoobi\Thawani\Console\Commands;

use Exception;
use Ialtoobi\Thawani\ThawaniManager;
use Illuminate\Console\Command;

class ThawaniCommand extends Command
{
    protected $signature = 'make:thawani';
    protected $description = 'Execute Thawani operations.';
    protected $createdMethods = [];

    public function handle()
    {
        $this->info('Starting Thawani operation...');

        // try {
        //     $response = ThawaniManager::retrieveCustomer('cus_kNkOXHqx5XctnDdb');
        //     // Assuming $response is an array or Collection that can be converted to JSON
        //     $this->info('Response: ' . json_encode($response, JSON_PRETTY_PRINT));
        // } catch (Exception $e) {
        //     $this->error("Error: " . $e->getMessage());
        //     // Optionally log the error as well
        //     // Log::error($e);
        // }
    }
}
