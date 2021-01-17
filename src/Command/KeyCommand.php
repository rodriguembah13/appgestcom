<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class KeyCommand extends Command
{
    protected static $defaultName = 'app:key';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }
        $now=new \DateTime('now');

        $now1=$now->modify("");
        if ($input->getOption('option1')) {
            // ...
        }
        $this->makeDir();
        $io->success('key is created successfull.'.$now1);

        return 0;
    }

    protected function makeDir()
    {
        $home = getenv('HOME');
        $now=new \DateTime('now');

        $now1=$now->add(new \DateInterval("1"));
        $key = "Application de gestion scolaire GE-SCHOOL, licence jusqu'au 29-11-2020 généré le 20-11-2019 par \n rodrigue mbah ";
        $val = '';
        if (is_dir($home.DIRECTORY_SEPARATOR.'.geste')) {
            $val = 'esiste';
        } else {
            mkdir($home.DIRECTORY_SEPARATOR.'.geste');
            $val = 'noesiste';
        }
        $home = $home.DIRECTORY_SEPARATOR.'.geste';
        if (is_file($home.DIRECTORY_SEPARATOR.'conf.txt')) {
            $resource = $home.DIRECTORY_SEPARATOR.'conf.txt';
            $monfichier = fopen($resource, 'a+');
            // fwrite($monfichier, base64_encode($key));

            fseek($monfichier, 0); // On remet le curseur au début du fichier
            // fputs($monfichier, base64_encode($key)); // On écrit le nouveau nombre de pages vues
            file_put_contents($resource, base64_encode($key));
            fclose($monfichier);
        } else {
            fopen($home.DIRECTORY_SEPARATOR.'conf.txt', 'w');
        }
    }
}
