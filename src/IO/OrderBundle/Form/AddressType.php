<?php

namespace IO\OrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IO\OrderBundle\Enum\CountryEnum;

class AddressType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('number', 'text', array(
                    'label' => 'Numéro de rue',
                    'attr' => array('class' => 'form-control form-address-street-number'),
                    'required' => true,
                ))
                ->add('street', 'text', array(
                    'label' => 'Nom de la rue',
                    'attr' => array('class' => 'form-control form-address-street-street'),
                    'required' => true,
                ))
                ->add('postcode', 'text', array(
                    'label' => 'Code postal',
                    'attr' => array('class' => 'form-control form-address-city-postcode'),
                    'required' => true,
                ))
                ->add('city', 'text', array(
                    'label' => 'Ville',
                    'attr' => array('class' => 'form-control form-address-city-city'),
                    'required' => true,
                ))
                ->add('country', 'choice', array(
                    'label' => 'Pays',
                    'attr' => array('class' => 'form-control'),
                    'choices' => CountryEnum::$countries,
                    'required' => true,
                ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'address';
    }

}
