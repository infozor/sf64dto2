<?php

namespace App\ModuleProcess\Command;

use Cron\CronExpression;
//use App\Message\SimpleMessage;
//use Doctrine\DBAL\Connection;


use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use App\ModuleProcess\Aion\Test2;
use App\Message\TestJobMessageM;
use App\ModuleProcess\Orchestrator\ProcessOrchestrator;

#[AsCommand(name: 'app:schedule:worker', description: 'Add a short description for your command')]
class MyScheduleWorkerCommand extends Command
{
	public function __construct(private MessageBusInterface $bus, private ProcessOrchestrator $orchestrator)
	{
		parent::__construct();
	}
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$now = new \DateTimeImmutable();

		/*
		$jobs = $this->db->fetchAllAssociative("
		 SELECT id, cron_expr, payload
		 FROM scheduled_jobs
		 WHERE active = 1
		 ");
		*/ 
		
		$Test = new Test2($output);
		$jobs = $Test->get_schedulle_jobs();

		
		$jobs_ = [
				[
						'id' => 1,
						'cron_expr' => '* * * * *'
				],
				[
						'id' => 2,
						'cron_expr' => '* * * * *'
				]
		];
		
		// Запускаем процесс
		$this->orchestrator->startProcess(0, 'test_command', '123');

		foreach ( $jobs as $job )
		{
			$cron = CronExpression::factory($job['cron_expr']);
			//$cron = CronExpression::factory('* * * * *');

			if ($cron->isDue($now))
			{
				
				
				
				//$this->bus->dispatch(new SimpleMessage($job['payload']));
				
				
				//
				/*
				$this->bus->dispatch(new TestJobMessageM(
						jobId: $job['id'],
						payload: ['payload' => $job['payload']]
						));
				*/		

				$output->writeln("Queued job {$job['id']}");
			}
		}

		return Command::SUCCESS;
	}
}