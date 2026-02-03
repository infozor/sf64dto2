<?php

namespace App\ModuleProcess\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

use App\ModuleProcess\Aion\Test2;
use App\ModuleProcess\Aion\IonLog;
use App\Message\TestJobMessage;
use App\Message\TestJobMessageM;


#[AsCommand(name: 'app:test-command', description: 'Add a short description for your command')]
class MyTestCommand extends Command
{
	private string $projectDir;
	private IonLog $IonLog;
	private MessageBusInterface $bus;
	public function __construct(string $projectDir, MessageBusInterface $bus)
	{
		$this->IonLog = new IonLog($projectDir);
		$this->projectDir = $projectDir;
		$this->bus = $bus;

		parent::__construct();
	}
	protected function configure(): void
	{
		$this->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
	}
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$status_text = '123';

		$message = date('d.m.Y H:i:s') . ' ' . $status_text . "\n";
		$this->IonLog->log('a123_', $message);

		// Отправляем сообщение в очередь
		$jobId = 123; // можно заменить на реальное значение
		//$this->bus->dispatch(new TestJobMessage($jobId));
		
		$this->bus->dispatch(new TestJobMessageM(
				jobId: 123,
				payload: [
						'email' => 'test@example.com',
						'attempt' => 1,
				]
				));

		// Этот код можно оставить или убрать
		/*
		$Test = new Test2($output);
		$Test->do_it();
		*/

		$io = new SymfonyStyle($input, $output);
		$arg1 = $input->getArgument('arg1');

		if ($arg1)
		{
			$io->note(sprintf('You passed an argument: %s', $arg1));
		}

		if ($input->getOption('option1'))
		{
			$io->note('Option1 is set');
		}

		$io->success('Message dispatched to messenger queue.');

		return Command::SUCCESS;
	}
}
