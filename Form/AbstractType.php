<?php

namespace Ibrows\Bundle\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType as BaseType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractType extends BaseType
{
    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'translation_domain' => 'IbrowsNewsletterBundleForms',
            )
        );
    }
}
