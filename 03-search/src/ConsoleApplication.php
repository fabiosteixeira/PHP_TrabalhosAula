<?php

namespace App;

use App\Command\ListSearchEnginesCommand;
use App\Command\SearchCommand;
use App\Engine\Registry;
use App\Engine\Wikipedia\WikipediaParser;
use App\Engine\Wikipedia\WikipediaEngine;
use App\Engine\Wikiwix\WikiwixParser;
use App\Engine\Wikiwix\WikiwixEngine;
use Symfony\Component\Console\Application;
use Symfony\Component\HttpClient\HttpClient;

class ConsoleApplication extends Application
{
    public function __construct(string $version = 'UNKNOWN')
    {
        parent::__construct('Search Engine Application', $version);

        $registry = $this->createRegistry();

        $this->add(new SearchCommand($registry));
        $this->add(new ListSearchEnginesCommand($registry));
    }

    private function createRegistry(): Registry
    {
        $client = HttpClient::create();

        return new Registry([
            new WikipediaEngine(new WikipediaParser(), $client),
            new WikiwixEngine(new WikiwixParser(), $client)
        ]);
    }
}