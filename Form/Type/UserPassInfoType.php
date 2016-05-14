<?php

namespace SpiritDev\Bundle\DBoxUserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserPassInfoType extends AbstractType {

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

    public function getName() {
        return 'user_pass_info';
    }

}