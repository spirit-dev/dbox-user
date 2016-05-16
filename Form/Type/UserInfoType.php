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
 * File           UserInfoType.php
 * Updated the    15/05/16 11:47
 */

namespace SpiritDev\Bundle\DBoxUserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class UserInfoType
 * @package SpiritDev\Bundle\DBoxUserBundle\Form\Type
 */
class UserInfoType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('email', 'text', array(
                'label' => 'profile.info.email',
                'label_attr' => array('class' => 'col-sm-2 control-label'),
                'attr' => array('class' => 'form-control'),
                'required' => true
            ))
            ->add('lastname', 'text', array(
                'label' => 'profile.info.lastname',
                'label_attr' => array('class' => 'col-sm-2 control-label'),
                'attr' => array('class' => 'form-control'),
                'required' => true
            ))
            ->add('firstname', 'text', array(
                'label' => 'profile.info.firstname',
                'label_attr' => array('class' => 'col-sm-2 control-label'),
                'attr' => array('class' => 'form-control'),
                'required' => true
            ))
            ->add('imageFile', 'vich_image', array(
                'label' => 'profile.info.avatar',
                'label_attr' => array('class' => 'col-sm-2 control-label'),
                'required' => false,
                'allow_delete' => true,
                'download_link' => false,
                'attr' => array(
                    'accept' => "image/*"
                )
            ))
            ->add('save', 'submit', array(
                'label' => 'profile.info.submit',
                'attr' => array('class' => 'btn btn-info')
            ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'user_info';
    }

}