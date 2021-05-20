<?php

namespace Kompo\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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
        $uuid = $this->argument('uuid');

        $request = Http::post('http://kompoio.local/api/pull-bundle', [
            'slug' => 'basic-auth'
        ]);

        $kontrollers = $request->json()['kontrollers'];

        foreach ($kontrollers as $kontroller) {
            $this->handleKontroller($kontroller);
        }

        dd($request->json());
    }

    protected function handleKontroller($kontroller)
    {
        if (
            file_exists(base_path($kontroller['path'])) 
            && !$this->confirm('Do you wish to overwrite '.$kontroller['path'].'?')
        ) {
            return;
        }

        file_put_contents(base_path($kontroller['path']), $kontroller['file_contents']);

    }
}