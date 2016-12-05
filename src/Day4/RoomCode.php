<?php

namespace Day4;

final class RoomCode
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $sectorId;

    /**
     * @var string
     */
    private $checksum;

    /**
     * @param string $name
     * @param int $sectorId
     * @param string $checksum
     */
    public function __construct($name, $sectorId, $checksum)
    {
        \Assert\Assertion::string($name);
        \Assert\Assertion::integerish($sectorId);
        \Assert\Assertion::string($checksum);

        $this->name = $name;
        $this->sectorId = $sectorId;
        $this->checksum = $checksum;
    }

    public function isRealRoom()
    {
        /**
         * A room is real if the checksum is the five most common letters in the encrypted name
         * in order, with ties broken by alphabetization.
         */
        $letters = str_replace('-', '', $this->name);

        $letterCount = [];
        foreach (str_split($letters) as $letter) {
            if (! isset($letterCount[$letter])) {
                $letterCount[$letter] = 0;
            }

            $letterCount[$letter] ++;
        }
        arsort($letterCount);

        var_dump($letterCount);


    }

    /**
     * @param string $string
     *
     * @return RoomCode
     */
    public static function fromString($string)
    {
        $name = substr($string, 0, strrpos($string, '-'));
        $sum = str_replace(['[', ']'], '', substr($string, strpos($string, '[')));
        $sector = str_replace([$name, $sum, '[', '-', ']'], '', $string);

        return new self($name, $sector, $sum);
    }
}
