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
 * File           UserPassInfoType.php
 * Updated the    15/05/16 11:47
 */

namespace SpiritDev\Bundle\DBoxUserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class UserPassInfoType
 * @package SpiritDev\Bundle\DBoxUserBundle\Form\Type
 */
class UserPassInfoType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('currentpass', 'password', array(
                'label' => 'profile.pass.currentpass',
                'label_attr' => array('class' => 'col-sm-3 control-label'),
                'attr' => array('class' => 'form-control'),
                'required' => true,
                'mapped' => false,
            ))
            ->add('newpass', 'password', array(
                'label' => 'profile.pass.newpass',
                'label_attr' => array('class' => 'col-sm-3 control-label'),
                'attr' => array('class' => 'form-control'),
                'required' => true,
                'mapped' => false,
            ))
            ->add('newpassverify', 'password', array(
                'label' => 'profile.pass.newpassverify',
                'label_attr' => array('class' => 'col-sm-3 control-label'),
                'attr' => array('class' => 'form-control'),
                'required' => true,
                'mapped' => false,
            ))
            ->add('save', 'submit', array(
                'label' => 'profile.pass.save',
                'attr' => array('class' => 'btn btn-warning')
            ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'user_pass_info';
    }

}