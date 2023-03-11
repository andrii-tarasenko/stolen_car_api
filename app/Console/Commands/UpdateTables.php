<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $update = new UpdateParamsCarController();
        $update->updateModelAndMakes();
    }
}
