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
            if ($locationLevel1 = $this->addLocation($child, 1)) {
                $this->getEm()->persist($locationLevel1);
            }
            /** @var \SimpleXMLElement $childLevel2 */
            foreach ($child->children() as $childLevel2) {
                if ($locationLevel2 = $this->addLocation($childLevel2, 2)) {
                    $locationLevel2->setParent($locationLevel1);
                    $locationLevel2->setRegion($locationLevel1);
                    $this->getEm()->persist($locationLevel2);
                }
                /** @var \SimpleXMLElement $childLevel3 */
                foreach ($childLevel2->children() as $childLevel3) {
                    if ($locationLevel3 = $this->addLocation($childLevel3, 3)) {
                        $locationLevel3->setParent($locationLevel2);
                        $locationLevel3->setRegion($locationLevel1);
                        $this->getEm()->persist($locationLevel3);
                    }
                }
            }
        }

        $this->getEm()->flush();

        $output->writeln('success!');
    }

    /**
     * @param \SimpleXMLElement $element
     * @param int               $level
     * @return Location|null
     */
    protected function addLocation(\SimpleXMLElement $element, int $level)
    {
        $item = json_decode(json_encode($element->attributes()), true);
        $attributes = $item['@attributes'];

        $isExists = $this->getEm()->getRepository(Location::class)
            ->findOneBy([
                'type'=> $element->getName(),
                'externalId'=> $attributes[self::ATTRIBUTE_ID],
            ]);

        if ($isExists) {
            return null;
        }

        $location = new Location();
        $location->setType($element->getName());
        $location->setLevel($level);
        $location->setExternalId($attributes[self::ATTRIBUTE_ID]);
        if (isset($attributes[self::ATTRIBUTE_COORD])) {
            $location->setCoordinates(explode(' ', $attributes[self::ATTRIBUTE_COORD]));
        }
        $location->setName($attributes[self::ATTRIBUTE_NAME]);

        return $location;
    }
}
