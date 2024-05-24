<?php

use DiePHP\DataObject;
use PHPUnit\Framework\TestCase;

class _TestDataObject extends TestCase
{

    /**
     * @test
     * @group DataObjectOfMethod
     */
    public function it_can_create_instance_from_assoc_array()
    {
        $assocArray = ['key' => 'value'];
        $dataObject = DataObject::of($assocArray);

        $this->assertInstanceOf(DataObject::class, $dataObject);
        $storedArray = $dataObject->toArray();
        $this->assertSame($assocArray, $storedArray);
    }

    /**
     * @test
     * @group DataObjectOfMethod
     */
    public function it_can_create_instance_from_other_data_object()
    {
        $assocArray = ['key' => 'value'];
        $dataObjectOne = DataObject::of($assocArray);
        $dataObjectTwo = DataObject::of($dataObjectOne);

        $this->assertEquals($dataObjectOne, $dataObjectTwo);
    }

    /**
     * @test
     * @group DataObjectOfMethod
     */
    public function it_throws_exception_on_non_assoc_array()
    {
        $this->expectException(LogicException::class);

        $array = ['valueOne', 'valueTwo'];
        DataObject::of($array);
    }

    public function testGetMethod()
    {
        $dataObj = new DataObject(['key1' => 'value1', 'key2' => ['subkey1' => 'subvalue1']]);

        $this->assertSame('value1', $dataObj->get('key1'));
        $this->assertSame(null, $dataObj->get('key3'));
        $this->assertSame('subvalue1', $dataObj->get('key2.subkey1'));
        $this->assertSame(null, $dataObj->get('key2.subkey2'));
    }

}
