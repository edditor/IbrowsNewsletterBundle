<?php

namespace Ibrows\Bundle\NewsletterBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class EditorCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // add all tagged editors
        if (!$container->hasDefinition('ibrows_newsletter.editor_manager')) {
            return;
        }

        $definition = $container->getDefinition(
            'ibrows_newsletter.editor_manager'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'ibrows_newsletter.editor'
        );

        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addEditor',
                array($id, new Reference($id))
            );
        }
    }
}
