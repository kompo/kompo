<?php

namespace Kompo\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class PullCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kompo:pull {uuid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull a specific set of files from kompo.io';

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
        $request = Http::post('https://kompo.io/api/pull-bundle/'.$this->argument('uuid'));

        $pull = $request->json();

        $items = $pull['pull_items'];

        foreach ($items as $item) {
            $this->handleFile($item['item']);
        }

        $this->info('Completed!');
    }

    protected function handleFile($file)
    {
        $filePath = base_path($file['path']);

        if (
            file_exists($filePath) 
            && (
                $this->fileContentsAreTheSame($filePath, $file['file_contents'])
                || !$this->confirm('Do you wish to overwrite '.$file['path'].'?')
            )
        ) {
            $this->line('Ignoring '.$filePath);
            return;
        }

        $this->info('Writing '.$filePath);

        (new Filesystem)->ensureDirectoryExists(Str::beforeLast($filePath, '/'));

        file_put_contents($filePath, $file['file_contents']);

    }

    protected function fileContentsAreTheSame($filePath, $newContents)
    {
        return file_get_contents($filePath) === $newContents;
    }
}