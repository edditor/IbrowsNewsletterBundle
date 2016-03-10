<?php

namespace Ibrows\Bundle\NewsletterBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterType extends AbstractType
{
    /**
     * @var string
     */
    protected $managerName;

    /**
     * @var string
     */
    protected $designClass;

    /**
     * NewsletterType constructor.
     * @param string $managerName
     * @param string $designClass
     */
    public function __construct($managerName, $designClass)
    {
        $this->managerName = $managerName;
        $this->designClass = $designClass;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject')
            ->add('name')
            ->add('senderMail', 'email')
            ->add('senderName')
            ->add('returnMail', 'email')
            ->add(
                'design',
                'entity',
                array(
                    'em'    => $this->managerName,
                    'class' => $this->designClass,
                )
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'validation_groups' => array('newsletter'),
            )
        );
    }
}
