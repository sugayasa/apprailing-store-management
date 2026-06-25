<?php

namespace App\Libraries;

class StorageFactory {

    public static function make()
    {
        return AWS_STORAGE_ENABLED
            ? new \App\Libraries\StorageS3()
            : new \App\Libraries\StorageLocal();
    }
}
