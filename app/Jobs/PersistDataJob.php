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

    private $edges;

    /**
     * Create a new job instance.
     *
     * @param $edges
     */
    public function __construct($edges)
    {
        $this->edges = $edges;
    }

    /**
     * Execute the job.
     *
     * @param Post $post
     * @return void
     * @throws \Exception
     */
    public function handle(Post $post)
    {
        try {
            // Persist all nodes into database
            foreach ($this->edges as $edge) {
                $post->insert((array)$edge->node);
            }
        } catch (\Exception $e) {
            \Log::error($e);
            throw new \Exception("Failed to persist posts in the database");
        }
    }
}
