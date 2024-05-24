<?php

use DiePHP\DataObject;
use PHPUnit\Framework\TestCase;

class _TestDataObjectMap extends TestCase
{

    /**
     * The DataObject instance for the test.
     * @var DataObject
     */
    private $dataObject;

    /**
     * This tests the map() method of the DataObject class.
     * Given a set of keys and a function, this method applies the function to the values of the DataObject that
     * corresponds with the keys, and transforms the DataObject. This is tested with various keys and functions.
     */
    public function testMapMethod() : void
    {
        // Test with valid keys and function
        $keys = ['first', 'second'];
        $function = fn($value) => $value * 2;
        $resulting_object = $this->dataObject->map($keys, $function);
        $this->assertEquals(['first' => 2, 'second' => 4, 'third' => 3], $resulting_object->toArray(), 'Test with valid keys and a valid function did not pass.');

        // Test with all keys
        $keys = '*';
        $function = fn($value) => $value + 1;
        $resulting_object = $this->dataObject->map($keys, $function);
        $this->assertEquals(['first' => 2, 'second' => 3, 'third' => 4], $resulting_object->toArray(), 'Test with wildcard key and a valid function did not pass.');

        // Test with non-existing keys
        $keys = ['none'];
        $function = fn($value) => $value - 1;
        $resulting_object = $this->dataObject->map($keys, $function);
        $this->assertEquals(['first' => 1, 'second' => 2, 'third' => 3], $resulting_object->toArray(), 'Test with non-existing keys and a valid function did not pass.');
    }

    /**
     * This method is called before each test.
     */
    protected function setUp() : void
    {
        $this->dataObject = new DataObject(['first' => 1, 'second' => 2, 'third' => 3]);
    }

}
