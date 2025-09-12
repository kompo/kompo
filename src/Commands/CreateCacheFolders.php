<?php

namespace Kompo\Commands;

use Illuminate\Console\Command;

class CreateCacheFolders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kompo:create-cache-folders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Precreate all cache folders';

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
        $mainPath = storage_path('framework/cache/data');
        $this->createForPath($mainPath);

        $items = scandir($mainPath);

        foreach ($items as $subPath) {
            if (!in_array($subPath, ['.', '..'])) {
                $this->createForPath($mainPath.'/'.$subPath);
            }
        }

        return Command::SUCCESS;
    }

    protected function createForPath($parentPath)
    {
        $chars = ['0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f'];
        foreach ($chars as $char1) {
            foreach ($chars as $char2) {
                $path = $parentPath.'/'.$char1.$char2;
                if (!file_exists($path)) {
                    mkdir($path);
                }
            }
        }
    }
}