<?php

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