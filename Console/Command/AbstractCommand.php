<?php
namespace SR\Base\Console\Command;

use Symfony\Component\Console\Command\Command;
use \Magento\Framework\ObjectManagerInterface;

class AbstractCommand extends Command
{
    protected $objectManager;

    /**
     * AbstractCommand constructor.
     * @param ObjectManagerInterface $manager
     */
    public function __construct(
        ObjectManagerInterface $manager
    )
    {
        $this->objectManager = $manager;
        parent::__construct();
    }

    protected function getObjectManager()
    {
        return $this->objectManager;
    }

}