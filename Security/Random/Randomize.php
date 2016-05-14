<?php

namespace SpiritDev\Bundle\DBoxUserBundle\Security\Random;

/**
 * Class Randomize
 * @package SpiritDev\Bundle\DBoxUserBundle\Security\Random
 */
class Randomize {

    /**
     * @param int $length ; default 10; manual length; null equiv to random seed
     * @return string
     */
    public static function generateRandomString($length = 10) {

        // If length is null random seed
        if ($length == null) {
            $length = rand(0, 32);
        }
        // Setting available characters
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // Initialiazing variables
        $characterLength = strlen($characters);
        $randomString = '';
        // Randomizing
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $characterLength - 1)];
        }

        // retun result
        return $randomString;
    }

}