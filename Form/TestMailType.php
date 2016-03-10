<?php

namespace Ibrows\Bundle\NewsletterBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

class TestMailType extends AbstractType
{
    /**
     * @var array
     */
    protected $subscribers;

    /**
     * @var string
     */
    protected $defaultMail;

    /**
     * @param array  $subscribers
     * @param string $defaultMail
     */
    public function __construct(array $subscribers, $defaultMail = null)
    {
        $this->subscribers = $subscribers;
        $this->defaultMail = $defaultMail;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array('data' => $this->defaultMail, 'label' => 'testemail'))
            ->add(
                'subscriber',
                'choice',
                array(
                    'label'   => 'testsubscriber',
                    'choices' => $this->subscribers
                )
            );
    }
}
