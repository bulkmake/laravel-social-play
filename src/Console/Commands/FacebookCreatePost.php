<?php

namespace Bulkmake\LaravelSocialPlay\Console\Commands;

use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;

class FacebookCreatePost extends Command
{
    protected $signature = 'facebook:create-post {mediaType}';
    protected $description = 'This is a Facebook command for creating a post on Facebook';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $mediaType = $this->argument('mediaType');
        $pageId = config('social-play.fb_page_id');
        $accessToken = config('social-play.meta_access_token');
        $mediaUrl = 'https://bulkmake.gumlet.io/public/products/ADE1776S/silver-color-american-diamond-earring-37-17210283241.jpg';

        $url = "https://graph.facebook.com/v21.0/$pageId/$mediaType";

        // Step 1: Post the photo to Facebook
        $response = Http::asForm()->post($url, [
            'url' => $mediaUrl,
            'message' => 'hello',  // Optional message with the photo
            'access_token' => $accessToken,
            'published' => 'true'  // Ensure it's published to get the post ID
        ]);

        if ($response->successful()) {
            $this->info($mediaType . ' posted successfully! Working on Comments');
            $responseBody = $response->json();
            $postId = $responseBody['id'];

            // Step 2: Post a comment on the photo
            $commentResponse = Http::asForm()->post("https://graph.facebook.com/v21.0/$postId/comments", [
                'message' => 'Good',
                'access_token' => $accessToken
            ]);

            if ($commentResponse->successful()) {
                $this->info('Comment posted successfully!');
            } else {
                $this->warn('Error: ' . $response->json('error.message'));
            }
        } else {
            $this->warn('Error: ' . $response->json('error.message'));
        }
    }
}