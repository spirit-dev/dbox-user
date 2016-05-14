<?php

namespace SpiritDev\Bundle\DBoxUserBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository {

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