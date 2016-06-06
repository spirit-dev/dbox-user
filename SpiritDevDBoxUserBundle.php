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
 * File           SpiritDevDBoxUserBundle.php
 * Updated the    06/06/16 15:58
 */

namespace SpiritDev\Bundle\DBoxUserBundle;

use SpiritDev\Bundle\DBoxUserBundle\Lib\Globals;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class SpiritDevDBoxUserBundle
 * @package SpiritDev\Bundle\DBoxUserBundle
 */
class SpiritDevDBoxUserBundle extends Bundle {
    /**
     * @return string
     */
    public function getParent() {
        return 'FOSUserBundle';
    }

    /**
     *
     */
    public function boot() {
        // Set some static globals
        // Usefull for entity injections

        // Set default language
        Globals::setDefaultLanguage($this->container->getParameter('spirit_dev_d_box_user.user_management.default_language'));
        // Set default roles
        Globals::setDefaultRoles($this->container->getParameter('spirit_dev_d_box_user.user_management.default_role'));
    }
}
