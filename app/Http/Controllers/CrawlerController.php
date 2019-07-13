<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CrawlerController extends Controller
{
    public function getPosts()
    {
        $uri = 'https://www.instagram.com/explore/tags/%D9%81%D8%B1%D8%A7%D8%AA%D8%B1%D8%A8%D8%B1%D9%88/?__a=1';

        try {
            $this->getNodes($uri);
            return Post::all()->count();
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }

    private function getNodes($uri, $maxId = null)
    {
        $client = new Client();
        $post = new Post();

        // Add Max ID if included.
        $uri = $maxId ? $uri . '&max_id=' . $maxId : $uri;

        $response = json_decode($client->request('GET', $uri)->getBody()->getContents());

        $edge_info = $response->graphql->hashtag->edge_hashtag_to_media;

        foreach ($edge_info->edges as $edge) {
            $post->insert((array)$edge);
        }

        if ($edge_info->page_info->has_next_page) {
            $this->getNodes($uri, $edge_info->page_info->end_cursor);
        }
    }
}
