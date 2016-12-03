<?php
require_once __DIR__ . "/vendor/autoload.php";

/*
--- Day 1: No Time for a Taxicab ---

Santa's sleigh uses a very high-precision clock to guide its movements, and the clock's oscillator is regulated by stars. Unfortunately, the stars have been stolen... by the Easter Bunny. To save Christmas, Santa needs you to retrieve all fifty stars by December 25th.

Collect stars by solving puzzles. Two puzzles will be made available on each day in the advent calendar; the second puzzle is unlocked when you complete the first. Each puzzle grants one star. Good luck!

You're airdropped near Easter Bunny Headquarters in a city somewhere. "Near", unfortunately, is as close as you can get - the instructions on the Easter Bunny Recruiting Document the Elves intercepted start here, and nobody had time to work them out further.

The Document indicates that you should start at the given coordinates (where you just landed) and face North. Then, follow the provided sequence: either turn left (L) or right (R) 90 degrees, then walk forward the given number of blocks, ending at a new intersection.

There's no time to follow such ridiculous instructions on foot, though, so you take a moment and work out the destination. Given that you can only walk on the street grid of the city, how far is the shortest path to the destination?

For example:

    Following R2, L3 leaves you 2 blocks East and 3 blocks North, or 5 blocks away.
    R2, R2, R2 leaves you 2 blocks due South of your starting position, which is 2 blocks away.
    R5, L5, R5, R3 leaves you 12 blocks away.

How many blocks away is Easter Bunny HQ?

The first half of this puzzle is complete! It provides one gold star: *
--- Part Two ---

Then, you notice the instructions continue on the back of the Recruiting Document. Easter Bunny HQ is actually at the first location you visit twice.

For example, if your instructions are R8, R4, R4, R8, the first location you visit twice is 4 blocks away, due East.

How many blocks away is the first location you visit twice?
*/
final class Day1
{
	/**
	 * @param string $input
	 *
	 * @return World
	 */
	public static function main($input) {
		$data = explode(', ', $input);
        $world = new World();
		foreach ($data as $cell) {
            $world->turn($cell);
		}

		return $world;
	}
}

class World
{
    /**
     * @var Coordinate
     */
    private $coordinate;

    /**
     * @var Direction
     */
    private $currentDirection;

    /**
     * @var array
     */
    private $locationsVisited = [];

    /**
     * @var Coordinate The first location visited twice
     */
    private $firstLocationVisitedTwice;

    public function __construct()
    {
        $this->coordinate = new Coordinate(0, 0);
        $this->currentDirection = new North(0);
    }

    public function turn($string)
    {
        $direction = substr($string, 0, 1);
        $number = substr($string, 1);
        $this->currentDirection = $this->currentDirection->turn($direction, $number);
        $this->currentDirection->updateWorld($this);
    }

    /**
     * @return int
     */
    public function distance()
    {
        return $this->coordinate->distance();
    }

    /**
     * @return Coordinate|null
     */
    public function firstVisitedTwice()
    {
        return $this->firstLocationVisitedTwice;
    }

    public function addX($x)
    {
        for($i = 0; $i < $x; $i++) {
            $this->updateCoordinate($this->coordinate->addX(1));
        }
    }

    public function subtractX($x)
    {
        for($i = 0; $i < $x; $i++) {
            $this->updateCoordinate($this->coordinate->subtractX(1));
        }
    }

    public function addY($y)
    {
        for($i = 0; $i < $y; $i++) {
            $this->updateCoordinate($this->coordinate->addY(1));
        }
    }

    public function subtractY($y)
    {
        for($i = 0; $i < $y; $i++) {
            $this->updateCoordinate($this->coordinate->subtractY(1));
        }
    }

    /**
     * @param Coordinate $coordinate
     */
    private function updateCoordinate(Coordinate $coordinate)
    {
        $this->coordinate = $coordinate;
        $this->addToHistory($coordinate);
        $this->visitCoordinate($coordinate);
    }

    private function addToHistory(Coordinate $location)
    {
        if (! isset($this->locationsVisited[$location->toString()])) {
            $this->locationsVisited[$location->toString()] = 0;
        }
        $this->locationsVisited[$location->toString()]++;
    }

    /**
     * @param Coordinate $location
     */
    private function visitCoordinate(Coordinate $location)
    {
        if (null !== $this->firstLocationVisitedTwice) {
            return;
        }

        if ($this->locationsVisited[$location->toString()] == 2) {
            $this->firstLocationVisitedTwice = $location;
        }
    }
}

class Coordinate
{
    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * @param int $x
     * @param int $y
     */
    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @param int $x
     *
     * @return Coordinate
     */
    public function addX($x)
    {
        return new self($this->x + $x, $this->y);
    }

    /**
     * @param int $y
     *
     * @return Coordinate
     */
    public function addY($y)
    {
        return new self($this->x, $this->y + $y);
    }

    /**
     * @param int $x
     *
     * @return Coordinate
     */
    public function subtractX($x)
    {
        return new self($this->x - $x, $this->y);
    }

    /**
     * @param int $y
     *
     * @return Coordinate
     */
    public function subtractY($y)
    {
        return new self($this->x, $this->y - $y);
    }

    /**
     * @return int
     */
    public function distance()
    {
        return abs($this->x) + abs($this->y);
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->x . ',' . $this->y;
    }
}

abstract class Direction
{
    /**
     * @var int
     */
    protected $distance;

    /**
     * @param int $distance
     */
    public function __construct($distance)
    {
        $this->distance = (int) $distance;
    }

    /**
     * @param World $world
     */
    public abstract function updateWorld(World $world);

    /**
     * @param string $side
     * @param int $number
     *
     * @return Direction
     */
    public abstract function turn($side, $number);
}

class North extends Direction
{
    /**
     * @param World $world
     */
    public function updateWorld(World $world)
    {
        $world->addY($this->distance);
    }

    /**
     * @param string $side
     * @param int $number
     *
     * @return Direction
     */
    public function turn($side, $number)
    {
        return ($side == 'R') ? new East($number) : new West($number);
    }
}

class South extends Direction
{
    /**
     * @param World $world
     */
    public function updateWorld(World $world)
    {
        $world->subtractY($this->distance);
    }

    /**
     * @param string $side
     * @param int $number
     *
     * @return Direction
     */
    public function turn($side, $number)
    {
        return ($side == 'R') ? new West($number) : new East($number);
    }
}

class East extends Direction
{
    /**
     * @param World $world
     */
    public function updateWorld(World $world)
    {
        $world->addX($this->distance);
    }

    /**
     * @param string $side
     * @param int $number
     *
     * @return Direction
     */
    public function turn($side, $number)
    {
        return ($side == 'R') ? new South($number) : new North($number);
    }
}

class West extends Direction
{
    /**
     * @param World $world
     */
    public function updateWorld(World $world)
    {
        $world->subtractX($this->distance);
    }

    /**
     * @param string $side
     * @param int $number
     *
     * @return Direction
     */
    public function turn($side, $number)
    {
        return ($side == 'R') ? new North($number) : new South($number);
    }
}
