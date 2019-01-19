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
            $item = json_decode(json_encode($child->attributes()), true);
            $attributes = $item['@attributes'];

            $isExists = $this->getEm()->getRepository(Location::class)
                ->findOneBy(['externalId'=> $attributes[self::ATTRIBUTE_ID]]);

            if ($isExists) {
                continue;
            }

            $location = new Location();
            $location->setType($child->getName());
            $location->setLevel(1);
            $location->setExternalId($attributes[self::ATTRIBUTE_ID]);
            $location->setCoordinates(explode(' ', $attributes[self::ATTRIBUTE_COORD]));
            $location->setName($attributes[self::ATTRIBUTE_NAME]);
            $this->getEm()->persist($location);
        }

        $this->getEm()->flush();

        $output->writeln('success!');
    }
}
