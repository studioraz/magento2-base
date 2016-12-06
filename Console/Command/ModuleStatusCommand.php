<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SR\Base\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Command for displaying status of modules
 */
class ModuleStatusCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('studioraz:base:module:status')
            ->setDescription('Displays status of modules with extra information.');
        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $moduleList = $this->getObjectManager()->create('Magento\Framework\Module\ModuleList');

        $output->writeln('<info>List of enabled modules:</info>');
        $enabledModules = $moduleList->getNames();
        if (count($enabledModules) === 0) {
            $output->writeln('None');
        } else {
            foreach ($enabledModules as $module) {
                $moduleConfig = $moduleList->getOne($module);
                $output->writeln($moduleConfig['name'] . ' ' . $moduleConfig['setup_version']);
            }
        }
        $output->writeln('');

        $fullModuleList = $this->getObjectManager()->create('Magento\Framework\Module\FullModuleList');

        $output->writeln("<info>List of disabled modules:</info>");
        $disabledModules = array_diff($fullModuleList->getNames(), $enabledModules);


        if (count($disabledModules) === 0) {
            $output->writeln('None');
        } else {
            $output->writeln(join("\n", $disabledModules));
        }
    }
}
