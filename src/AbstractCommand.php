<?php

namespace App;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Class AbstractCommand
 */
abstract class AbstractCommand extends Command
{
    /**
     * @param string $id
     * @return object
     */
    protected function get($id)
    {
        return $this->getContainer()->get($id);
    }

    /**
     * @return Registry
     * @throws \LogicException If DoctrineBundle is not available
     */
    protected function getDoctrine()
    {
        if (!$this->getContainer()->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application.');
        }

        return $this->getContainer()->get('doctrine');
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    protected function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return ContainerInterface
     *
     * @throws \LogicException
     */
    protected function getContainer()
    {
        $application = $this->getApplication();
        if (null === $application) {
            throw new \LogicException('The container cannot be retrieved as the application instance is not yet set.');
        }

        return $application->getKernel()->getContainer();
    }
}
