<?php

namespace Ibrows\Bundle\NewsletterBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
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
    public function __construct($isPasswordRequired = true, $showStartTime = true)
    {
        $this->isPasswordRequired = $isPasswordRequired;
        $this->showStartTime = $showStartTime;
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
                'password',
                array(
                    'required' => $this->isPasswordRequired,
                )
            )
            ->add('host')
            ->add('port')
            ->add(
                'encryption',
                'choice',
                array(
                    'choices'    => array('tls' => 'tls', 'ssl' => 'ssl'),
                    'required'   => false,
                    'empty_data' => null,
                )
            )
            ->add(
                'authMode',
                'choice',
                array(
                    'choices'    => array('plain' => 'plain', 'login' => 'login', 'cram-md5' => 'cram-md5'),
                    'required'   => false,
                    'empty_data' => null,
                )
            )
            ->add('interval');

        if (true === $this->showStartTime) {
            $builder->add('starttime', 'datetime');
        }
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
