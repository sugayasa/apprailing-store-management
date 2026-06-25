<?php

namespace App\Libraries;

interface StorageInterface {
    public function upload($filePath, $key);
    public function read($key);
    public function delete($key);
}