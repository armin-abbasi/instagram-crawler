<?php

namespace App\Console\Commands;

use App\Jobs\ElasticsearchBulkInsertJob;
use App\Models\Post;
use Illuminate\Console\Command;

class IndexPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:index';

    const CHUNK_SIZE = 150;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index all the retrieved posts in elasticsearch.';

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
     * @return mixed|void
     */
    public function handle()
    {
        $this->line('<fg=yellow>Indexing into elasticsearch...</>');

        Post::chunk(self::CHUNK_SIZE, function ($chunks) {
            $this->line('<fg=green>Queuing ' . count($chunks) . ' records for indexing</>');
            dispatch(new ElasticsearchBulkInsertJob('feeds', $chunks->toArray()));
        });
    }
}
