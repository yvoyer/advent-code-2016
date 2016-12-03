<?php

final class Day1Test extends PHPUnit_Framework_TestCase {
    /**
     * @param $expected
     * @param $input
     * @param $visitedTwice
     *
     * @dataProvider provideInputs
     */
    public function test_input($expected, $input, $visitedTwice)
    {
        $world = Day1::main($input);
        $this->assertSame($expected, $world->distance());
        if ($visitedTwice) {
            $this->assertInstanceOf(Coordinate::class, $world->firstVisitedTwice());
            $this->assertSame($visitedTwice, $world->firstVisitedTwice()->distance());
        } else {
            $this->assertNull($world->firstVisitedTwice());
        }
    }

    public static function provideInputs()
    {
        return [
            [5, "R2, L3", null],
            [2, "R2, R2, R2", null],
            [12, "R5, L5, R5, R3", null],
            [0, "L5, L5, L5, L5", 0],
            [7, "R2, R5, R2, R3, R5", 4],
            [291, "R3, L5, R2, L2, R1, L3, R1, R3, L4, R3, L1, L1, R1, L3, R2, L3, L2, R1, R1, L1, R4, L1, L4, R3, L2, L2, R1, L1, R5, R4, R2, L5, L2, R5, R5, L2, R3, R1, R1, L3, R1, L4, L4, L190, L5, L2, R4, L5, R4, R5, L4, R1, R2, L5, R50, L2, R1, R73, R1, L2, R191, R2, L4, R1, L5, L5, R5, L3, L5, L4, R4, R5, L4, R4, R4, R5, L2, L5, R3, L4, L4, L5, R2, R2, R2, R4, L3, R4, R5, L3, R5, L2, R3, L1, R2, R2, L3, L1, R5, L3, L5, R2, R4, R1, L1, L5, R3, R2, L3, L4, L5, L1, R3, L5, L2, R2, L3, L4, L1, R1, R4, R2, R2, R4, R2, R2, L3, L3, L4, R4, L4, L4, R1, L4, L4, R1, L2, R5, R2, R3, R3, L2, L5, R3, L3, R5, L2, R3, R2, L4, L3, L1, R2, L2, L3, L5, R3, L1, L3, L4, L3", 159]
        ];
    }
}
