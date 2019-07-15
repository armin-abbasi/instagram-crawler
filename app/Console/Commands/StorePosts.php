<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StorePosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'retrieve instagram posts based on an hash-tag and persist all of it in database';

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
        
    }
}
