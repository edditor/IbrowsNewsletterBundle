<?php

namespace Ibrows\Bundle\NewsletterBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

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
            ->add('senderMail', EmailType::class)
            ->add('senderName')
            ->add('returnMail', EmailType::class)
            ->add(
                'design',
                EntityType::class,
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
