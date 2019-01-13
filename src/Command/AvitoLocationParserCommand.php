<?php

namespace App\Command;

use App\Entity\Location;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AvitoLocationParserCommand
 * @package App\Command
 */
class AvitoLocationParserCommand extends Command
{
    const AVITO_LOCATIONS_URL = 'http://autoload.avito.ru/format/Locations.xml';

    /**
     * @var Client
     */
    public $client;

    /**
     * AvitoAutoParserCommand constructor.
     * @param null $name
     */
    public function __construct(Client $client)
    {
        $this->client = new Client();

        parent::__construct(null);
    }

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
            $location = new Location();
            $location->setType($child->getName());
            $location->setLevel(1);
            /** @var \SimpleXMLElement $attribute */
            foreach ($child->attributes() as $attribute) {
                dump($attribute);
//                $attribute =json_decode(json_encode($attribute), true);
//                $location->setCoordinates(explode(' ', $attribute[0]));
            }
        }

        $output->writeln('success!');
    }

    /**
     * @return Client
     */
    private function getClient(){
        return $this->client;
    }
}
