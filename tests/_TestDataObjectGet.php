<?php

declare(strict_types=1);

use DiePHP\DataObject;
use PHPUnit\Framework\TestCase;

/**
 * Class DataObject11Test
 * @requires PHPUnit >=8.0
 * Here we test the class DataObject, specifically the getValues method.
 */
final class _TestDataObjectGet extends TestCase
{

    public function testGetValues() : void
    {
        $data = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
            'key4' => 'value4',
        ];

        $dataObject = new DataObject($data);

        $actual = $dataObject->getValues();
        $expected = array_values($data);

        self::assertSame($expected, $actual, 'Values obtained from getValues are not what is expected.');
    }

}
