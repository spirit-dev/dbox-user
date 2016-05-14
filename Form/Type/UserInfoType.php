<?php

namespace SpiritDev\Bundle\DBoxUserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserInfoType extends AbstractType {

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

    public function getName() {
        return 'user_info';
    }

}