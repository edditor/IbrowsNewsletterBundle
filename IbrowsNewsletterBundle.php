<?php

namespace Ibrows\Bundle\NewsletterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class IbrowsNewsletterBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new BlockProviderCompilerPass());
        $container->addCompilerPass(new RendererCompilerPass());
        $container->addCompilerPass(new MailerServiceCompilerPass());
        $container->addCompilerPass(new EditorCompilerPass());
    }
}
