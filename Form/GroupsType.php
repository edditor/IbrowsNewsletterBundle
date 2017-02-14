<?php
namespace Ibrows\Bundle\NewsletterBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ibrows\Bundle\NewsletterBundle\Model\Mandant\MandantInterface;
use Ibrows\Bundle\NewsletterBundle\Model\ClassManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupsType extends AbstractType
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
     * GroupsType constructor.
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
        $mandant = $this->mandant;

        $builder
            ->add(
                'groups',
                EntityType::class,
                array(
                    'em'            => $this->managerName,
                    'query_builder' => function (EntityRepository $repo) use ($mandant) {
                        $qb = $repo->createQueryBuilder('s');
                        $qb->where('s.mandant = :mandant');
                        $qb->setParameter('mandant', $mandant);
                        $qb->orderBy('s.name', 'ASC');
                        return $qb;
                    },
                    'class'         => $this->classManager->getModel('group'),
                    'multiple'      => true,
                    'expanded'      => false
                )
            );
    }
}
