<?php

use DiePHP\DataObject;
use PHPUnit\Framework\TestCase;

class _TestDataObjectFlatten extends TestCase
{

    /**
     * @var DataObject
     */
    private $dataObject;

    public function setUp() : void
    {
        $this->dataObject = new DataObject();
    }

    /**
     * Test that `flatten` method flattens the container properly.
     * Assume that the `flatten` method is successful if after running the method, no elements in the container are
     * arrays.
     */

    public function testFlatten()
    {
        $this->dataObject->merge([
            'key1' => [
                'keyA' => 'valueA',
                'keyB' => 'valueB',
            ],
            'key2' => [
                'keyC' => 'valueC',
                'keyD' => 'valueD',
            ],
        ]);

        $newObject = $this->dataObject->flatten();
        $values = $newObject->getValues();

        foreach ($values as $value) {
            $this->assertIsNotArray($value);
        }

        $this->assertEquals([
            'key1.keyA' => 'valueA',
            'key1.keyB' => 'valueB',
            'key2.keyC' => 'valueC',
            'key2.keyD' => 'valueD',
        ],
            $newObject->toArray()
        );

    }

}
