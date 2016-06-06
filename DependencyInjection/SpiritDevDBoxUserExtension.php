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
 * File           SpiritDevDBoxUserExtension.php
 * Updated the    06/06/16 17:06
 */

namespace SpiritDev\Bundle\DBoxUserBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SpiritDevDBoxUserExtension extends Extension {
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container) {
        $configuration = new Configuration();
        $config = array();
        foreach ($configs as $subConfig) {
            $config = array_merge($config, $subConfig);
        }
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        // Checking LDAP Drivers configuration
        $this->checkConfig($container, $config, 'ldap_driver', 'ldap_driver', false);
        $this->checkConfig($container, $config['ldap_driver'], 'driver', 'ldap_driver.driver', false);
        $this->checkConfig($container, $config['ldap_driver']['driver'], 'host', 'ldap_driver.driver.host', true);
        $this->checkConfig($container, $config['ldap_driver']['driver'], 'port', 'ldap_driver.driver.port', true);
        $this->checkConfig($container, $config['ldap_driver']['driver'], 'username', 'ldap_driver.driver.username', true);
        $this->checkConfig($container, $config['ldap_driver']['driver'], 'password', 'ldap_driver.driver.password', true);
        $this->checkConfig($container, $config['ldap_driver'], 'user', 'ldap_driver.user', false);
        $this->checkConfig($container, $config['ldap_driver']['user'], 'basedn', 'ldap_driver.user.basedn', true);
        $this->checkConfig($container, $config['ldap_driver'], 'provider', 'ldap_driver.provider', true);
        // Checking user manager
        $this->checkConfig($container, $config, 'default_language', 'user_management.default_language', true);
        $this->checkConfig($container, $config, 'default_role', 'user_management.default_role', true);

    }

    /**
     * @param ContainerBuilder $container
     * @param $configurationToCheck
     * @param $commonName
     */
    private function checkConfig(ContainerBuilder $container, $configurationToCheck, $key, $commonName, $toSet) {

        if (!isset($configurationToCheck[$key])) {
            if ($toSet) {
                $message = 'The "' . $commonName . '" option must be set';
            } else {
                $message = 'The "' . $commonName . '" option node must be set';
            }
            throw new \InvalidArgumentException($message);
        } else {
            if ($toSet) {
                $container->setParameter('spirit_dev_d_box_user.' . $commonName, $configurationToCheck[$key]);
            }
        }
    }
}
