<?php

namespace Bulkmake\LaravelSocialPlay\Console\Commands;
use Illuminate\Support\Facades\Http;

use Illuminate\Console\Command;

class Slideshow extends Command
{
    protected $signature = 'slideshow';
    protected $description = 'this command makes the slideshow of images with blured background';

    public function handle()
    {
        // SCRIPT OPTIONS - CAN BE MODIFIED
        $width = 720;
        $height = 1280;
        $fps = 30;
        $transitionDuration = 1;
        $imageDuration = 2;

        // Array of image filenames
        $images = [
            '2.jpeg',
            '3.jpeg',
            '4.jpeg',
            '5.jpeg',
            '6.jpeg',
        ];

        // Set the directory where the images are located
        $imageDirectory = 'C:\Users\user\Herd\ffmpeg\\'; // Ensure you have a trailing backslash

        // Build full paths for images
        $files = array_map(fn($image) => $imageDirectory . $image, $images);

        // Check if files exist
        foreach ($files as $file) {
            if (!file_exists($file)) {
                $this->error("Error: File does not exist - {$file}");
                return;
            }
        }

        if (count($files) < 2) {
            $this->error('Error: At least two images are required.');
            return;
        }

        // Calculate lengths manually
        $imageCount = count($files);
        $transitionFrameCount = $transitionDuration * $fps;
        $imageFrameCount = $imageDuration * $fps;
        $totalDuration = ($imageDuration + $transitionDuration) * $imageCount - $transitionDuration;
        $totalFrameCount = $totalDuration * $fps;

        $this->info("Video Slideshow Info");
        $this->info("------------------------");
        $this->info("Image count: $imageCount");
        $this->info("Dimension: {$width}x{$height}");
        $this->info("FPS: $fps");
        $this->info("Image duration: {$imageDuration} s");
        $this->info("Transition duration: {$transitionDuration} s");
        $this->info("Total duration: {$totalDuration} s");

        $startTime = microtime(true);

        // Start command
        $fullCommand = "ffmpeg -y ";

        // Add inputs
        foreach ($files as $image) {
            $fullCommand .= "-loop 1 -i \"{$image}\" "; // Use double quotes for the file path
        }

        // Start filter complex
        $fullCommand .= "-filter_complex \"";

        // Prepare blurred inputs
        for ($c = 0; $c < $imageCount; $c++) {
            $fullCommand .= "[{$c}:v]scale={$width}:{$height},setsar=sar=1/1,format=rgba,boxblur=100,setsar=sar=1/1,fps={$fps}[stream" . ($c + 1) . "blurred];";
        }

        // Prepare inputs
        for ($c = 0; $c < $imageCount; $c++) {
            $fullCommand .= "[{$c}:v]scale=w='if(gte(iw/ih,{$width}/{$height}),min(iw,{$width}),-1)':h='if(gte(iw/ih,{$width}/{$height}),-1,min(ih,{$height}))',scale=trunc(iw/2)*2:trunc(ih/2)*2,setsar=sar=1/1,format=rgba[stream" . ($c + 1) . "raw];";
        }

        // Overlay blurred and scaled inputs
        for ($c = 1; $c <= $imageCount; $c++) {
            $fullCommand .= "[stream{$c}blurred][stream{$c}raw]overlay=(main_w-overlay_w)/2:(main_h-overlay_h)/2:format=rgb,setpts=PTS-STARTPTS,split=2[stream{$c}out1][stream{$c}out2];";
        }

        // Apply padding
        for ($c = 1; $c <= $imageCount; $c++) {
            $fullCommand .= "[stream{$c}out1]pad=width={$width}:height={$height}:x=({$width}-iw)/2:y=({$height}-ih)/2,trim=duration={$imageDuration},select=lte(n\,{$imageFrameCount})[stream{$c}overlaid];";
            if ($c === 1) {
                if ($imageCount > 1) {
                    $fullCommand .= "[stream{$c}out2]pad=width={$width}:height={$height}:x=({$width}-iw)/2:y=({$height}-ih)/2,trim=duration={$transitionDuration},select=lte(n\,{$transitionFrameCount})[stream{$c}ending];";
                }
            } elseif ($c < $imageCount) {
                $fullCommand .= "[stream{$c}out2]pad=width={$width}:height={$height}:x=({$width}-iw)/2:y=({$height}-ih)/2,trim=duration={$transitionDuration},select=lte(n\,{$transitionFrameCount}),split=2[stream{$c}starting][stream{$c}ending];";
            } elseif ($c === $imageCount) {
                $fullCommand .= "[stream{$c}out2]pad=width={$width}:height={$height}:x=({$width}-iw)/2:y=({$height}-ih)/2,trim=duration={$transitionDuration},select=lte(n\,{$transitionFrameCount})[stream{$c}starting];";
            }
        }

        // Create transition frames
        for ($c = 1; $c < $imageCount; $c++) {
            $fullCommand .= "[stream" . ($c + 1) . "starting][stream{$c}ending]blend=all_expr='A*(if(gte(T,{$transitionDuration}),{$transitionDuration},T/{$transitionDuration}))+B*(1-(if(gte(T,{$transitionDuration}),{$transitionDuration},T/{$transitionDuration})))',select=lte(n\,{$transitionFrameCount})[stream" . ($c + 1) . "blended];";
        }

        // Begin concat
        for ($c = 1; $c < $imageCount; $c++) {
            $fullCommand .= "[stream{$c}overlaid][stream" . ($c + 1) . "blended]";
        }

        // End concat
        $fullCommand .= "[stream{$imageCount}overlaid]concat=n=" . (2 * $imageCount - 1) . ":v=1:a=0,format=yuv420p[video]\"";

        // End command
        $fullCommand .= " -map [video] -async 1 -rc-lookahead 0 -g 0 -profile:v main -level 42 -c:v libx264 -r {$fps} C:/Users/user/Herd/ffmpeg/out.mp4";

        // Execute the command
        exec($fullCommand, $output, $return_var);

        if ($return_var === 0) {
            $elapsedTime = microtime(true) - $startTime;
            $this->info("Slideshow created in " . round($elapsedTime, 2) . " seconds");
        } else {
            $this->error("An error occurred while creating the video.");
        }
    }
}