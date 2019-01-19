<?php

namespace App\Command;

use App\AbstractCommand;
use App\Entity\Location;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AvitoLocationParserCommand
 * @package App\Command
 */
class AvitoLocationParserCommand extends AbstractCommand
{
    const AVITO_LOCATIONS_URL = 'http://autoload.avito.ru/format/Locations.xml';

    const ATTRIBUTE_COORD = 'Coord';
    const ATTRIBUTE_ID = 'Id';
    const ATTRIBUTE_NAME = 'Name';

    protected function configure()
    {
        $this
            ->setName('avito-location:parser')
            ->setDescription('Avito location parser.')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $xml = simplexml_load_file(self::AVITO_LOCATIONS_URL);
        /** @var \SimpleXMLElement $child */
        foreach ($xml->children() as $child) {
            dump($child);exit;
            $location = new Location();
            $location->setType($child->getName());
            $location->setLevel(1);
            /** @var \SimpleXMLElement $attribute */
            foreach ($child->attributes() as $attribute) {
                $item =json_decode(json_encode($attribute), true);
                switch ($attribute->getName()) {
                    case self::ATTRIBUTE_ID:
                        $location->setExternalId($item[0]);
                        break;
                    case self::ATTRIBUTE_NAME:
                        $location->setName($item[0]);
                        break;
                    case self::ATTRIBUTE_COORD:
                        $location->setCoordinates(explode(' ', $item[0]));
                        break;
                }
            }
            $this->getEm()->persist($location);
        }

        $this->getEm()->flush();

        $output->writeln('success!');
    }
}
