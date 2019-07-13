<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CrawlerController extends Controller
{
    private $baseURI = 'https://www.instagram.com/explore/tags/%D9%81%D8%B1%D8%A7%D8%AA%D8%B1%D8%A8%D8%B1%D9%88/?__a=1';

    public function getPosts()
    {
        try {
            $this->getNodes();
            return Post::all()->count();
//            return \Redis::command('DBSIZE');
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }

    private function getNodes($maxId = null)
    {
        $client = new Client();
        $post = new Post();
        $uri = $this->baseURI;

        // Add Max ID if included.
        if ($maxId) {
            $uri .= '&max_id=' . $maxId;
        }

        $response = json_decode($client->request('GET', $uri)->getBody()->getContents());

        $edge_info = $response->graphql->hashtag->edge_hashtag_to_media;

        foreach ($edge_info->edges as $key => $edge) {
            $post->insert((array)$edge);
        }

        /*\Redis::pipeline(function ($pipe) use ($edge_info) {
            foreach ($edge_info->edges as $key => $edge) {
                $pipe->set($edge->node->id, json_encode($edge));
            }
        });*/

        if ($edge_info->page_info->has_next_page) {
            $this->getNodes($edge_info->page_info->end_cursor);
        }
    }
}
