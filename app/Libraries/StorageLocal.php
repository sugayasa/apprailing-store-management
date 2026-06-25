<?php

namespace App\Libraries;

class StorageLocal implements StorageInterface {

    protected $basePath;

    public function __construct()
    {
        $this->basePath = PATH_STORAGE;
    }

    public function upload($filePath, $key)
    {
        if (is_uploaded_file($filePath)) {
            return move_uploaded_file($filePath, $key);
        } else {
            return copy($filePath, $key);
        }
    }

    public function read($path)
    {
        $file = $path;
        return file_exists($file) ? file_get_contents($file) : null;
    }

    public function delete($path)
    {
        $file = $this->basePath . $path;
        return file_exists($file) ? unlink($file) : false;
    }
}
