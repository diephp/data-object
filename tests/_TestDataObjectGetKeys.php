<?php

use DiePHP\DataObject;
use PHPUnit\Framework\TestCase;

class _TestDataObjectGetKeys extends TestCase
{

    /**
     * Test for the getKeys() method in the DataObject class.
     * This method is expected to return all the keys from the DataObject container
     */
    public function testGetKeys() : void
    {
        $dataObject = new DataObject(['name' => 'John', 'age' => 30]);

        $this->assertSame(['name', 'age'], $dataObject->getKeys());
    }

    /**
     * Test for the getKeys() method on an empty DataObject class.
     * This method is expected to return an empty array when the DataObject container is empty
     */
    public function testGetKeysOnEmptyObject() : void
    {
        $dataObject = new DataObject([]);

        $this->assertSame([], $dataObject->getKeys());
    }

}
