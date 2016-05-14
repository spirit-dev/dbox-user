<?php

namespace SpiritDev\Bundle\DBoxUserBundle\Ldap;

use Symfony\Component\DependencyInjection\ContainerInterface;

class LdapDriverCore {

    protected $container;

    // Ldap Connection info
    protected $ldapHostname;
    protected $ldapPort;
    protected $ldapDomLogin;
    protected $ldapDomPassw;

    // Ldap Bind info
    protected $baseDn;

    // Ldap usage resources
    protected $ldapLinkIdentifier;
    protected $ldapBind;

    /**
     * Initialises the service
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;

        // Setting Ldap Connection info
        $this->ldapHostname = $container->getParameter('ldap_driver')['driver']['host'];
        $this->ldapPort = $container->getParameter('ldap_driver')['driver']['port'];
        $this->ldapDomLogin = $container->getParameter('ldap_driver')['driver']['username'];
        $this->ldapDomPassw = $container->getParameter('ldap_driver')['driver']['password'];

        // Setting Ldap Bind info
        $this->baseDn = $this->container->getParameter('ldap_driver')['user']['basedn'];
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