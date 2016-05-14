<?php

namespace SpiritDev\Bundle\DBoxUserBundle\Security\Handler;

use Doctrine\ORM\EntityManager;
use FR3D\LdapBundle\Ldap\LdapManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use SpiritDev\Bundle\DBoxUserBundle\Entity\User;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface {
    protected $router;
    protected $security;
    protected $ldap;
    protected $em;

    public function __construct(Router $router, TokenStorage $security, LdapManager $ldap, EntityManager $em) {
        $this->router = $router;
        $this->security = $security;
        $this->ldap = $ldap;
        $this->em = $em;
    }

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