<?php

use DiePHP\DataObject;
use PHPUnit\Framework\TestCase;

class _TestDataObjectRemove extends TestCase
{

    private $container;

    /**
     * Test the `remove` method in the `DataObject` class.
     */
    public function testRemove()
    {
        // Test removal of existing key
        $this->container->remove('key1');
        $this->assertFalse($this->container->has('key1'), 'The "key1" key should not exist after removal.');

        // Test removal of non-existing key
        $this->container->remove('nonExistingKey');
        $this->assertFalse($this->container->has('nonExistingKey'), 'The "nonExistingKey" key should not exist.');

        // Test removal of nested key
        $this->container->set('key3.key4', 'value3');
        $this->assertEquals('value3', $this->container->__get('key3.key4'), 'The "key3.key4" key should exist.');

        $this->container->remove('key3.key4');
        $this->assertFalse($this->container->__isset('key3.key4'), 'The "key3.key4" key should not exist after removal.');
    }

    /**
     * Setup method for each test case.
     */
    protected function setUp() : void
    {
        $this->container = new DataObject(['key1' => 'value1', 'key2' => 'value2']);
    }

}
