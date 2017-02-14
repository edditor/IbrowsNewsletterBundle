<?php
namespace Ibrows\Bundle\NewsletterBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ibrows\Bundle\NewsletterBundle\Model\Mandant\MandantInterface;
use Ibrows\Bundle\NewsletterBundle\Model\ClassManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriberType extends AbstractType
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
     * @var MandantInterface
     */
    protected $mandant;

    /**
     * SubscriberType constructor.
     * @param string $managerName
     * @param string $classManager
     * @param MandantInterface $mandant
     */
    public function __construct($managerName, ClassManagerInterface $classManager, MandantInterface $mandant)
    {
        $this->managerName = $managerName;
        $this->classManager = $classManager;
        $this->mandant = $mandant;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                'label' => 'subscriber.email',
                'translation_domain' => 'IbrowsNewsletterBundle',
                'required' => true
                )
            )
            ->add('firstname', TextType::class, array(
                'label' => 'subscriber.firstname',
                'translation_domain' => 'IbrowsNewsletterBundle',
                'required' => false,
                'empty_data' => new EmptyString(),
                )
            )
            ->add('lastname', TextType::class, array(
                'label' => 'subscriber.lastname',
                'translation_domain' => 'IbrowsNewsletterBundle',
                'required' => false,
                'empty_data' => new EmptyString(),
                )
            )
            ->add('companyname', TextType::class, array(
                'label' => 'subscriber.company',
                'translation_domain' => 'IbrowsNewsletterBundle',
                'required' => false,
                'empty_data' => new EmptyString(),
                )
            )
            ->add(
                'groups', EntityType::class, array(
                    'em'    => $this->managerName,
                    'class' => $this->classManager->getModel('group'),
                    'label' => 'subscriber.groups',
                    'translation_domain' => 'IbrowsNewsletterBundle',
                    'multiple' => true,
                    'required' => false,
                )
            )
        ;
    }
}
