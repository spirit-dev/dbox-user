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
 * File           ProfileController.php
 * Updated the    16/05/16 12:28
 */

namespace SpiritDev\Bundle\DBoxUserBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class ProfileController
 * @package SpiritDev\Bundle\DBoxUserBundle\Controller
 */
class ProfileController extends Controller {

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/", name="spirit_dev_dbox_user_bundle_profile_show")
     */
    public function showAction() {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $gitApi = $this->get('spirit_dev_dbox_portal_bundle.api.gitlab');
        if ($user->getGitLabId()) {
            $gitLabUser = $gitApi->getUser($user);
        } else {
            $gitLabUser = null;
        }

        $pmApi = $this->get('spirit_dev_dbox_portal_bundle.api.redmine');
        if ($user->getRedmineId()) {
            $pmUserInfo = $pmApi->showUser($user->getRedmineId());
            $pmUserUri = $this->getParameter('spirit_dev_d_box_portal.redmine_api.protocol') . $this->getParameter('spirit_dev_d_box_portal.redmine_api.url') . "/my/page";
        } else {
            $pmUserInfo = null;
            $pmUserUri = null;
        }

        return $this->container->get('templating')->renderResponse('SpiritDevDBoxUserBundle:Profile:profile.html.' . $this->container->getParameter('fos_user.template.engine'), array(
            'user' => $user,
            'gitinfo' => $gitLabUser,
            'pminfo' => $pmUserInfo,
            'pmbaseurl' => $pmUserUri
        ));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/editinfos", name="spirit_dev_dbox_user_bundle_profile_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->createForm('user_info', $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            // Get form new user
            $modifiedUser = $form->getData();

            // Store user new informations
            $ldapUpdate = $this->get('spirit_dev_dbox_user_bundle.ldap.ldap_driver')->ldapModifyUser($modifiedUser);

            if ($ldapUpdate) {
                // Get DB Stored User
                $dbUser = $this->getDoctrine()->getRepository('SpiritDevDBoxUserBundle:User')->find($user->getId());

                // Merge DB User with Ldap User
                $dbUser->setLastName($modifiedUser->getLastName());
                $dbUser->setFirstName($modifiedUser->getFirstName());
                $dbUser->setEmail($modifiedUser->getEmail());
                $dbUser->setEmailCanonical($modifiedUser->getEmail());

                // Save in db
                $this->getDoctrine()->getEntityManager()->flush();
                // Update session user
                $this->container->get('security.token_storage')->getToken()->setUser($modifiedUser);

                // Send email
                $mailer = $this->get('spirit_dev_dbox_portal_bundle.mailer');
                $mailer->profileInformationsUpdate($dbUser);

                // Set flashbag
                $this->get('session')->getFlashBag()->set('success', 'flashbag.edit_profile.success');
            } else {
                // Set flashbag
                $this->get('session')->getFlashBag()->set('error', 'flashbag.edit_profile.');
            }

            return new RedirectResponse($this->getRedirectionUrl());

        }

        return $this->render('SpiritDevDBoxUserBundle:Profile:edit_user_info.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Generate the redirection url when editing is completed.
     *
     * @return string
     */
    protected function getRedirectionUrl() {
        return $this->container->get('router')->generate('spirit_dev_dbox_user_bundle_user_profile_show');
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/editpassword", name="spirit_dev_dbox_user_bundle_profile_editpassword")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editPasswordAction(Request $request) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->createForm('user_pass_info', $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            // Get form new user
            $modifiedUser = $form->getData();

            // Get request datas
            $requestParams = $request->request->all()['user_pass_info'];
            $currentPass = $requestParams['currentpass'];
            $newPass = $requestParams['newpass'];
            $verifyNewPass = $requestParams['newpassverify'];

            // Verify ldap current password
            $ldapVerifyPass = $this->get('spirit_dev_dbox_user_bundle.ldap.ldap_driver')->ldapVerifyPassword($modifiedUser, $currentPass);

            if ($ldapVerifyPass) {
                // Verify if password match minimal requisites
                // Different pass between old and new
                // Same pass verification
                if (strcmp($newPass, $verifyNewPass) === 0 && strcmp($currentPass, $newPass) !== 0) {

                    // Update ldap new password
                    $ldapPassUpdate = $this->get('spirit_dev_dbox_user_bundle.ldap.ldap_driver')->ldapUpdatePassword($modifiedUser, $newPass);

                    if ($ldapPassUpdate) {
                        // Send email to user
                        $mailer = $this->get('spirit_dev_dbox_portal_bundle.mailer');
                        $mailer->profilePassUpdate($user);

                        // Set flashbag
                        $this->get('session')->getFlashBag()->set('success', 'flashbag.edit_password.success');
                    } else {
                        // Set flashbag
                        $this->get('session')->getFlashBag()->set('error', 'flashbag.edit_password.error');
                    }
                } else {
                    if (strcmp($newPass, $verifyNewPass) !== 0) {
                        // Set flashbag
                        $this->get('session')->getFlashBag()->set('error', 'flashbag.edit_password.verification_error');
                    }
                    if (strcmp($currentPass, $newPass) === 0) {
                        // Set flashbag
                        $this->get('session')->getFlashBag()->set('error', 'flashbag.edit_password.match_error');
                    }
                }
            } else {
                // Set flashbag
                $this->get('session')->getFlashBag()->set('error', 'flashbag.edit_password.pass_error');
            }
            return new RedirectResponse($this->getRedirectionUrl());
        }
        return $this->render('SpiritDevDBoxUserBundle:Profile:edit_user_pass.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/editsettings", name="spirit_dev_dbox_user_bundle_profile_settings")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editSettingsAction(Request $request) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $issue = false;

        $form = $this->createForm('user_settings', $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            // Get new datas
            $modifiedUser = $form->getData();
            // Get DB User
            $dbUser = $this->getDoctrine()->getRepository('SpiritDevDBoxUserBundle:User')->find($user->getId());
            // Update infos
            $dbUser->setSkipIntro($modifiedUser->getSkipIntro());
            // Register infos
            $this->getDoctrine()->getEntityManager()->flush();
            // Set flashbag
            $this->get('session')->getFlashBag()->set('success', 'flashbag.edit_profile.success');
            return new RedirectResponse($this->getRedirectionUrl());
        }

        return $this->render('SpiritDevDBoxUserBundle:Profile:edit_user_settings.html.twig', array('form' => $form->createView()));
    }

    /**
     * @param string $action
     * @param string $value
     */
    protected function setFlash($action, $value) {
        $this->container->get('session')->getFlashBag()->set($action, $value);
    }
}
