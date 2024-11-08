<?php

namespace Bulkmake\LaravelSocialPlay\Console\Commands;
use Illuminate\Support\Facades\Http;

use Illuminate\Console\Command;

class PinterestCreatePost extends Command
{
    protected $signature = 'Pinterest:create-post';
    protected $description = 'This is a pnterest command for creating the post on pnterest';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Define the Pinterest API URL for retrieving boards
        $url = 'https://api.pinterest.com/v5/boards';

        // Make the GET request to the Pinterest API
        $response = Http::withHeaders([
           'Authorization' => "Bearer ". config('social-play.pinterest_access_token'),
        ])->get($url);

        // Check if the request was successful
        if ($response->successful()) {
            $jsonPath = base_path('boards.json');
            file_put_contents($jsonPath, $response->getBody());
            $this->info('Boards retrieved successfully!');
        } else {
            $this->error('Error retrieving boards: ' . $response->json('message'));
        }
    }
}