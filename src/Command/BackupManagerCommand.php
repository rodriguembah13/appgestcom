<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BackupManagerCommand extends Command
{
    protected static $defaultName = 'app:backup-manager';
    protected static $mysqlDatabaseName = 'fake';
    protected static $mysqlUserName = 'symfony';
    protected static $mysqlPassword = 'Symfony123*';
    protected static $mysqlHostName = 'localhost';
    protected static $mysqlExportPath = '/home/ballack/PhpstormProjects/untitled/20-07-24-11-42-dump.sql';

    private $database;
    private $username;
    private $password;
    private $hostname;
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
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->database = $this->params->get('database_name');
        $this->username = $this->params->get('database_user');
        $this->password = $this->params->get('database_password');
        $this->hostname = $this->params->get('database_host');
        $this->path = $this->params->get('backup_path').'/'.date('y-m-d-G-i').'-dump.sql';
        $myoutput = [];
        //$command = 'mysqldump --opt -h'.self::$mysqlHostName.' -u'.self::$mysqlUserName.' -p'.self::$mysqlPassword.' '.self::$mysqlDatabaseName.' > '.self::$mysqlExportPath;
        $command = 'mysqldump --opt -h'.$this->hostname.' -u'.$this->username.' -p'.$this->password.' '.$this->database.' > '.$this->path;

        exec($command, $myoutput, $worked);
        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
