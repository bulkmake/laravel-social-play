<?php

namespace Bulkmake\LaravelSocialPlay\Console\Commands;
use Illuminate\Support\Facades\Http;

use Illuminate\Console\Command;

class InstagramCreatePost extends Command
{
    protected $signature = 'instagram:create-post';
    protected $description = 'This is a instagram command for creating the post on instagram';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $iguserId = config('social-play.ig_user_id');
        $accessToken = config('social-play.meta_access_token');

        $children = [];
        // URL for posting the photo
        $url = "https://graph.facebook.com/v21.0/$iguserId/media";

        // Step 1: upload the photo to instagram
        $response = Http::asForm()->post($url, [
            'image_url' => 'https://bulkmake.gumlet.io/public/products/ADE1776S/silver-color-american-diamond-earring-37-17210283241.jpg',
            'is_carousel_item' => 'true',
            //'video_url'=> 'https://videos.pexels.com/video-files/15465878/15465878-hd_720_1280_30fps.mp4',
            //'caption' => 'hello',
            'access_token' => $accessToken,
            //'media_type'=>'REELS',   // Valid Types REELS, CAROUSEL, STORIES
            // 'collaborators' => 'bulkmake'  // Ensure it's published to get the post ID
        ]);

        // Check if the photo upload was successful
        if ($response->successful()) {
            $children[] = $response->json()['id'];
            $this->info('Images uploaded successfully. IDs:');
            foreach ($children as $id) {
                $this->info('- ' . $id);
            }

        } else {
            $this->warn('Error: ' . $response->json('error.message'));
        }

    }
}