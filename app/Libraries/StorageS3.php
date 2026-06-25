<?php

namespace App\Libraries;

use Aws\S3\S3Client;

class StorageS3 implements StorageInterface {

    protected $s3;
    protected $bucket;

    public function __construct()
    {
        $this->bucket = AWS_BUCKET;

        $this->s3 = new S3Client([
            'version' => 'latest',
            'region'  => AWS_DEFAULT_REGION,
            'endpoint' => AWS_ENDPOINT,
            'use_path_style_endpoint' => true,
            'suppress_php_deprecation_warning' => AWS_SUPPRESS_PHP_DEPRECATION_WARNING,
            'credentials' => [
                'key'    => AWS_ACCESS_KEY_ID,
                'secret' => AWS_SECRET_ACCESS_KEY,
            ],
        ]);
    }

    public function upload($filePath, $key)
    {
        $result = $this->s3->putObject([
            'Bucket' => $this->bucket,
            'Key'    => $key,
            'SourceFile' => $filePath,
            'ACL'    => 'public-read'
        ]);

        return $result['ObjectURL'];
    }

    public function read($key)
    {
        try {
            $result = $this->s3->getObject([
                'Bucket' => $this->bucket,
                'Key'    => $key,
            ]);
            return (string) $result['Body'];
        } catch (\Aws\S3\Exception\S3Exception $e) {
            return false;
        }
    }

    public function download($key, $saveAs)
    {
        $this->s3->getObject([
            'Bucket' => $this->bucket,
            'Key'    => $key,
            'SaveAs' => $saveAs,
        ]);

        return true;
    }

    public function delete($key)
    {
        $this->s3->deleteObject([
            'Bucket' => $this->bucket,
            'Key'    => $key,
        ]);

        return true;
    }
}
