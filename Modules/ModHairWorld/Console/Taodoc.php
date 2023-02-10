<?php

namespace Modules\ModHairWorld\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class taodoc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:tao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate API documents';

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
        $this->call('api:generate', [
            '--routePrefix' => "api/manager/*",
            '--force' => 'true',
            '--output' => 'public_html/docs'
        ]);

        $file = base_path('public_html/docs/index.html');
        if (file_exists($file)) {
            $html = file_get_contents($file);

            $html = str_replace('\n', '<br>', $html);
            $html = str_replace('"{', '{', $html);
            $html = str_replace('}"', '}', $html);
            $html = str_replace('\"', '"', $html);

            file_put_contents($file, $html);
        }
    }
}
