<?php

namespace App\Command;

use App\Engine\EngineInterface;
use App\Engine\Registry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListSearchEnginesCommand extends Command
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
     * ListSearchEnginesCommand constructor.
     * @param Registry $engines
     */
    public function __construct(Registry $engines)
    {
        parent::__construct();
        $this->engines = $engines;
    }

    protected function configure()
    {
        $this->setName('engines');
        $this->setDescription('List all available search engines');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title('Available search engines');

        $engines = array_map(function(EngineInterface $engine) {
            return [$engine::getName(), get_class($engine)];
        }, $this->engines->all());

        $this->io->table(['Name', 'Implementation'], $engines);

        return 0;
    }
}