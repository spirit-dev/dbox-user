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
 * File           LdapDriverInterface.php
 * Updated the    15/05/16 11:47
 */

namespace SpiritDev\Bundle\DBoxUserBundle\Ldap;

use SpiritDev\Bundle\DBoxUserBundle\Entity\User;

/**
 * Interface LdapDriverInterface
 * @package SpiritDev\Bundle\DBoxUserBundle\Ldap
 */
interface LdapDriverInterface {

    /**
     * Create LDAP User
     * @param $userDn
     * @param $newUserInfo
     * @return mixed
     */
    public function ldapCreateUser($userDn, $newUserInfo);

    /**
     * Modify LDAP User
     * @param User $user
     * @return mixed
     */
    public function ldapModifyUser(User $user);

    /**
     * Update LDAP user password
     * @param User $user
     * @param $password
     * @return mixed
     */
    public function ldapUpdatePassword(User $user, $password);

    /**
     * Verify LDAP User password with given one
     * @param User $user
     * @param $password
     * @return mixed
     */
    public function ldapVerifyPassword(User $user, $password);

    /**
     * Remove LDAP User
     * @param User $user
     * @return mixed
     */
    public function ldapRemoveUser(User $user);

    /**
     * Lock user LDAP Account
     * @param User $user
     * @return mixed
     */
    public function ldapLockAccount(User $user);

    /**
     * Unlock user LDAP Account
     * @param User $user
     * @return mixed
     */
    public function ldapUnlockAccount(User $user);

}