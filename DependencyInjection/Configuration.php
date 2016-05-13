<?php

namespace Ibrows\Bundle\NewsletterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ibrows_newsletter');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('db_driver')->defaultValue('orm')->end()
            ->end()
        ;

        $this->addMandantSection($rootNode);
        $this->addTemplatesSection($rootNode);
        $this->addClassesSection($rootNode);
        $this->addRendererBridgeSection($rootNode);
        $this->addFilesystemSection($rootNode);
        $this->addServiceIdSection($rootNode);

        return $treeBuilder;
    }

    protected function addMandantSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('mandants')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')
                ->end()
            ->end()
        ;

    }

    protected function addTemplatesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()

                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()

                        ->scalarNode('base_template')->defaultValue('IbrowsNewsletterBundle::layout.html.twig')->end()

                        ->arrayNode('mandant')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('index')->defaultValue('IbrowsNewsletterBundle:Mandant:index.html.twig')->end()
                                ->scalarNode('edit')->defaultValue('IbrowsNewsletterBundle:Mandant:edit.html.twig')->end()
                            ->end()
                        ->end()

                        ->arrayNode('newsletter')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('index')->defaultValue('IbrowsNewsletterBundle:Newsletter:index.html.twig')->end()
                                ->scalarNode('list')->defaultValue('IbrowsNewsletterBundle:Newsletter:list.html.twig')->end()
                                ->scalarNode('create')->defaultValue('IbrowsNewsletterBundle:Newsletter:create.html.twig')->end()
                                ->scalarNode('edit')->defaultValue('IbrowsNewsletterBundle:Newsletter:edit.html.twig')->end()
                                ->scalarNode('subscriber')->defaultValue('IbrowsNewsletterBundle:Newsletter:subscriber.html.twig')->end()
                                ->scalarNode('settings')->defaultValue('IbrowsNewsletterBundle:Newsletter:settings.html.twig')->end()
                                ->scalarNode('summary')->defaultValue('IbrowsNewsletterBundle:Newsletter:summary.html.twig')->end()
                                ->scalarNode('send')->defaultValue('IbrowsNewsletterBundle:Newsletter:send.html.twig')->end()
                                ->scalarNode('unsubscribe')->defaultValue('IbrowsNewsletterBundle:Newsletter:unsubscribe.html.twig')->end()
                                ->scalarNode('overview')->defaultValue('IbrowsNewsletterBundle:Newsletter:overview.html.twig')->end()
                                ->scalarNode('generate')->defaultValue('IbrowsNewsletterBundle:Newsletter:generate.html.twig')->end()
                            ->end()
                        ->end()

                        ->arrayNode('design')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('index')->defaultValue('IbrowsNewsletterBundle:Design:index.html.twig')->end()
                                ->scalarNode('list')->defaultValue('IbrowsNewsletterBundle:Design:list.html.twig')->end()
                                ->scalarNode('create')->defaultValue('IbrowsNewsletterBundle:Design:create.html.twig')->end()
                                ->scalarNode('edit')->defaultValue('IbrowsNewsletterBundle:Design:edit.html.twig')->end()
                                ->scalarNode('show')->defaultValue('IbrowsNewsletterBundle:Design:show.html.twig')->end()
                            ->end()
                        ->end()

                        ->arrayNode('subscriber')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('list')->defaultValue('IbrowsNewsletterBundle:Subscriber:list.html.twig')->end()
                            ->end()
                        ->end()

                        ->arrayNode('statistic')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('show')->defaultValue('IbrowsNewsletterBundle:Statistic:show.html.twig')->end()
                            ->end()
                        ->end()

                ->end()
            ->end()
        ;
    }

    protected function addClassesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('classes')->children()

                    ->arrayNode('model')
                            ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('newsletter')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('mandant')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('subscriber')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('design')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('user')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('block')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('group')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('readlog')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('unsubscribelog')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('sendlog')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('sendsettings')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('mailjob')->isRequired()->cannotBeEmpty()->end()
                        ->end()
                    ->end()

                    ->arrayNode('form')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('newsletter')->defaultValue('Ibrows\\Bundle\\NewsletterBundle\\Form\\NewsletterType')->end()
                            ->scalarNode('mandant')->defaultValue('Ibrows\\Bundle\\NewsletterBundle\\Form\\MandantType')->end()
                            ->scalarNode('subscriber')->defaultValue('Ibrows\\Bundle\\NewsletterBundle\\Form\\SubscriberType')->end()
                            ->scalarNode('design')->defaultValue('Ibrows\\Bundle\\NewsletterBundle\\Form\\DesignType')->end()
                            ->scalarNode('testmail')->cannotBeOverwritten(true)->defaultValue('Ibrows\\Bundle\\NewsletterBundle\\Form\\TestMailType')->end()
                            ->scalarNode('unsubscribe')->defaultValue('Ibrows\\Bundle\\NewsletterBundle\\Form\\UnsubscribeType')->end()
                            ->scalarNode('sendsettings')->defaultValue('Ibrows\\Bundle\\NewsletterBundle\\Form\\SendSettingsType')->end()
                        ->end()
                    ->end()

                ->end()
            ->end()
        ;
    }

    protected function addFilesystemSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()

                ->arrayNode('filesystem')->children()
                    ->arrayNode('block')
                        ->children()
                            ->scalarNode('directory')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('public')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('host')->isRequired()->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()

            ->end()
        ;
    }

    protected function addRendererBridgeSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()

                ->arrayNode('rendererbridge')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')->defaultValue('Ibrows\\Bundle\\NewsletterBundle\\Renderer\\Bridge\\RendererBridge')->end()
                    ->end()
                ->end()

                ->arrayNode('gendertitlestrategy')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')->defaultValue('Ibrows\\Bundle\\NewsletterBundle\\Renderer\\GenderTitleStrategy\\GenderTitleTranslatorStrategy')->end()
                        ->arrayNode('translator')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('translationdomain')->defaultValue('IbrowsNewsletterBundle')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

            ->end()
        ;
    }

    protected function addServiceIdSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('serviceid')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('rendererbridge')->defaultValue('ibrows_newsletter.rendererbridge')->end()
                        ->scalarNode('gendertitlestrategy')->defaultValue('ibrows_newsletter.rendererbridge.gendertitlestrategy.translator')->end()
                        ->scalarNode('encryptionadapter')->defaultValue('ibrows_newsletter.encryption.adapter.mcrypt')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
