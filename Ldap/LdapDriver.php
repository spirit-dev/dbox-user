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
 * File           LdapDriver.php
 * Updated the    07/06/16 17:44
 */

namespace SpiritDev\Bundle\DBoxUserBundle\Ldap;

use SpiritDev\Bundle\DBoxUserBundle\Entity\User;
use Zend\Ldap\ErrorHandler;

/**
 * Class LdapDriver
 * @package SpiritDev\Bundle\DBoxUserBundle\Ldap
 */
class LdapDriver extends LdapDriverCore implements LdapDriverInterface {


    /**
     * Create LDAP User
     * @param $userDn
     * @param $newUserInfo
     * @return mixed
     */
    public function ldapCreateUser($userDn, $newUserInfo) {
        // Initialiazing ldap connection
        $ldapInitialisation = $this->ldapInit();

        $issue = null;

        if ($ldapInitialisation) {
            // Creating user
            ErrorHandler::start(E_WARNING);
            $issue = ldap_add($this->ldapLinkIdentifier, $userDn, $newUserInfo);
            ErrorHandler::stop();
            // Closing ldap connection
            ldap_close($this->ldapLinkIdentifier);
        }

        return $issue;
    }

    /**
     * Modify LDAP User
     * @param User $user
     * @return mixed
     */
    public function ldapModifyUser(User $user) {
        $ldapInitialisation = $this->ldapInit();

        $issue = null;

        if ($ldapInitialisation) {
            $modifiedInfos = [
                'mail' => $user->getEmail(),
                'sn' => $user->getFirstName(),
                'givenName' => $user->getLastName(),
                'cn' => $user->getFirstName() . ' ' . $user->getLastName()
            ];

            $issue = ldap_modify($this->ldapLinkIdentifier, $user->getDn(), $modifiedInfos);
        }
        ldap_close($this->ldapLinkIdentifier);

        return $issue;
    }

    /**
     * Update LDAP user password
     * @param User $user
     * @param $password
     * @return mixed
     */
    public function ldapUpdatePassword(User $user, $password) {
        $issue = null;

        // initialize ldap connection
        $ldapInitialisation = $this->ldapInit();

        if ($ldapInitialisation) {
            // Generating encoded SSHA Password
            // Salt comming from FOS user DB
            $encodedPassword = '{SSHA}' . base64_encode(sha1($password . $user->getSalt(), TRUE) . $user->getSalt());

            $modifiedInfos = [
                'userPassword' => $encodedPassword,
            ];

            $issue = ldap_modify($this->ldapLinkIdentifier, $user->getDn(), $modifiedInfos);
            ldap_close($this->ldapLinkIdentifier);
        }

        return $issue;
    }

    /**
     * Verify LDAP User password with given one
     * @param User $user
     * @param $password
     * @return mixed
     */
    public function ldapVerifyPassword(User $user, $password) {

        $this->ldapLinkIdentifier = $this->ldapConnect();

        if (!$this->ldapLinkIdentifier) {
            die('Could not connect to {' . $this->ldapHostname . ':' . $this->ldapPort . '}');
        } else {
            ErrorHandler::start(E_WARNING);
            $this->ldapBind = ldap_bind($this->ldapLinkIdentifier, $user->getDn(), $password);
            ErrorHandler::stop();
        }

        return $this->ldapBind;
    }

    /**
     * Remove LDAP User
     * @param User $user
     * @return mixed
     */
    public function ldapRemoveUser(User $user) {
        // Initialiazing ldap connection
        $ldapInitialisation = $this->ldapInit();

        $issue = null;

        if ($ldapInitialisation) {
            // Creating user
            ErrorHandler::start(E_WARNING);
            $issue = ldap_delete($this->ldapLinkIdentifier, $user->getDn());

            ErrorHandler::stop();
            // Closing ldap connection
            ldap_close($this->ldapLinkIdentifier);
        }

        return $issue;
    }

    /**
     * Lock user LDAP Account
     * @param User $user
     * @return mixed
     */
    public function ldapLockAccount(User $user) {
        $ldapInitialization = $this->ldapInit();

        $issue = null;

        if ($ldapInitialization) {

            $ldapSearch = ldap_search($this->ldapLinkIdentifier, $this->baseDn, 'uid=' . $user->getUsername());
            $ldapUser = ldap_get_entries($this->ldapLinkIdentifier, $ldapSearch);
            $ldapUserPassword = $ldapUser[0]['userpassword'][0];

            $ldapUserNewPassword = substr($ldapUserPassword, 0, 6) . "!" . substr($ldapUserPassword, 6, strlen($ldapUserPassword));

            $modifiedInfos = [
                'userPassword' => $ldapUserNewPassword,
            ];
            $issue = ldap_modify($this->ldapLinkIdentifier, $user->getDn(), $modifiedInfos);
        }
        ldap_close($this->ldapLinkIdentifier);

        return $issue;
    }

    /**
     * Unlock user LDAP Account
     * @param User $user
     * @return mixed
     */
    public function ldapUnlockAccount(User $user) {
        $ldapInitialization = $this->ldapInit();

        $issue = null;

        if ($ldapInitialization) {

            $ldapSearch = ldap_search($this->ldapLinkIdentifier, $this->baseDn, 'uid=' . $user->getUsername());
            $ldapUser = ldap_get_entries($this->ldapLinkIdentifier, $ldapSearch);
            $ldapUserPassword = $ldapUser[0]['userpassword'][0];

            $ldapUserNewPassword = substr($ldapUserPassword, 0, 6) . substr($ldapUserPassword, 7, strlen($ldapUserPassword));

            $modifiedInfos = [
                'userPassword' => $ldapUserNewPassword,
            ];
            $issue = ldap_modify($this->ldapLinkIdentifier, $user->getDn(), $modifiedInfos);
        }
        ldap_close($this->ldapLinkIdentifier);

        return $issue;
    }
}