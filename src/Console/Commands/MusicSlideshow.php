<?php

namespace Bulkmake\LaravelSocialPlay\Console\Commands;
use Illuminate\Support\Facades\Http;

use Illuminate\Console\Command;

class MusicSlideshow extends Command
{
    protected $signature = 'musicslideshow';
    protected $description = 'this command makes the slideshow of images with music';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $images = ['image1.jpg', 'image2.jpg', 'image3.jpg'];
        $audio = 'music.mp3'; // Path to your audio file
        $ffmpegCommand = "ffmpeg";

        foreach ($images as $image) {
            $ffmpegCommand .= " -loop 1 -t 3 -framerate 60 -i \"$image\"";
        }

        // Add audio input
        $ffmpegCommand .= " -i \"$audio\"";

        $filterComplex = '';
        for ($i = 0; $i < count($images); $i++) {
            $filterComplex .= "[$i:v]";
        }
        $filterComplex .= "concat=n=" . count($images) . ":v=1:a=0[outv]";

        // Complete the ffmpeg command with audio mapping
        $ffmpegCommand .= " -filter_complex \"$filterComplex\" -map \"[outv]\" -map " . count($images) . ":a -c:v libx264 -c:a aac -pix_fmt yuv420p output.mp4";

        exec($ffmpegCommand, $output, $return_var);

        if ($return_var === 0) {
            $this->info('Video created successfully with music: output.mp4');
        } else {
            $this->error('An error occurred while creating the video with music.');
        }
    }
}