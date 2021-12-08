<?php

namespace app\Models;

class Banner {

    protected string $imageUri;
    protected string $imageFormat;
    protected string $imageB64;

    public function setImageUri($uri) {

        $data = explode(',',$uri);
        $meta = explode('/',$data['0']);

        switch($meta['1']) {
            case 'gif':
                $this->imageFormat = 'gif';
                break;
            case 'png':
                $this->imageFormat = 'png';
                break;
            default:
                $this->imageFormat = 'jpg';
                break;
        }

        $this->imageUri = $uri;
        $this->imageB64 = $data['1'];
    }

    public function getImageFormat(): string {
        return $this->imageFormat ?? 'jpg';
    }

    public function display() {

        header('Content-type:image/'.$this->imageFormat);
        print_r(base64_decode($this->imageB64));
    }

}