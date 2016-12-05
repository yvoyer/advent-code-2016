<?php

namespace Star;

use Day4\RoomCode;

final class Day4Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideStringToDecode
     */
    public function test_it_should_decode_string_to_code(RoomCode $expected, $string)
    {
        $this->assertEquals($expected, RoomCode::fromString($string));
    }

    public static function provideStringToDecode()
    {
        return [
            [new RoomCode('a-b-c', 123, 'sum'), 'a-b-c-123[sum]'],
            [new RoomCode('ads-beq-cdfsaf-fdsf-gfdgf', 12213321321, 'sadsadsdsadsadsa'), 'ads-beq-cdfsaf-fdsf-gfdgf-12213321321[sadsadsdsadsadsa]'],
        ];
    }

    /**
     * @dataProvider provideValidationInput
     * @param bool $expected
     * @param string $code
     */
    public function test_it_should_tell_whether_code_is_real_room($expected, $code)
    {
        $this->assertSame($expected, RoomCode::fromString($code)->isRealRoom());
    }

    public static function provideValidationInput()
    {
        return [
            [true, 'aaaaa-bbb-z-y-x-123[abxyz]'],
            [true, 'a-b-c-d-e-f-g-h-987[abcde]'],
            [true, 'not-a-real-room-404[oarel]'],
            [false, 'totally-real-room-200[decoy]'],
        ];
    }
}
