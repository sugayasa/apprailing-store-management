<?php

namespace App\Libraries;

class ImageRenderer {

    public function render($imageData, $extension, $defaultImage = 'noImage')
    {
        if (!$imageData) {
            $this->noimage($defaultImage);
            return;
        }

        $image = @imagecreatefromstring($imageData);

        if (!$image) {
            $this->noimage('brokenimage');
            return;
        }

        switch (strtolower($extension)) {

            case 'jpg':
            case 'jpeg':
                header("Content-Type: image/jpeg");
                imagejpeg($image);
                break;

            case 'png':
                $background = imagecolorallocatealpha($image, 0, 0, 0, 127);
                imagecolortransparent($image, $background);
                imagealphablending($image, false);
                imagesavealpha($image, true);

                header("Content-Type: image/png");
                imagepng($image);
                break;

            default:
                if ($defaultImage !== 'noImage') {
                    $this->defaultlogobank();
                } else {
                    $this->noimage("noimage");
                }
                imagedestroy($image);
                return;
        }

        imagedestroy($image);
        exit;
    }

    protected function noimage($type)
    {
        header("Content-Type: image/jpg");
        readfile(FCPATH . "img/" . $type);
        exit;
    }

    protected function defaultlogobank()
    {
        header("Content-Type: image/png");
        readfile(FCPATH . "img/default-bank-logo.png");
        exit;
    }
}
