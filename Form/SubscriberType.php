<?php
namespace Ibrows\Bundle\NewsletterBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ibrows\Bundle\NewsletterBundle\Model\Mandant\MandantInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add(
                'subscribers',
                EntityType::class,
                array(
                    'em'            => $this->managerName,
                    'query_builder' => function (EntityRepository $repo) use ($mandant) {
                        $qb = $repo->createQueryBuilder('s');
                        $qb->where('s.mandant = :mandant');
                        $qb->andWhere('s.unsubscribed = 0');
                        $qb->setParameter('mandant', $mandant);
                        $qb->orderBy('s.email');
                        return $qb;
                    },
                    'class'         => $this->subscriberClass,
                    'multiple'      => true,
                    'expanded'      => false
                )
            );
    }
}
