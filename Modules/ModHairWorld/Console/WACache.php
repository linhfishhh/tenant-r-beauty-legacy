<?php

namespace Modules\ModHairWorld\Console;

use Illuminate\Console\Command;
use Modules\ModHairWorld\Entities\Salon;
use Modules\ModHairWorld\Entities\SalonService;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class WACache extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'wa:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache Salon Service Relationship Value';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $task = $this->choice('Chọn tác vụ', ['all', 'service', 'salon'], 0);


        if($task == 'all' || $task == 'service'){
            $this->line('Step 1/2: Cache Salon Services');
            $services = SalonService::whereHas('salon')
                ->with(['sale_off'])
                ->get();
            $total = $services->count();
            $this->line($total.' service to perform');
            $bar = $this->output->createProgressBar($total);
            $bar->start();
            foreach ($services as $service){
                $service->cacheSale();
                $bar->advance();
            }
            $bar->finish();
            $this->line('');
            $this->line('Step 1/2: Done');
        }

        if($task == 'all' || $task =='salon'){
            $this->line('Step 2/2: Cache Salon Info');
            $salons = Salon::with([
                'location_lv1',
                'location_lv2',
                'location_lv3'
            ])->get();
            $total = $salons->count();
            $this->line($total.' service to perform');
            $bar = $this->output->createProgressBar($total);
            $bar->start();
            foreach ($salons as $salon){
                if($task != 'all'){
                    $salon->cacheSale();
                }
                $salon->cacheBookingCount();
                $bar->advance();
            }
            $bar->finish();
            $this->line('');
            $this->line('Step 2/2: Done');
        }
    }


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
