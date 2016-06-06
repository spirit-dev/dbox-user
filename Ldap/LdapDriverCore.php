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
 * File           LdapDriverCore.php
 * Updated the    06/06/16 16:00
 */

namespace SpiritDev\Bundle\DBoxUserBundle\Ldap;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LdapDriverCore
 * @package SpiritDev\Bundle\DBoxUserBundle\Ldap
 */
class LdapDriverCore {

    /**
     * @var ContainerInterface
     */
    protected $container;

    // Ldap Connection info
    /**
     * @var
     */
    protected $ldapHostname;
    /**
     * @var
     */
    protected $ldapPort;
    /**
     * @var
     */
    protected $ldapDomLogin;
    /**
     * @var
     */
    protected $ldapDomPassw;

    // Ldap Bind info
    /**
     * @var
     */
    protected $baseDn;

    // Ldap usage resources
    /**
     * @var
     */
    protected $ldapLinkIdentifier;
    /**
     * @var
     */
    protected $ldapBind;

    /**
     * Initialises the service
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;

        // Setting Ldap Connection info
        $this->ldapHostname = $container->getParameter('spirit_dev_d_box_user.ldap_driver.driver.host');
        $this->ldapPort = $container->getParameter('spirit_dev_d_box_user.ldap_driver.driver.port');
        $this->ldapDomLogin = $container->getParameter('spirit_dev_d_box_user.ldap_driver.driver.username');
        $this->ldapDomPassw = $container->getParameter('spirit_dev_d_box_user.ldap_driver.driver.password');

        // Setting Ldap Bind info
        $this->baseDn = $this->container->getParameter('spirit_dev_d_box_user.ldap_driver.user.basedn');
    }

    /**
     * Initialize ldap connection
     * @return bool
     */
    protected function ldapInit() {
        $issue = false;

        $this->ldapLinkIdentifier = $this->ldapConnect();

        if (!$this->ldapLinkIdentifier) {
            die('Could not connect to {' . $this->ldapHostname . ':' . $this->ldapPort . '}');
            $issue = false;
        } else {
            ldap_set_option($this->ldapLinkIdentifier, LDAP_OPT_PROTOCOL_VERSION, 3);
            $this->ldapBind = $this->ldapBind();
            if (!$this->ldapBind) {
                die('Could not bind with these credentials');
                $issue = false;
            } else {
                ldap_set_option($this->ldapLinkIdentifier, LDAP_OPT_REFERRALS, 0);
                $issue = true;
            }
        }

        return $issue;
    }

    /**
     * Ldap effective connection
     * @return bool|resource
     */
    protected function ldapConnect() {
        return ldap_connect($this->ldapHostname, $this->ldapPort);
    }

    /**
     * Ldap bind with admin credentials
     * @return bool
     */
    protected function ldapBind() {
        return ldap_bind($this->ldapLinkIdentifier, $this->ldapDomLogin, $this->ldapDomPassw);
    }

    /**
     * Search ldap entry
     * @param $dn
     * @return resource
     */
    protected function ldapSearch($dn) {
        return ldap_search($this->ldapLinkIdentifier, $this->baseDn, "(uid=$dn)");
    }
}