<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PersistDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $nodes;

    private $model;

    /**
     * Create a new job instance.
     *
     * @param $edges
     */
    public function __construct($edges)
    {
        $this->nodes = $edges;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->model = new Post();

        foreach ($this->nodes as $node) {
            $this->model->insert((array)$node);
        }
    }
}
