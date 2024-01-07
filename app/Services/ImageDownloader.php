<?php

namespace App\Services;

use App\Actions\File\UploadFile;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageDownloader
{
    private Client $http;

    public function __construct(Client $http)
    {
        $this->http = $http;

    }

    public function execute($url, $params, $storage = 'public')
    {
        $image = $this->downloadRemoteImage($url);
        $return = app(UploadFile::class)->execute($storage, $image, $params);

        if (file_exists($image->getRealPath()) && is_writable($image->getRealPath())) {
            unlink($image->getRealPath());
        }

        return $return;
    }


    private function downloadRemoteImage($url): UploadedFile
    {
        $content =  $this->http->request('GET', $url)->getBody()->getContents();
        $filename = Str::random(40) . '.' . pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        $tempPath = Str::random(15) .'/' . $filename;

        Storage::disk('local')->put($tempPath, $content);
        $path = Storage::disk('local')->path($tempPath);
        return new UploadedFile($path, $filename);
    }


}
