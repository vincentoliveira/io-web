<?php

namespace IO\OrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IO\OrderBundle\Enum\CountryEnum;

class UserIdentityType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('firstname', 'text', array(
                    'label' => 'Prénom',
                    'attr' => array('class' => 'form-control'),
                    'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank(array(
                        'message' => 'Veuillez renseigner votre prénom',
                            )),
                    'required' => true,
                ))
                ->add('lastname', 'text', array(
                    'label' => 'Nom',
                    'attr' => array('class' => 'form-control'),
                    'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank(array(
                        'message' => 'Veuillez renseigner votre nom',
                            )),
                    'required' => true,
                ))
                ->add('birthdate', 'date', array(
                    'label' => 'Date de naissance (jj/mm/aaaa)',
                    'format' => 'yyyy-MM-dd',
                    'widget' => 'single_text',
                    'attr' => array('class' => 'form-control date-masked'),
                    'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank(array(
                        'message' => 'Veuillez renseigner votre date de naissance',
                            )),
                    'years' => range(date('Y') - 18, 1900),
                    'required' => true,
                ))
                ->add('email', 'email', array(
                    'label' => 'Email',
                    'attr' => array('class' => 'form-control'),
                    'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank(array(
                        'message' => 'Veuillez renseigner votre adresse email',
                            )),
                    'required' => true,
                ))
                ->add('nationality', 'choice', array(
                    'label' => 'Nationalité',
                    'attr' => array('class' => 'form-control'),
                    'choices' => CountryEnum::$countries,
                    'required' => true,
                ))
                ->add('phones', 'collection', array(
                    'type' => new PhoneType(),
                    'label' => false,
                    'options' => array(
                        'label' => false,
                        'required' => false,
                        'attr' => array('class' => 'email-box')
                    ),
                ))
                ->add('addresses', 'collection', array(
                    'type' => new AddressType(),
                    'label' => false,
                    'options' => array(
                        'label' => false,
                        'required' => false,
                        'attr' => array('class' => 'email-box')
                    ),
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
        return 'identity';
    }

}
