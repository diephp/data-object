<?php

use DiePHP\DataObject;
use PHPUnit\Framework\TestCase;

class _TestDataObjectHash extends TestCase
{

    /**
     * @test
     */
    public function testHashMethod()
    {
        $dataObject = new DataObject(["name" => "John Doe", "age" => 30]);

        $hash = $dataObject->hash();

        // Make sure the hash is a string:
        $this->assertIsString($hash);

        // Make sure the hash is 32 characters long (standard MD5 hash):
        $this->assertEquals(32, strlen($hash));
    }

}
