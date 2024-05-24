<?php

use DiePHP\DataObject;
use PHPUnit\Framework\TestCase;

class _TestDataObjectEither extends TestCase
{

    /**
     * A DataObject instance.
     * @var DataObject
     */
    private DataObject $dataObject;

    /**
     * Test the `either` method.
     * The `either` method receives an array of keys and returns the value of the
     * first key that has a non-null value. If none of the keys have a non-null
     * value, the method returns the default value.
     */
    public function testEither()
    {
        $keys = ['forthKey', 'firstKey', 'secondKey'];
        $this->assertEquals('firstValue', $this->dataObject->either($keys));

        $keys = ['forthKey', 'thirdKey', 'fifthKey'];
        $this->assertNull($this->dataObject->either($keys));

        $keys = ['forthKey', 'thirdKey', 'fifthKey'];
        $defaultValue = 'default';
        $this->assertEquals($defaultValue, $this->dataObject->either($keys, $defaultValue));
    }

    /**
     * This method is called before each test.
     */
    protected function setUp() : void
    {
        // Initialize the DataObject instance.
        $this->dataObject = new DataObject([
            'firstKey'  => 'firstValue',
            'secondKey' => 'secondValue',
            'thirdKey'  => null,
        ]);
    }

    /**
     * This method is called after each test.
     */
    protected function tearDown() : void
    {
        // Unset the DataObject instance.
        unset($this->dataObject);
    }

}
