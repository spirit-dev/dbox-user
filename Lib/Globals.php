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
 * File           Globals.php
 * Updated the    06/06/16 15:58
 */

namespace SpiritDev\Bundle\DBoxUserBundle\Lib;

/**
 * Class Globals
 * @package SpiritDev\Bundle\DBoxUserBundle\Lib
 */
class Globals {

    /**
     * @var
     */
    protected static $defaultLanguage;

    /**
     * @var
     */
    protected static $defaultRoles;

    /**
     * @return mixed
     */
    public static function getDefaultRoles() {
        return self::$defaultRoles;
    }

    /**
     * @param mixed $defaultRoles
     */
    public static function setDefaultRoles($defaultRoles) {
        self::$defaultRoles = $defaultRoles;
    }

    /**
     * @return mixed
     */
    public static function getDefaultLanguage() {
        return self::$defaultLanguage;
    }

    /**
     * @param $defaultLanguage
     */
    public static function setDefaultLanguage($defaultLanguage) {
        self::$defaultLanguage = $defaultLanguage;
    }

}