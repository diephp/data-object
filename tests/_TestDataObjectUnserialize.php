<?php

use DiePHP\DataObject;
use PHPUnit\Framework\TestCase;

/**
 * Class DataObject2Test
 * This class tests the __unserialize method in the DataObject class.
 * The __unserialize method should correctly restore the state of initial object from serialized data.
 * @package DiePHP
 */
class _TestDataObjectUnserialize extends TestCase
{

    /**
     * Test case for the method __unserialize. The object should be correctly restored to the
     * state it was in when serialized.
     */
    public function testUnserializeMethod()
    {
        $dataObj = new DataObject(['key' => 'value', 'zeroKey' => null]);

        $serializedData = serialize($dataObj);
        $unserializedDataObj = unserialize($serializedData);

        $this->assertNotSame($dataObj, $unserializedDataObj);
        $this->assertEquals($dataObj->toArray(), $unserializedDataObj->toArray());
    }

}
