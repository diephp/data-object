<?php

use DiePHP\DataObject;
use PHPUnit\Framework\TestCase;

class _TestDataObjectCollapse extends TestCase
{

    /**
     * @var DataObject
     */
    private $dataObject;

    /**
     * Test the 'collapse' method of the 'DataObject' class.
     */
    public function testCollapse() : void
    {
        $collapsedDataObject = $this->dataObject->collapse();

        $this->assertTrue($collapsedDataObject->has('name'));
        $this->assertTrue($collapsedDataObject->has('email'));
        $this->assertTrue($collapsedDataObject->has('0'));
        $this->assertTrue($collapsedDataObject->has('1'));

        $this->assertEquals('John Doe', $collapsedDataObject->get('name'));
        $this->assertEquals('john@example.com', $collapsedDataObject->get('email'));
        $this->assertEquals('admin', $collapsedDataObject->get('0'));
        $this->assertEquals('guest', $collapsedDataObject->get('1'));
    }

    /**
     * This method is called before each test.
     */
    protected function setUp() : void
    {
        $this->dataObject = DataObject::of([
            'user'  => [
                'name'  => 'John Doe',
                'email' => 'john@example.com',
            ],
            'roles' => ['admin', 'guest'],
        ]);
    }

}
