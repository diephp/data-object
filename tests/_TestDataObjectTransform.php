<?php

use DiePHP\DataObject;
use PHPUnit\Framework\TestCase;

class _TestDataObjectTransform extends TestCase
{

    /**
     * Test for `transform` method of the `DataObject` class.
     */
    public function testTransformMethod()
    {
        $object = new DataObject([
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
        ]);

        $transformer = function ($data) {
            return [
                'key1' => strtoupper($data['key1']),
                'key2' => strtoupper($data['key2']),
                'key3' => strtoupper($data['key3']),
            ];
        };

        $object->transform($transformer);

        $this->assertSame([
            'key1' => 'VALUE1',
            'key2' => 'VALUE2',
            'key3' => 'VALUE3',
        ], $object->toArray());
    }

}
