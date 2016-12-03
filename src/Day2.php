<?php
/**
 * This file is part of the advent-code-2016 project.
 *
 * (c) Yannick Voyer <star.yvoyer@gmail.com> (http://github.com/yvoyer)
 */

/*
--- Day 2: Bathroom Security ---

You arrive at Easter Bunny Headquarters under cover of darkness. However, you left in such a rush that you forgot to
use the bathroom! Fancy office buildings like this one usually have keypad locks on their bathrooms, so you search the
front desk for the code.

"In order to improve security," the document you find says, "bathroom codes will no longer be written down. Instead,
please memorize and follow the procedure below to access the bathrooms."

The document goes on to explain that each button to be pressed can be found by starting on the previous button and
moving to adjacent buttons on the keypad: U moves up, D moves down, L moves left, and R moves right. Each line of
instructions corresponds to one button, starting at the previous button (or, for the first line, the "5" button);
press whatever button you're on at the end of each line. If a move doesn't lead to a button, ignore it.

You can't hold it much longer, so you decide to figure out the code as you walk to the bathroom. You picture a keypad
like this:

1 2 3
4 5 6
7 8 9

Suppose your instructions are:

ULL
RRDDD
LURDL
UUUUD

    You start at "5" and move up (to "2"), left (to "1"), and left (you can't, and stay on "1"), so the first button
is 1.
    Starting from the previous button ("1"), you move right twice (to "3") and then down three times (stopping at "9"
after two moves and ignoring the third), ending up with 9.
    Continuing from "9", you move left, up, right, down, and left, ending with 8.
    Finally, you move up four times (stopping at "2"), then down once, ending with 5.

So, in this example, the bathroom code is 1985.

Your puzzle input is the instructions from the document you found at the front desk. What is the bathroom code?

--- Part Two ---

You finally arrive at the bathroom (it's a several minute walk from the lobby so visitors can behold the many fancy
conference rooms and water coolers on this floor) and go to punch in the code. Much to your bladder's dismay, the
keypad is not at all like you imagined it. Instead, you are confronted with the result of hundreds of man-hours of
bathroom-keypad-design meetings:

    1
  2 3 4
5 6 7 8 9
  A B C
    D

You still start at "5" and stop when you're at an edge, but given the same instructions as above, the outcome is very
 different:

    You start at "5" and don't move at all (up and left are both edges), ending at 5.
    Continuing from "5", you move right twice and down three times (through "6", "7", "B", "D", "D"), ending at D.
    Then, from "D", you move five more times (through "D", "B", "C", "C", "B"), ending at B.
    Finally, after five more moves, you end at 3.

So, given the actual keypad layout, the code would be 5DB3.
 */
final class Day2
{
    /**
     * @param string $string
     * @param Keypad $pad
     *
     * @return int The code
     */
    public static function decodeCode($string, Keypad $pad)
    {
        $lines = explode("\n", $string);
        $previousDigit = new Digit(5);
        $digits = [];
        foreach ($lines as $sequence) {
            foreach (str_split($sequence) as $char) {
                switch ($char) {
                    case 'U';
                        $previousDigit = $previousDigit->up($pad);
                        break;

                    case 'D';
                        $previousDigit = $previousDigit->down($pad);
                        break;

                    case 'R';
                        $previousDigit = $previousDigit->right($pad);
                        break;

                    case 'L';
                        $previousDigit = $previousDigit->left($pad);
                        break;
                }
            }
            $digits[] = $previousDigit;
        }

        $resultArray = array_map(
            function(Digit $digit) {
                return $digit->toString();
            },
            $digits
        );

        return implode('', $resultArray);
    }
}

class Digit
{
    /**
     * @var int
     */
    private $current;

    /**
     * @param int $digit
     * @throws InvalidArgumentException
     */
    public function __construct($digit)
    {
        $this->current = $digit;
    }

    /**
     * @return int
     */
    public function toString()
    {
        return $this->current;
    }

    /**
     * @param KeyPad $keyPad
     *
     * @return Digit
     */
    public function up(KeyPad $keyPad)
    {
        if ($keyPad->allowedToUp($this)) {
            return $keyPad->nextUp($this);
        }

        return $this;
    }

    /**
     * @param KeyPad $keyPad
     *
     * @return Digit
     */
    public function down(KeyPad $keyPad)
    {
        if ($keyPad->allowedToDown($this)) {
            return $keyPad->nextDown($this);
        }

        return $this;
    }

    /**
     * @param KeyPad $keyPad
     *
     * @return Digit
     */
    public function right(KeyPad $keyPad)
    {
        if ($keyPad->allowedToRight($this)) {
            return $keyPad->nextRight($this);
        }

        return $this;
    }

    /**
     * @param KeyPad $keyPad
     *
     * @return Digit
     */
    public function left(KeyPad $keyPad)
    {
        if ($keyPad->allowedToLeft($this)) {
            return $keyPad->nextLeft($this);
        }

        return $this;
    }
}

abstract class KeyPad
{
    /**
     * @var Key[]
     */
    protected $keys = [];

    /**
     * @param Digit $digit
     *
     * @return Digit
     */
    public function nextUp(Digit $digit)
    {
        return $this->keys[$digit->toString()]->up();
    }

    /**
     * @param Digit $digit
     *
     * @return Digit
     */
    public function nextDown(Digit $digit)
    {
        return $this->keys[$digit->toString()]->down();
    }

    /**
     * @param Digit $digit
     *
     * @return Digit
     */
    public function nextRight(Digit $digit)
    {
        return $this->keys[$digit->toString()]->right();
    }

    /**
     * @param Digit $digit
     *
     * @return Digit
     */
    public function nextLeft(Digit $digit)
    {
        return $this->keys[$digit->toString()]->left();
    }

    /**
     * @param Digit $digit
     *
     * @return bool
     */
    public function allowedToUp(Digit $digit)
    {
        return $this->keys[$digit->toString()]->canUp();
    }

    /**
     * @param Digit $digit
     *
     * @return bool
     */
    public function allowedToDown(Digit $digit)
    {
        return $this->keys[$digit->toString()]->canDowm();
    }

    /**
     * @param Digit $digit
     *
     * @return bool
     */
    public function allowedToRight(Digit $digit)
    {
        return $this->keys[$digit->toString()]->canRight();
    }

    /**
     * @param Digit $digit
     *
     * @return bool
     */
    public function allowedToLeft(Digit $digit)
    {
        return $this->keys[$digit->toString()]->canLeft();
    }
}
/**
1 2 3
4 5 6
7 8 9
*/
class NineDigitKeyPad extends KeyPad
{
    public function __construct()
    {
        $this->keys[1] = Key::create(1)->allowDown(4)->allowRight(2);
        $this->keys[2] = Key::create(2)->allowLeft(1)->allowRight(3)->allowDown(5);
        $this->keys[3] = Key::create(3)->allowLeft(2)->allowDown(6);
        $this->keys[4] = Key::create(4)->allowUp(1)->allowRight(5)->allowDown(7);
        $this->keys[5] = Key::create(5)->allowUp(2)->allowRight(6)->allowDown(8)->allowLeft(4);
        $this->keys[6] = Key::create(6)->allowUp(3)->allowLeft(5)->allowDown(9);
        $this->keys[7] = Key::create(7)->allowUp(4)->allowRight(8);
        $this->keys[8] = Key::create(8)->allowLeft(7)->allowUp(5)->allowRight(9);
        $this->keys[9] = Key::create(9)->allowLeft(8)->allowUp(6);
    }
}

/**
    1
  2 3 4
5 6 7 8 9
  A B C
    D
 */
class StarShapedKeyPad extends KeyPad
{
    public function __construct()
    {
        $this->keys[1] = Key::create(1)->allowDown(3);
        $this->keys[2] = Key::create(2)->allowDown(6)->allowRight(3);
        $this->keys[3] = Key::create(3)->allowUp(1)->allowRight(4)->allowDown(7)->allowLeft(2);
        $this->keys[4] = Key::create(4)->allowDown(8)->allowLeft(3);
        $this->keys[5] = Key::create(5)->allowRight(6);
        $this->keys[6] = Key::create(6)->allowUp(2)->allowRight(7)->allowDown('A')->allowLeft(5);
        $this->keys[7] = Key::create(7)->allowUp(3)->allowRight(8)->allowDown('B')->allowLeft(6);
        $this->keys[8] = Key::create(8)->allowUp(4)->allowRight(9)->allowDown('C')->allowLeft(7);
        $this->keys[9] = Key::create(9)->allowLeft(8);
        $this->keys['A'] = Key::create('A')->allowUp(6)->allowRight('B');
        $this->keys['B'] = Key::create('B')->allowUp(7)->allowRight('C')->allowDown('D')->allowLeft('A');
        $this->keys['C'] = Key::create('C')->allowUp(8)->allowLeft('B');
        $this->keys['D'] = Key::create('D')->allowUp('B');
    }
}

class Key
{
    /**
     * @var int|string
     */
    private $current;

    /**
     * @var int|null
     */
    private $right;

    /**
     * @var int|null
     */
    private $left;

    /**
     * @var int|null
     */
    private $up;

    /**
     * @var int|null
     */
    private $down;

    private function __construct($current)
    {
        $this->current = $current;
    }

    /**
     * @return Digit
     */
    public function up()
    {
        return new Digit($this->up);
    }

    /**
     * @return Digit
     */
    public function down()
    {
        return new Digit($this->down);
    }

    /**
     * @return Digit
     */
    public function right()
    {
        return new Digit($this->right);
    }

    /**
     * @return Digit
     */
    public function left()
    {
        return new Digit($this->left);
    }

    /**
     * @return bool
     */
    public function canUp()
    {
        return isset($this->up);
    }

    /**
     * @return bool
     */
    public function canDowm()
    {
        return isset($this->down);
    }

    /**
     * @return bool
     */
    public function canRight()
    {
        return isset($this->right);
    }

    /**
     * @return bool
     */
    public function canLeft()
    {
        return isset($this->left);
    }

    /**
     * @param $number
     *
     * @return Key
     */
    public function allowDown($number)
    {
        $this->down = $number;

        return $this;
    }

    /**
     * @param $number
     *
     * @return Key
     */
    public function allowUp($number)
    {
        $this->up = $number;

        return $this;
    }

    /**
     * @param $number
     *
     * @return Key
     */
    public function allowRight($number)
    {
        $this->right = $number;

        return $this;
    }

    /**
     * @param $number
     *
     * @return Key
     */
    public function allowLeft($number)
    {
        $this->left = $number;

        return $this;
    }

    /**
     * @param $current
     *
     * @return Key
     */
    public static function create($current)
    {
        return new self($current);
    }
}
