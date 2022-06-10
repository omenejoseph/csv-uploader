<?php

namespace App\Console\Commands;

use App\Helpers\UserBioDataExtractor;
use App\Models\User;
use Illuminate\Console\Command;

class ImportCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $csvPath = $this->ask('What CSV are you uploading', public_path('csv/examples__282_29.csv'));

        $row = 1;
        $extractedData = [];
        $handler = new UserBioDataExtractor;
        if (($handle = fopen($csvPath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row !== 1) {
                    $data = $handler->extract($data[0]);
                    $extractedData[] = $data[0];
                    if (isset($data[1])) {
                        $extractedData[] = $data[1];
                    }
                }
                $row++;
            }
            fclose($handle);
        }

        User::insert($extractedData);
        return 0;
    }
}
