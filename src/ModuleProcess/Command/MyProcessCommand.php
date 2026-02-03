<?php

namespace App\ModuleProcess\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


use App\ModuleProcess\Aion\Test2;
use App\ModuleProcess\Aion\IonLog;
use App\ModuleProcess\Aion\JsonPretty;

use App\Service\CampaignsGet;
//use App\Service\CampaignGetDirect;

#[AsCommand(
    //name: 'MyProcessCommand',
    name: 'app:process-command',
    description: 'Add a short description for your command',
)]
class MyProcessCommand extends Command
{
    /*	
    public function __construct()
    {
        parent::__construct();
    }
    */

    private string $projectDir;
    private IonLog $IonLog;
    
    private CampaignsGet $campaignsService;
    private JsonPretty $JsonPretty;
    

    public function __construct(string $projectDir, CampaignsGet $campaignsService)
    {
        parent::__construct();

        $this->IonLog = new IonLog($projectDir);
        $this->campaignsService = $campaignsService;
        $this->projectDir = $projectDir;
        $this->JsonPretty = new JsonPretty($projectDir);

    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        
        $this->IonLog->logMethod("start->");

        
        


        goto step1;
//        goto step2;

// -----------------------------------------------------------------------------
// Шаг1 Получение списка кампаний
// campaignsService->getActiveCampaigns()
// -----------------------------------------------------------------------------

        step1:

        //@todo taskp2554 12.11.2025 12:55 step1
        
        $this->IonLog->logMethod("Шаг1 Получение списка кампаний");

        $campaigns = $this->campaignsService->getActiveCampaigns();

        $jsonListCampaigns = json_encode($campaigns);

        
        $p = [
        		'file_folder' => 'campaigns',
        		'file_pref' => '',
        		'file_name' => 'campaigns_list',
        		'data' => $jsonListCampaigns,
        		//'date_suff' => true,  // true - добавлять дату в имя файла
        		'date_suff' => false    // false - не добавлять дату в имя файла
        ];
        
        $this->JsonPretty->prepare_and_save($p);
        
        /*
        $fileCampaigns = $this->projectDir . '/var/data/' . 'campaigns.json';
        file_put_contents($fileCampaigns, $jsonListCampaigns);
        */

        /*
        $Test = new Test2($output);
        $Test->do_it();
        */

// -----------------------------------------------------------------------------
//
//
// -----------------------------------------------------------------------------
// Шаг2 Вытащить id кампании
//
// -----------------------------------------------------------------------------

        step2:
        
        $this->IonLog->logMethod("Шаг2 Вытащить id кампании");
        
        $fileCampaigns = $this->projectDir . '/var/data/'.$p['file_folder'].'/' . $p['file_name'].'.json';
        $jsonListCampaignsGet = file_get_contents($fileCampaigns);
        $arrayListCampaignsGet = json_decode($jsonListCampaignsGet, true);
        
        

        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
            $option = $input->getOption('option1');
            $a = 1;
        }
        
        
        $this->IonLog->logMethod("---finish---");

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');


        return Command::SUCCESS;
    }


}
