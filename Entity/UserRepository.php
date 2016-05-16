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
 * File           UserRepository.php
 * Updated the    15/05/16 11:47
 */

namespace SpiritDev\Bundle\DBoxUserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @package SpiritDev\Bundle\DBoxUserBundle\Entity
 */
class UserRepository extends EntityRepository {

    /**
     * @return mixed
     */
    public function getUsableUsers() {

        $usersNotIn = [
            'sys',
            'admin',
            'centraladmin'
        ];

        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.username NOT IN (:unames)')
            ->setParameter('unames', $usersNotIn);

    }

}