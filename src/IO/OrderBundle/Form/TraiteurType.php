<?php

namespace IO\OrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TraiteurType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
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
                ->add('date', 'date', array(
                    'label' => 'Date de l\'évènement (jj/mm/aaaa)',
                    'format' => 'dd/MM/yyyy',
                    'widget' => 'single_text',
                    'attr' => array('class' => 'form-control date-masked'),
                    'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank(array(
                        'message' => 'Veuillez renseigner la date de l\'évènement',
                            )),
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
                ->add('adress', 'text', array(
                    'label' => 'Adresse du lieu de l\'évènement',
                    'attr' => array('class' => 'form-control'),
                    'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank(array(
                        'message' => 'Veuillez renseigner l\'adresse de l\'évènement',
                            )),
                    'required' => true,
                ))
                ->add('description', 'text', array(
                    'label' => 'Description de l\'évènement',
                    'attr' => array('class' => 'form-control'),
                    'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank(array(
                        'message' => 'Veuillez décrire l\'évènement',
                            )),
                    'required' => true,
                ))
                ->add('number', 'number', array(
                    'label' => 'Nombre de participants',
                    'attr' => array('class' => 'form-control'),
                    'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank(array(
                        'message' => 'Veuillez renseigner le nombre de participants',
                            )),
                    'required' => true,
                ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        
    }

    /**
     * @return string
     */
    public function getName() {
        return 'traiteur';
    }

}
