<?php

namespace Tests\Unit;

use App\Helpers\UserBioDataExtractor;
use PHPUnit\Framework\TestCase;

class UserBioDataExtractorTest extends TestCase
{
    protected UserBioDataExtractor $bioDataExtractor;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->bioDataExtractor = new UserBioDataExtractor;
    }

    public function test_generate_part_splits_string_correctly()
    {
        $test1 = "A and B";
        $test2 = "A & B";
        $test3 = "A B C";

        $parts1 = $this->bioDataExtractor->generateParts($test1);

        $this->assertEquals(trim($parts1['part1']), 'A');
        $this->assertEquals(trim($parts1['part2']), 'B');

        $parts2 = $this->bioDataExtractor->generateParts($test2);

        $this->assertEquals(trim($parts2['part1']), 'A');
        $this->assertEquals(trim($parts2['part2']), 'B');

        $parts3 = $this->bioDataExtractor->generateParts($test3);

        $this->assertEquals(trim($parts3['part1']), $test3);
        $this->assertEquals(trim($parts3['part2']), '');
    }

    public function test_titles_are_correctly_extracted()
    {
        $test1 = "Mr A";
        $test2 = "Mrs B";
        $test3 = "Prof C";
        $test4 = "A B C";

        $parts1 = $this->bioDataExtractor->extractTitles($test1);

        $this->assertEquals(trim($parts1), 'Mr');

        $parts2 = $this->bioDataExtractor->extractTitles($test2);

        $this->assertEquals(trim($parts2), 'Mrs');

        $parts3 = $this->bioDataExtractor->extractTitles($test3);

        $this->assertEquals(trim($parts3), 'Prof');

        $parts4 = $this->bioDataExtractor->extractTitles($test4);

        $this->assertEquals(trim($parts4), '');
    }

    public function test_helper_class_sets_correct_initials_from_string()
    {
        $test = "Mr J. Smith";

        $data = $this->bioDataExtractor->extract($test);

        $this->assertEquals(count($data), 1);
        $this->assertEquals($data[0]['initial'], 'J');
    }

    public function test_helper_class_sets_correct_titles_from_string()
    {
        $test = "Mr and Mrs J. Smith";
        $data = $this->bioDataExtractor->extract($test);

        $this->assertEquals(count($data), 2);

        $this->assertEquals($data[1]['title'], 'Mr');
        $this->assertEquals($data[0]['title'], 'Mrs');

    }

    public function test_helper_class_sets_correct_first_and_last_names_from_string()
    {

        $test = "Mr John Smith & Mrs Maggie Doe";

        $data = $this->bioDataExtractor->extract($test);

        $this->assertEquals(count($data), 2);

        $this->assertEquals($data[1]['first_name'], 'John');
        $this->assertEquals($data[1]['last_name'], 'Smith');

        $this->assertEquals($data[0]['first_name'], 'Maggie');
        $this->assertEquals($data[0]['last_name'], 'Doe');

    }
}
