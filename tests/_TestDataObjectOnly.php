<?php

use DiePHP\DataObject;
use PHPUnit\Framework\TestCase;

class _TestDataObjectOnly extends TestCase
{

    /**
     * A unit test to validate the "only" method in the "DataObject" class.
     * The "only" method is assumed to only keep the passed keys from the object it's invoked upon.
     * This test will add some key-value pairs in a DataObject instance, then keep only a selected few of the keys
     * using the "only" method. It will then use PHPUnit asserts to assert that only the keys that are supposed to be
     * kept are available in the new object.
     */

    public function testOnlyMethod()
    {
        $originalKeys = ['first_key', 'second_key', 'third_key'];
        $values = ['first_value', 'second_value', 'third_value'];
        $dataObject = new DataObject(array_combine($originalKeys, $values));
        $keysToKeep = ['first_key', 'third_key'];

        $newDataObject = $dataObject->only($keysToKeep);

        $this->assertInstanceOf(DataObject::class, $newDataObject, "Method doesn't return a DataObject instance");

        foreach ($keysToKeep as $key) {
            $this->assertTrue($newDataObject->has($key), "Key '{$key}' is supposed to exist in the new DataObject");
        }

        foreach (array_diff($originalKeys, $keysToKeep) as $key) {
            $this->assertFalse($newDataObject->has($key), "Key '{$key}' is not supposed to exist in the new DataObject");
        }
    }

}
