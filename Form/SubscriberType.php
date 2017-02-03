<?php
namespace Ibrows\Bundle\NewsletterBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ibrows\Bundle\NewsletterBundle\Model\Mandant\MandantInterface;
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
     * @var string
     */
    protected $subscriberClass;

    /**
     * @var MandantInterface
     */
    protected $mandant;

    /**
     * SubscriberType constructor.
     * @param string $managerName
     * @param string $subscriberClass
     * @param MandantInterface $mandant
     */
    public function __construct($managerName, $subscriberClass, MandantInterface $mandant)
    {
        $this->managerName = $managerName;
        $this->subscriberClass = $subscriberClass;
        $this->mandant = $mandant;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $mandant = $this->mandant;

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
        ;
    }
}
