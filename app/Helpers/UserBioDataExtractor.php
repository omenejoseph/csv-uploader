<?php

namespace App\Helpers;


class UserBioDataExtractor
{
    protected string $firstName = '';
    protected string $lastName= '';
    protected string $initial= '';
    protected string $title = '';
    protected array $titles = ['Mrs', 'Mr', 'Dr', 'Engr', 'Prof', 'Mister', 'Miss', 'Ms'];
    protected string $stringValue = '';

    public function extract(string $value) : array
    {
        $parts = $this->generateParts($value);
        $data = [];

        if (trim($parts['part2']) != '') {
            $data[] = $this->extractData($parts['part2'], $parts['part1']);
        }

        if (trim($parts['part1']) != '') {
            $data[] = $this->extractData($parts['part1'], $parts['part2']);
        }

        return $data;
    }

    private function extractData(string $string, ?string $sub = null): array
    {
        $first_name = '';
        $initial = '';
        $last_name = '';
        $title = '';

        if (trim($string) !== '') {
            $title = $this->extractTitles($string);

            $string = str_replace($title, '', $string);

            if (trim($string) == '') {
                $sub_title = $this->extractTitles($sub);
                $string = str_replace($sub_title, '', $sub);
            }

            $string = str_replace('.', '', $string);

            $parts = explode(' ', trim($string));


            foreach ($parts as $key => $part) {
                if ($key == 0 && strlen($part) != 1 && count($parts) != 1) {
                    $first_name = $part;
                } elseif (strlen($part) == 1) {
                    $initial = $part;
                } else {
                    $last_name .= $part;
                }
            }
        }


        return compact('first_name', 'last_name', 'initial', 'title');
    }



    public function extractTitles(string $string):? string
    {
        foreach ($this->titles as $title) {
            if (str_contains($string, $title)) {
                return $title;
            }
        }
        return null;
    }


    public function generateParts(string $string): array
    {
        $conjuctors = ['&', 'and'];
        $part1 = $string;
        $part2 = '';

        foreach ($conjuctors as $conjuctor) {
            if (str_contains($string, $conjuctor)) {
                $exploded = explode($conjuctor, $string);

                $part1 = $exploded[0];
                $part2 = $exploded[1];
                break;
            }
        }

        return compact('part1', 'part2');
    }

}
