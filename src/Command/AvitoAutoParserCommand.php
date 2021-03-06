<?php

namespace App\Command;

use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AvitoAutoParserCommand
 * @package App\Command
 */
class AvitoAutoParserCommand extends Command
{
    const AVITO_LOCATIONS_URL = 'http://autoload.avito.ru/format/Locations.xml';

    const AVITO_LIST_URL = 'https://www.avito.ru/search/filters/list?_=6&params[210]=19652&pmin=350000&pmax=500000&params[1283][]=14756&params[2809][]=19982&params[185][]=860&params[697]=8856&radius=100&category_id=9&location_id=625810&currentPage=catalog&filtersGroup=catalog';
    const AVITO_CATALOG_URL = 'https://www.avito.ru/js/catalog?_=6&params[210]=19652&pmin=350000&pmax=500000&params[1283][]=14756&params[2809][]=19982&params[185][]=860&params[697]=8856&radius=100&countOnly=1&category_id=9&location_id=625810';

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
            ->setName('avito-auto:parser')
            ->setDescription('Avito auto parser.')
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
//        $reponse = $this->getClient()->get(self::AVITO_LOCATIONS_URL);
        dump($xml);exit;
        $output->writeln('success!');
    }

    /**
     * @return Client
     */
    private function getClient(){
        return $this->client;
    }
}
