<?php

namespace IO\OrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IO\OrderBundle\Enum\PhonePrefixEnum;

class PhoneType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add(
                        $builder->create('prefix', 'choice', array(
                            'label' => 'Prefix',
                            'attr' => array('class' => 'form-control form-phone-prefix'),
                            'choices' => PhonePrefixEnum::$countries,
                            'required' => true,
                        ))
                        ->addModelTransformer(new DataTransformer\PhonePrefixDataTransformer())
                )
                ->add('number', 'text', array(
                    'label' => 'Numéro',
                    'attr' => array('class' => 'form-control form-phone-number'),
                    'required' => true,
        ));
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
        return 'phones';
    }

}
