<?php

use DiePHP\DataObject;
use PHPUnit\Framework\TestCase;

class _TestDataObjectFilter extends TestCase
{

    /**
     * Test case for `filter` method in `DataObject` class.
     * This method is expected to filter the elements in the container based on a given callback function
     */
    public function testCanFilterDataObject() : void
    {
        $dataObject = new DataObject(['name' => 'John Doe', 'age' => 30, 'email' => '']);

        // Test filters that removes empty elements
        $filteredDataObjectEmpty = $dataObject->clone()->filter(function ($value) {
            return !empty($value);
        });
        $this->assertEquals(['name' => 'John Doe', 'age' => 30], $filteredDataObjectEmpty->toArray());

        // Test filters that removes numeric elements
        $filteredDataObjectNotNumeric = $dataObject->clone()->filter(function ($value) {
            return !is_numeric($value);
        });
        $this->assertEquals(['name' => 'John Doe', 'email' => ''], $filteredDataObjectNotNumeric->toArray());

        // Test filters that keeps only string elements
        $filteredDataObjectOnlyString = $dataObject->clone()->filter(function ($value) {
            return is_string($value);
        });
        $this->assertEquals(['name' => 'John Doe', 'email' => ''], $filteredDataObjectOnlyString->toArray());
    }

}
