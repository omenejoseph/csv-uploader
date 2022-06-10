<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupSqliteDbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sqlite:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup SQLIte db';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!is_dir(database_path('sqlite'))) {
            mkdir(database_path('sqlite'), 0744);
            if (!file_exists(database_path('sqlite/database.sqlite'))) {
                fopen(database_path('sqlite/database.sqlite'), 'w');
            }
        }
    }
}
