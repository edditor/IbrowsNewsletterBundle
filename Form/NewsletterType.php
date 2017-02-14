<?php

namespace Ibrows\Bundle\NewsletterBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Ibrows\Bundle\NewsletterBundle\Model\ClassManagerInterface;

class NewsletterType extends AbstractType
{
    /**
     * @var string
     */
    protected $managerName;

    /**
     * @var ClassManagerInterface
     */
    protected $classManager;

    /**
     * NewsletterType constructor.
     * @param string $managerName
     * @param string $designClass
     */
    public function __construct($managerName, ClassManagerInterface $classManager)
    {
        $this->managerName = $managerName;
        $this->classManager = $classManager;
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
                    'class' => $this->classManager->getModel('design'),
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
