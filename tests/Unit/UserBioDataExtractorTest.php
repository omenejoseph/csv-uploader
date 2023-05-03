
<?php

namespace Tests\Unit;

use App\Helpers\UserBioDataExtractor;
use PHPUnit\Framework\TestCase;

class UserBioDataExtractorTest extends TestCase
{
    protected UserBioDataExtractor $extractor;

    public function setUp(): void
    {
        parent::setUp();
        $this->extractor = new UserBioDataExtractor();
    }

    public function testExtract()
    {
        $input = "Mr John Smith and Mrs Jane Smith";
        $expected = [
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'initial' => '',
                'title' => 'Mrs',
            ],
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'initial' => '',
                'title' => 'Mr',
            ],
        ];

        $result = $this->extractor->extract($input);
        $this->assertEquals($expected, $result);
    }

    public function testExtractTitles()
    {
        $input = "Mr John Smith";
        $expected = "Mr";

        $result = $this->extractor->extractTitles($input);
        $this->assertEquals($expected, $result);
    }

    public function testGenerateParts()
    {
        $input = "John Smith & Jane Smith";
        $expected = [
            'part1' => 'John Smith',
            'part2' => ' Jane Smith',
        ];

        $result = $this->extractor->generateParts($input);
        $this->assertEquals($expected, $result);
    }
}