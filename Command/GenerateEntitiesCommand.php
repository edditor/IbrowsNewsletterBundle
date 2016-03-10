<?php

namespace Ibrows\Bundle\NewsletterBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class GenerateEntitiesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ibrows:newsletter:entities:generate')
            ->setDescription('Generates the needed entities')
            ->addArgument('namespace', InputArgument::REQUIRED, 'Your entity namespace (e.g. Ibrows\\NewsletterSandboxBundle\\Entity)')
            ->addOption('table-prefix', null, InputOption::VALUE_REQUIRED, 'Prefix for Table-Names', 'ibrows_newsletter')
            ->addOption('path', null, InputOption::VALUE_REQUIRED, 'Entity Path');
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int|null|void
     * @throws Exception
     * @todo Getting Path from Doctrine MetadataFactory or anywhere from Symfony
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $namespace = $input->getArgument('namespace');
        $newsletterNamespace = $namespace . '\\Newsletter';
        $namespacePath = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
        $kernelPath = $this->getContainer()->getParameter('kernel.root_dir');
        $destPath = $input->getOption('path') ?: realpath($kernelPath . '/../src/' . $namespacePath);

        if (!$destPath) {
            throw new Exception("Entity path could not been resolved");
        }

        $skeletonDir = realpath(__DIR__ . '/../Resources/entityskeleton');
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($skeletonDir));

        $filesystem = new Filesystem();

        $search = array('{{TABLE_PREFIX}}', '{{NAMESPACE}}');
        $replace = array($input->getOption('table-prefix'), $newsletterNamespace);
        foreach ($iterator as $file) {
            if (is_dir($file)) {
                continue;
            }

            $fileContent = str_replace($search, $replace, file_get_contents($file));
            $fileDest = $destPath . str_replace($skeletonDir, '', $file);

            $filesystem->mkdir(dirname($fileDest));
            file_put_contents(substr($fileDest, 0, -9), $fileContent);
        }
    }
}
