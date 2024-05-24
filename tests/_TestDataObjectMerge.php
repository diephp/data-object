<?php

use DiePHP\DataObject;
use PHPUnit\Framework\TestCase;

class _TestDataObjectMerge extends TestCase
{

    /**
     * Test the "merge" method of the DataObject class using PHPUnit.
     */
    public function testMerge()
    {
        $dataObject = new DataObject(['a' => 1, 'b' => 2]);

        // Test for normal merging.
        $dataObject->merge(['b' => 3, 'c' => 4]);
        $this->assertEquals([
            'a' => 1,
            'b' => 3,
            'c' => 4,
        ], $dataObject->toArray());

        // Test for override merging.
        $dataObject->merge(['c' => 5]);
        $this->assertEquals([
            'a' => 1,
            'b' => 3,
            'c' => 5,
        ], $dataObject->toArray());

        // Test for deep merging.
        $dataObject->merge(['d' => ['e' => 6]]);
        $this->assertEquals([
            'a' => 1,
            'b' => 3,
            'c' => 5,
            'd' => ['e' => 6],
        ], $dataObject->toArray());
    }

}
