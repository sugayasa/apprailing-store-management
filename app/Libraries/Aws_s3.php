<?php
namespace App\Libraries;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class Aws_s3 {

    protected $s3;
    protected $bucket;

    public function __construct()
    {
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

        $this->bucket = AWS_BUCKET;
    }

    public function upload($filePath, $key)
    {
        try {
            $result = $this->s3->putObject([
                'Bucket' => $this->bucket,
                'Key'    => $key,
                'SourceFile' => $filePath,
                'ACL'    => 'public-read'
            ]);
            return $result['ObjectURL'];
        } catch (AwsException $e) {
            log_message('error', $e->getMessage());
            return false;
        }
    }

    public function delete($key)
    {
        return $this->s3->deleteObject([
            'Bucket' => $this->bucket,
            'Key'    => $key,
        ]);
    }

    public function getUrl($key)
    {
        try {
            return $this->s3->getObjectUrl($this->bucket, $key);
        } catch (AwsException $e) {
            log_message('error', $e->getMessage());
            return false;
        }
    }

    public function getObject($key)
    {
        try {
            return $this->s3->getObject([
                'Bucket' => $this->bucket,
                'Key'    => $key
            ]);
        } catch (AwsException $e) {
            log_message('error', $e->getMessage());
            return false;
        }
    }
}
