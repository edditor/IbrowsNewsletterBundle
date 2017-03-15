<?php

namespace Ibrows\Bundle\NewsletterBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendSettingsType extends AbstractType
{
    /**
     * @var bool
     */
    protected $isPasswordRequired = true;

    /**
     * @var bool
     */
    protected $showStartTime = true;

    /**
     * @param bool $isPasswordRequired
     * @param bool $showStartTime
     */
    public function __construct($isPasswordRequired = true)
    {
        $this->isPasswordRequired = $isPasswordRequired;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add(
                'password',
                PasswordType::class,
                array(
                    'required' => $this->isPasswordRequired,
                )
            )
            ->add('host')
            ->add('port')
            ->add(
                'encryption',
                ChoiceType::class,
                array(
                    'choices'    => array('tls' => 'tls', 'ssl' => 'ssl'),
                    'required'   => false,
                    'empty_data' => null,
                )
            )
            ->add(
                'authMode',
                ChoiceType::class,
                array(
                    'choices'    => array('plain' => 'plain', 'login' => 'login', 'cram-md5' => 'cram-md5'),
                    'required'   => false,
                    'empty_data' => null,
                )
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'validation_groups' => array('newsletter')
            )
        );
    }
}
