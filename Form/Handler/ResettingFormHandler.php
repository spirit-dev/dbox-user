<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SpiritDev\Bundle\DBoxUserBundle\Form\Handler;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Form\Model\ChangePassword;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use SpiritDev\Bundle\DBoxUserBundle\Ldap\LdapDriver;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ResettingFormHandler
 * @package SpiritDev\Bundle\DBoxUserBundle\Form\Handler
 */
class ResettingFormHandler {
    /**
     * @var
     */
    protected $request;
    /**
     * @var UserManagerInterface
     */
    protected $userManager;
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var LdapDriver
     */
    protected $ldap;
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * ResettingFormHandler constructor.
     * @param FormInterface $form
     * @param RequestStack $request
     * @param UserManagerInterface $userManager
     * @param LdapDriver $ldap
     * @param EntityManager $em
     */
    public function __construct(FormInterface $form, RequestStack $request, UserManagerInterface $userManager, LdapDriver $ldap, EntityManager $em) {
        $this->form = $form;
        $this->request = $request->getCurrentRequest();
        $this->userManager = $userManager;

        $this->ldap = $ldap;
        $this->em = $em;
    }

    /**
     * @return string
     */
    public function getNewPassword() {
        return $this->form->getData()->new;
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    public function process(UserInterface $user) {
        $this->form->setData(new ChangePassword());

        if ('POST' === $this->request->getMethod()) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($user);

                return true;
            }
        }

        return false;
    }

    /**
     * @param UserInterface $user
     */
    protected function onSuccess(UserInterface $user) {
        // Disabling user password registration
//        $user->setPlainPassword($this->getNewPassword());
        $user->setConfirmationToken(null);
        $user->setPasswordRequestedAt(null);
        $user->setEnabled(true);
        $this->userManager->updateUser($user);

        // getting DB user
        $dbUser = $this->em->getRepository('SpiritDevDBoxUserBundle:User')->findOneByUsername($user->getUsername());
        // Updating LDAP Password
        $this->ldap->ldapUpdatePassword($dbUser, $this->getNewPassword());
    }
}
