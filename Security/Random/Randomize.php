<?php
/**
 * Copyright (c) 2016. Spirit-Dev
 * Licensed under GPLv3 GNU License - http://www.gnu.org/licenses/gpl-3.0.html
 *    _             _
 *   /_`_  ._._/___/ | _
 * . _//_//// /   /_.'/_'|/
 *    /
 *    
 * Since 2K10 until today
 *  
 * Hex            53 70 69 72 69 74 2d 44 65 76
 *  
 * By             Jean Bordat
 * Twitter        @Ji_Bay_
 * Mail           <bordat.jean@gmail.com>
 *  
 * File           Randomize.php
 * Updated the    15/05/16 11:47
 */

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