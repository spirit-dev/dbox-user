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
 * File           InteractiveLoginListener.php
 * Updated the    08/08/16 10:41
 */

namespace SpiritDev\Bundle\DBoxUserBundle\Security\Handler;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class InteractiveLoginListener
 * @package SpiritDev\Bundle\DBoxUserBundle\Security\Handler
 */
class InteractiveLoginListener {

    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * InteractiveLoginListener constructor.
     * @param ContainerInterface $container
     * @param UserManagerInterface $userManager
     */
    public function __construct(ContainerInterface $container, UserManagerInterface $userManager) {
        $this->container = $container;
        $this->userManager = $userManager;
    }

    /**
     * @param InteractiveLoginEvent $event
     * Function called for every login. It creates PM, QA, CI, VCS accounts for users
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {
        $user = $event->getAuthenticationToken()->getUser();

        // If user not in "sys" or "admin"
        $username = $user->getUsername();
        if ($username != "admin" && $username != "sys") {

            // Some vars
            $isModified = false;
            // Get current date
            $now = new \DateTime();

            // Get API's
            $vcsAPI = $this->container->get('spirit_dev_dbox_portal_bundle.api.gitlab');
            $pmAPI = $this->container->get('spirit_dev_dbox_portal_bundle.api.redmine');
            $qaAPI = $this->container->get('spirit_dev_dbox_portal_bundle.api.sonar');
            // Get Users
            $vcsUser = $vcsAPI->getUser($user);
            $pmUser = $pmAPI->getIdByUsername($user);

            // VCS - Create Users if not exists
            if ($user->getGitLabId() == null && !array_key_exists('id', $vcsUser)) {
                // Create gitlab user
                $vcsUser = $vcsAPI->createUser($user);
                $user->setGitLabId($vcsUser['id']);
                $isModified = true;
            }

            // PM - Create Users if not exists
            if ($user->getRedmineId() == null && !is_int($pmUser)) {
                // Create redmine user
                $pmUser = $pmAPI->createUser($user);
                $user->setRedmineId($pmUser->{'id'});
                $isModified = true;
            }

            // QA - Create Users if not exists
            if ($user->getSonarManaged() == null) {
                // Create Sonar user here
                $sonarUser = $qaAPI->createUser($user);
                $user->setSonarManaged(true);
                $isModified = true;
            }


            // Check schedule sync
            if ($now > $user->getNextSyncDate() || $user->getNextSyncDate() == null) {

                // Create / Synchronize gitlab user if not already done
                if (array_key_exists('id', $vcsUser)) {
                    $user->setGitLabId($vcsUser['id']);
                    $isModified = true;
                }

                // PM - Synchronize
                if (is_int($pmUser)) {
                    $user->setRedmineId($pmUser);
                    $isModified = true;
                }

            }

            // Update DB user if necessary
            if ($isModified) {
                // Updating next sync date
                $nextSyncDate = $now->add(new \DateInterval('P10D'));
                $user->setNextSyncDate($nextSyncDate);
                // Updating user
                $this->userManager->updateUser($user);
            }
        }
    }
}