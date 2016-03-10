<?php

/**
 * Created by PhpStorm.
 * Project: enventanewsletter
 *
 * User: mikemeier
 * Date: 05.03.15
 * Time: 12:23
 */

namespace Ibrows\Bundle\NewsletterBundle\Form;

use Ibrows\Bundle\NewsletterBundle\Block\Provider\ProviderInterface;
use Symfony\Component\Form\FormBuilderInterface;

class BlockMetadataEditType extends AbstractType
{
    /**
     * @var ProviderInterface
     */
    protected $provider;

    /**
     * @param ProviderInterface $provider
     */
    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('providerOptions', 'collection');
    }
}