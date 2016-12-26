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
class CatalogProductDelete extends CatalogProductAbstract
{

    /**
     * @var string;
     */
    const INPUT_KEY_SKUS = 'skus';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {

        $this->setName('studioraz:catalog:product:delete')
            ->setDescription('Delete a product by SKU.')
            ->setDefinition($this->getInputList());
        parent::configure();
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->_state->setAreaCode('adminhtml');

        $this->_registry->register('isSecureArea', true);

        foreach ($input->getArgument(self::INPUT_KEY_SKUS) as $sku) {

            try {
                $_result = $this->_productRepository->deleteById($sku);
                if ($_result) {
                    $output->writeln('The product ' . $sku . ' successfully deleted.');
                } else {
                    $output->writeln('The product ' . $sku . ' could not be deleted.');
                }

            } catch (\Exception $e) {
                $output->writeln('Product sku: ' . $sku . ', ' . $e->getMessage());
            }
        }
    }

    /*
    * Get list of options and arguments for the command
    *
    * @return mixed
    */
    public function getInputList()
    {
        return [
            new InputArgument(
                self::INPUT_KEY_SKUS,
                InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                'Space-separated list of product skus to delete.'
            ),
        ];
    }

}
