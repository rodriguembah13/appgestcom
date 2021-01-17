<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class RestoreManagerCommand extends Command
{
    protected static $defaultName = 'app:restore-manager';
    private $database;
    private $username;
    private $password;
    private $path;
    private $params;

    /** filesystem utility */
    private $fs;

    /**
     * RestoreManagerCommand constructor.
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        parent::__construct(null);
        $this->params = $parameterBag;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('file', InputArgument::REQUIRED, 'Absolute path for the file you need to dump database to.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io = new SymfonyStyle($input, $output);
        $this->database = $this->params->get('database_name');
        $this->username = $this->params->get('database_user');
        $this->password = $this->params->get('database_password');
        $this->hostname = $this->params->get('database_host');
        $this->path = $this->params->get('backup_path').'/'.$input->getArgument('file');
        $myoutput = [];
        $command = 'mysqldump --opt -h'.$this->hostname.' -u'.$this->username.' -p'.$this->password.' '.$this->database.' > '.$this->path;
        $command = 'mysql -h'.$this->hostname.' -u'.$this->username.' -p'.$this->password.' '.$this->database.' < '.$this->path;
        exec($command, $myoutput, $worked);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
