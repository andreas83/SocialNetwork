<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\Filesystem;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\ServerFactory;

class ImageController extends Controller
{
    public function show(Filesystem $filesystem, $path)
    {
        $path = 'public/'.$path;

        $driver = "";

        if (extension_loaded('gd')) {
            $driver = 'gd';
        }
        if (extension_loaded('imagick')) {
            $driver = 'imagick';
        }

        $server = ServerFactory::create([
            'response' => new LaravelResponseFactory(app('request')),
            'source' => $filesystem->getDriver(),
            'cache' => $filesystem->getDriver(),
            'cache_path_prefix' => '.cache',

            'driver' => $driver,
        ]);

        return $server->getImageResponse($path, request()->all());
    }
}
