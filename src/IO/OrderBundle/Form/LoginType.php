<?php

namespace IO\OrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LoginType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('email', 'email', array(
                    'label' => 'Email',
                    'attr' => array('class' => 'form-control'),
                    'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank(array(
                        'message' => 'Veuillez renseigner votre adresse email',
                            )),
                    'required' => true,
                ))
                ->add('plainPassword', 'password', array(
                    'label' => 'Mot de passe',
                    'attr' => array('class' => 'form-control'),
                    'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank(array(
                        'message' => 'Veuillez renseigner votre mot de passe',
                            )),
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
        return 'login';
    }

}
