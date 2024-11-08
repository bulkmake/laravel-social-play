<?php

namespace Bulkmake\LaravelSocialPlay\Console\Commands;

use Illuminate\Console\Command;

class ExampleCommand extends Command
{
    protected $signature = 'socialplay:example';
    protected $description = 'This is an example command for the social play package';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Example command executed!');
    }
}