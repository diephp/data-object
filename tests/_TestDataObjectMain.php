<?php

use DiePHP\DataObject;
use PHPUnit\Framework\TestCase;

/**
 * Class DataObject3Test.
 * This class tests the findKey method of the DataObject class
 * of the DiePHP namespace.
 * The findKey method is used to find the key associated with a
 * given value or function in a DataObject.
 */
class _TestDataObjectMain extends TestCase
{

    /**
     * Test to verify the findKey method when searching with a value.
     */
    public function test_findKey_with_value()
    {
        $dataObject = new DataObject(['key' => 'value', 'anotherKey' => 'anotherValue']);

        $key = $dataObject->findKey('anotherValue');

        $this->assertSame('anotherKey', $key);
    }

    /**
     * Test to verify the findKey method when searching with a function.
     */
    public function test_findKey_with_function()
    {
        $dataObject = new DataObject(['key' => 'value', 'anotherKey' => 'anotherValue']);

        $key = $dataObject->findKey(function ($value, $key) {
            return $key === 'anotherKey';
        });

        $this->assertSame('anotherKey', $key);
    }

    /**
     * Test to verify the findKey method when the key does not exist.
     */
    public function test_findKey_when_key_does_not_exist()
    {
        $dataObject = new DataObject(['key' => 'value', 'anotherKey' => 'anotherValue']);

        $key = $dataObject->findKey('notExistingValue');

        $this->assertNull($key);
    }

}
