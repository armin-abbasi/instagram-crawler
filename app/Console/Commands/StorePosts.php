<?php

namespace App\Console\Commands;

use App\Jobs\PersistDataJob;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class StorePosts extends Command
{

    const BASE_URI = 'https://www.instagram.com/explore/tags/:hashtag/?__a=1';

    /**
     * @var string $hashTag
     */
    public $hashTag = 'فراتربرو';

    /**
     * @var $client
     */
    private $client;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:store {--hashtag=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve instagram posts based on an hash-tag and persist all of it in database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->client = new Client();
    }

    /**
     * Execute the console command.
     *
     * @return mixed|void
     */
    public function handle()
    {
        $this->hashTag = $this->option('hashtag') ?? $this->hashTag;

        try {
            // Call recursive function and dispatch in a queue
            $this->getNodes();

            $this->info('Retrieved all data successfully!');

        } catch (\Exception $e) {
            
            \Log::error($e);

            $this->warn('Operation failed due to the following reasons: ');

            $this->error($e->getMessage());
        }
    }

    /**
     * Recursive function to retrieve all the nodes.
     * 
     * @param string|null $maxId
     */
    private function getNodes($maxId = null)
    {
        $uri = str_replace(':hashtag', $this->hashTag, self::BASE_URI);

        // Add Max ID if included.
        if ($maxId) {
            $uri .= '&max_id=' . $maxId;
        }

        $response = json_decode($this->client->request('GET', $uri)->getBody()->getContents());

        $edge_info = $response->graphql->hashtag->edge_hashtag_to_media;

        $this->line('Dispatching a persist job with ' . count($edge_info->edges) . ' number of nodes');

        dispatch(new PersistDataJob($edge_info->edges));

        if ($edge_info->page_info->has_next_page) {
            $this->getNodes($edge_info->page_info->end_cursor);
        }
    }
}
