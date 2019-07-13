<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CrawlerController extends Controller
{
    public function getPosts()
    {
        $uri = 'https://www.instagram.com/explore/tags/%D9%81%D8%B1%D8%A7%D8%AA%D8%B1%D8%A8%D8%B1%D9%88/?__a=1';
        $post = new Post();
        $client = new Client();

        try {
            $response = json_decode($client->request('GET', $uri)->getBody()->getContents());

            $edges = $response->graphql->hashtag->edge_hashtag_to_media->edges;

            foreach ($edges as $edge) {
                $post->insert((array)$edge);
            }

            return $post->count();
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
