<?php
/**
 * This file is part of the advent-code-2016 project.
 *
 * (c) Yannick Voyer <star.yvoyer@gmail.com> (http://github.com/yvoyer)
 */

/*
--- Day 3: Squares With Three Sides ---

Now that you can think clearly, you move deeper into the labyrinth of hallways and office furniture that makes up this part of Easter Bunny HQ. This must be a graphic design department; the walls are covered in specifications for triangles.

Or are they?

The design document gives the side lengths of each triangle it describes, but... 5 10 25? Some of these aren't triangles. You can't help but mark the impossible ones.

In a valid triangle, the sum of any two sides must be larger than the remaining side. For example, the "triangle" given above is impossible, because 5 + 10 is not larger than 25.

In your puzzle input, how many of the listed triangles are possible?

--- Part Two ---

Now that you've helpfully marked up their design documents, it occurs to you that triangles are specified in groups of three vertically. Each set of three numbers in a column specifies a triangle. Rows are unrelated.

For example, given the following specification, numbers with the same hundreds digit would be part of the same triangle:

101 301 501
102 302 502
103 303 503
201 401 601
202 402 602
203 403 603

In your puzzle input, and instead reading by columns, how many of the listed triangles are possible?

 */
final class Day3
{
    /**
     * @param string $input
     *
     * @return Triangle[] The valid triangles
     */
    public static function partOne($input)
    {
        $lines = explode("\n", $input);
        $valids = [];
        foreach ($lines as $triangleData) {
            $triangle = Triangle::forPartOne($triangleData);
            if ($triangle->isValid()) {
                $valids[] = $triangle;
            }
        }

        return $valids;
    }

    /**
     * @param string $input
     * @throws RuntimeException
     *
     * @return Triangle[]
     */
    public static function partTwo($input)
    {
        $lines = explode("\n", $input);
        $matrix = [];
        foreach ($lines as $columns) {
            $digits = explode(' ', $columns);
            $digits = array_map(
                function ($string) {
                    return trim($string);
                },
                $digits
            );
            $digits = array_values(
                array_filter(
                    $digits,
                    function ($string) {
                        return is_numeric($string);
                    }
                )
            );
            if (count($digits) !== 3) {
                throw new \RuntimeException('Columns could not be parsed, ' . $columns);
            }

            $matrix[] = $digits;
        }

        $triangles = [];
        $position = 0;
        while (isset($matrix[$position])) {
            $triangles[] = Triangle::fromString(
                $matrix[$position][0],
                $matrix[$position + 1][0],
                $matrix[$position + 2][0]
            );
            $triangles[] = Triangle::fromString(
                $matrix[$position][1],
                $matrix[$position + 1][1],
                $matrix[$position + 2][1]
            );
            $triangles[] = Triangle::fromString(
                $matrix[$position][2],
                $matrix[$position + 1][2],
                $matrix[$position + 2][2]
            );

            $position += 3;
        }

        return array_filter(
            $triangles,
            function (Triangle $triangle) {
                return $triangle->isValid();
            }
        );
    }
}

class Triangle
{
    /**
     * @var int
     */
    private $sideA;

    /**
     * @var int
     */
    private $sideB;

    /**
     * @var int
     */
    private $sideC;

    public function __construct($sideA, $sideB, $sideC)
    {
        \Assert\Assertion::integer($sideA);
        \Assert\Assertion::integer($sideB);
        \Assert\Assertion::integer($sideC);

        $this->sideA = $sideA;
        $this->sideB = $sideB;
        $this->sideC = $sideC;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $valid = false;
        if (($this->sideB + $this->sideC) > $this->sideA) {
            if (($this->sideC + $this->sideA) > $this->sideB) {
                if (($this->sideA + $this->sideB) > $this->sideC) {
                    $valid = true;
                }
            }
        }

        return $valid;
    }

    public function __toString()
    {
        return json_encode(['a' => $this->sideA, 'b' => $this->sideB, 'c' => $this->sideC]);
    }

    /**
     * @param int $a
     * @param int $b
     * @param int $c
     *
     * @return Triangle
     */
    public static function fromString($a, $b, $c)
    {
        \Assert\Assertion::integerish($a);
        \Assert\Assertion::integerish($b);
        \Assert\Assertion::integerish($c);
        return new self((int) $a, (int) $b, (int) $c);
    }

    /**
     * @param string $string
     *
     * @return Triangle
     * @throws RuntimeException
     */
    public static function forPartOne($string)
    {
        $sides = explode(' ', $string);

        // trim all sides
        $sides = array_map(
            function ($string) {
                return trim($string);
            },
            $sides
        );

        // Remove all empty
        $sides = array_filter(
            $sides,
            function ($string) {
                return is_numeric($string);
            }
        );

        // cast to int
        $sides = array_map(
            function ($string) {
                return (int) $string;
            },
            $sides
        );

        // Reindex
        $sides = array_values($sides);

        if (count($sides) != 3) {
            throw new \RuntimeException('Parsing of triangle was not ok.');
        }

        return new self($sides[0], $sides[1], $sides[2]);
    }
}
