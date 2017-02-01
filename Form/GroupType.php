<?php
namespace Ibrows\Bundle\NewsletterBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ibrows\Bundle\NewsletterBundle\Model\Mandant\MandantInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
     * @var MandantInterface
     */
    protected $mandant;

    /**
     * GroupType constructor.
     * @param string $managerName
     * @param string $groupClass
     * @param MandantInterface $mandant
     */
    public function __construct($managerName, $groupClass, MandantInterface $mandant)
    {
        $this->managerName = $managerName;
        $this->groupClass = $groupClass;
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
            ;
    }
}
