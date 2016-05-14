<?php

namespace SpiritDev\Bundle\DBoxUserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserSettingsType extends AbstractType {

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

    public function getName() {
        return 'user_settings';
    }

}