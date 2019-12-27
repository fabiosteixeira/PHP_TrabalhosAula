<?php

namespace App\Command;

use App\Engine\EngineInterface;
use App\Engine\Registry;
use App\Engine\Wikipedia\WikipediaParser;
use App\Engine\Wikipedia\WikipediaEngine;
use App\Result;
use App\ResultItem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttpClient;

class SearchCommand extends Command
{
    /**
     * @var Registry
     */
    private $engines;

    /**
     * @var StyleInterface
     */
    private $io;

    /**
     * SearchCommand constructor.
     * @param Registry $engines
     */
    public function __construct(Registry $engines)
    {
        parent::__construct();
        $this->engines = $engines;
    }

    protected function configure()
    {
        $this->setName('search');
        $this->setDescription('Perform a search in one of the registered search engines');
        $this->addArgument('term', InputArgument::REQUIRED, 'Term to be searched');
        $this->addOption('engine', 'e', InputOption::VALUE_OPTIONAL, 'Search engine to be used', 'wikipedia');
        $this->addUsage('<term>');
        $this->addUsage('-e|--engine <engine_name> <term>');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $term = $input->getArgument('term');

        // search
        $engine = $this->engines->get($input->getOption('engine'));
        $results = $engine->search($term);

        // show the results
        $this->present($term, $engine, $results);

        return 0;
    }

    private function present(string $term, EngineInterface $engine, Result $results): void
    {
        $this->io->title(sprintf('%d results was found for term "%s" on "%s"', count($results), $term, $engine::getName()));
        $this->io->text(sprintf("Showing first %d results:", $results->countItemsOnPage()));

        $rows = [];

        /** @var ResultItem $item */
        foreach ($results as $item) {
            $rows[] = [
                $item->getTitle(),
                substr($item->getPreview(), 0, 120) . '...'
            ];
        }

        $this->io->table(['Title', 'Preview'], $rows);
    }
}