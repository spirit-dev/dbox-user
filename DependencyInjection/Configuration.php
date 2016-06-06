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
 * File           Configuration.php
 * Updated the    06/06/16 16:18
 */

namespace SpiritDev\Bundle\DBoxUserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface {
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('spirit_dev_d_box_user');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
        ->children()
        ->arrayNode('ldap_driver')
            ->children()
                ->arrayNode('driver')
                    ->children()
                        ->scalarNode('host')->end()
                        ->integerNode('port')->end()
                        ->scalarNode('username')->end()
                        ->scalarNode('password')->end()
                    ->end()
                ->end()
                ->arrayNode('user')
                    ->children()
                        ->scalarNode('basedn')->end()
                    ->end()
                ->end()
                ->scalarNode('provider')->end()
            ->end()
        ->end()
        ->arrayNode('user_management')
            ->children()
                ->scalarNode('default_language')->defaultValue('en_US')->end()
                ->arrayNode('default_role')
                    ->defaultValue(array('ROLE_USER'))
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ->end()
        ;

        return $treeBuilder;
    }
}
