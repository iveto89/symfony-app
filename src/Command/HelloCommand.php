<?php

namespace App\Command;

use App\Service\Greeting;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class HelloCommand extends Command
{
    protected static $defaultName = 'app:say-hello';

    /**
     * @var Greeting
     */
    private $greeting;

    public function __construct(Greeting $greeting)
    {
        $this->greeting = $greeting;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDefinition([new InputOption('Says hello to the user')])
            ->addArgument('name', InputArgument::REQUIRED, 'Who do you want to greet?');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $output->writeln([
            'Hello from the app',
            '------------------',
            ''
        ]);
        $output->write($this->greeting->greet($name));


//        $io = new SymfonyStyle($input, $output);
//        $arg1 = $input->getArgument('arg1');
//
//        if ($arg1) {
//            $io->note(sprintf('You passed an argument: %s', $arg1));
//        }
//
//        if ($input->getOption('option1')) {
//            // ...
//        }
//
//        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
