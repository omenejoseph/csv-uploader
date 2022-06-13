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

        $saveData = $this->ask("Do you want to save this data to the database", 'yes');

        if ($saveData == 'yes') {
            User::insert($extractedData);
        }


        $shouldDisplayData = $this->ask("Do you want to see the extracted data on this console?", 'yes');

        if ($shouldDisplayData == 'yes') {
            $this->info('------------------------------------------------------------------------------------');
            $this->info('| Title                | First Name           | Initial              | Last Name     ');
            $this->info('-------------------------------------------------------------------------------------');

            foreach ($extractedData as $datum) {
                $this->generateNewRowString($datum);
                $this->info('------------------------------------------------------------------------------------');
            }
        }
        return 0;
    }

    private function generateNewRowString(array $data): void
    {
        $title = $this->getValueSpecifiedString($data['title']);
        $firstName = $this->getValueSpecifiedString($data['first_name']);
        $initial = $this->getValueSpecifiedString($data['initial']);
        $lastName = $this->getValueSpecifiedString($data['last_name']);

        $this->info("{$title}{$firstName}{$initial}{$lastName}");
    }

    private function getValueSpecifiedString($value): string
    {
        $maxLengthOfColumn = 22;
        $valueLength = strlen($value);
        $diff = $maxLengthOfColumn - $valueLength;
        $repeated = str_repeat(" ", $diff);

        return "|$value$repeated";
    }
}
