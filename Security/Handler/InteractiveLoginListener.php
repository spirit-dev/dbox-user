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
 * Updated the    08/06/16 19:59
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
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {
        $user = $event->getAuthenticationToken()->getUser();

        // Create gitlab user if not already done
        if ($user->getGitLabId() == null) {
            // Create gitlab user
            $gitLabUser = $this->container->get('spirit_dev_dbox_portal_bundle.api.gitlab')->createUser($user);
            dump($gitLabUser);
            $user->setGitLabId($gitLabUser['id']);
        }

        // Create redmine user if not already done
        if ($user->getRedmineId() == null) {
            // Create redmine user
            $redmineUser = $this->container->get('spirit_dev_dbox_portal_bundle.api.redmine')->createUser($user);
            dump($redmineUser);
            $user->setRedmineId($redmineUser->{'id'});
        }

        // Create jenkins user Deprecated;


        // Create sonar user if not already done
        if ($user->getSonarManaged() == null) {
            // Create Sonar user here
            $sonarUser = $this->container->get('spirit_dev_dbox_portal_bundle.api.sonar')->createUser($user);
            dump($sonarUser);
            $user->setSonarManaged(true);
        }

        // Update DB user if necessary
        if ($user->getGitLabId() != null || $user->getRedmineId() == null || $user->getSonarManaged() == null) {
            // Updating user
            $this->userManager->updateUser($user);
            dump('user update');
        }
    }
}