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
 * File           UserSettingsType.php
 * Updated the    15/05/16 11:47
 */

namespace SpiritDev\Bundle\DBoxUserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class UserSettingsType
 * @package SpiritDev\Bundle\DBoxUserBundle\Form\Type
 */
class UserSettingsType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('skip_intro', 'checkbox', array(
                'label' => 'profile.settings.skip_intro',
//                'label_attr' => array('class' => 'col-sm-2 control-label'),
//                'label_attr' => array('class' => 'checkbox-inline'),
//                'attr' => array('class' => 'form-control'),
                'required' => false
            ))
            ->add('save', 'submit', array(
                'label' => 'profile.settings.submit',
                'attr' => array('class' => 'btn btn-info')
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SpiritDev\Bundle\DBoxUserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'user_settings';
    }

}