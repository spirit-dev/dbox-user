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
 * File           LoginSuccessHandler.php
 * Updated the    15/05/16 11:47
 */

namespace SpiritDev\Bundle\DBoxUserBundle\Security\Handler;

use Doctrine\ORM\EntityManager;
use FR3D\LdapBundle\Ldap\LdapManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * Class LoginSuccessHandler
 * @package SpiritDev\Bundle\DBoxUserBundle\Security\Handler
 */
class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface {
    /**
     * @var Router
     */
    protected $router;
    /**
     * @var TokenStorage
     */
    protected $security;
    /**
     * @var LdapManager
     */
    protected $ldap;
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * LoginSuccessHandler constructor.
     * @param Router $router
     * @param TokenStorage $security
     * @param LdapManager $ldap
     * @param EntityManager $em
     */
    public function __construct(Router $router, TokenStorage $security, LdapManager $ldap, EntityManager $em) {
        $this->router = $router;
        $this->security = $security;
        $this->ldap = $ldap;
        $this->em = $em;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {

        // Get current user
        $currentUser = $this->security->getToken()->getUser();

        $currentUsername = $currentUser->getUsername();
        if ($currentUsername != 'sys' && $currentUsername != 'admin') {

            // Get Ldap User
            $ldapUser = $this->ldap->findUserByUsername($currentUser->getUsername());

            // Get DB Stored User
            $dbUser = $this->em->getRepository('SpiritDevDBoxUserBundle:User')->find($currentUser->getId());

            // Merge DB User with Ldap User
            $dbUser->setDn($ldapUser->getDn());
            $dbUser->setLastName($ldapUser->getLastName());
            $dbUser->setFirstName($ldapUser->getFirstName());
            $dbUser->setEmail($ldapUser->getEmail());
            $dbUser->setEmailCanonical($ldapUser->getEmail());
            $dbUser->setUsername($ldapUser->getUsername());
            $dbUser->setUsernameCanonical($ldapUser->getUsername());
            $dbUser->setLanguage($ldapUser->getLanguage());

            // Save in db
            $this->em->flush();
            // Update session user
            $this->security->getToken()->setUser($dbUser);
        }

        // Redirect
        $referer_url = $this->router->generate('spirit_dev_dbox_portal_bundle_introduction');
        return new RedirectResponse($referer_url);
    }
}