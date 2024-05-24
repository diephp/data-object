<?php

use DiePHP\DataObject;
use PHPUnit\Framework\TestCase;

/**
 * Class DataObjectTest
 * @package DiePHP
 * This test class is used to validate the behavior of DiePHP\DataObject class.
 */
class _TestDataObjectSerialize extends TestCase
{

    /**
     * This test aims to validate the serialization process of the DataObject class.
     */
    public function testSerialize()
    {
        // Create a DataObject instance
        $dataObject = new DataObject(['testKey' => 'testValue']);

        // Call __serialize on the instance
        $serializedArray = $dataObject->__serialize();

        // Assert that the serialized array has the correct content
        $this->assertIsArray($serializedArray);
        $this->assertArrayHasKey('testKey', $serializedArray);
        $this->assertEquals('testValue', $serializedArray['testKey']);
    }

}
