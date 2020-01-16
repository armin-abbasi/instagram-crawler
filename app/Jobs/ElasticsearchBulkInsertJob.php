<?php

namespace App\Jobs;

use Elasticsearch\ClientBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ElasticsearchBulkInsertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string $index
     */
    private $index;

    /**
     * @var array $data
     */
    private $data;

    /**
     * Create a new job instance.
     *
     * @param $index
     * @param $data
     */
    public function __construct($index, $data)
    {
        $this->index = $index;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ESClient = ClientBuilder::create()->build();
        $dataToIndex = [];

        foreach ($this->data as $data) {
            // Create index meta data.
            $dataToIndex['body'][] = [
                'index' => [
                    '_index' => $this->index,
                    '_id' => $data['_id']
                ]
            ];
            // Create index body.
            $dataToIndex['body'][] = [
                'post_id' => $data['post_id'],
                'caption' => $data['caption'],
                'date' => $data['timestamp'],
                'display_url' => $data['display_url'],
                'user_id' => $data['owner_id']
            ];
        }

        $ESClient->bulk($dataToIndex);
    }
}
