<?php
namespace Ibrows\Bundle\NewsletterBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ibrows\Bundle\NewsletterBundle\Model\Mandant\MandantInterface;
use Ibrows\Bundle\NewsletterBundle\Model\ClassManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupType extends AbstractType
{
    /**
     * @var string
     */
    protected $managerName;

    /**
     * @var string
     */
    protected $groupClass;

    /**
     * @var string
     */
    protected $subscriberClass;

    /**
     * @var MandantInterface
     */
    protected $mandant;

    /**
     * GroupType constructor.
     * @param string $managerName
     * @param string $classManager
     * @param MandantInterface $mandant
     */
    public function __construct($managerName, ClassManagerInterface $classManager, MandantInterface $mandant)
    {
        $this->managerName = $managerName;
        $this->classManager = $classManager;
        $this->subscriberClass = $classManager->getModel('subscriber');
        $this->groupClass = $classManager->getModel('group');
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
            ->add('name')
            ->add(
                'subscribers', EntityType::class, array(
                    'em'    => $this->managerName,
                    'class' => $this->subscriberClass,
                    'label' => 'group.subscribers',
                    'translation_domain' => 'IbrowsNewsletterBundle',
                    'multiple' => true,
                    'required' => false,
                    'by_reference' => false,
                )
            )
        ;
    }
}
