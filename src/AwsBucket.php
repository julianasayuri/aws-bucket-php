<?php

namespace AwsBucket;

use \Aws\S3\S3Client;

class AwsBucket
{
    public $s3Config;

    /**
     * Constructor
     * @param Array $s3Config
     */
    public function __construct(array $s3Config)
    {
        $this->s3Config = $s3Config;
    }

    /**
     * PutFile into AwsBucket
     * @param string $content content of file
     * @param string $name file name
     * @param string $extension file extension
     * @return string
     */
    public function putFile($content, $name, $extension)
    {
        $fileName = md5(rand(1, 999) . $name);

        $s3Client = $this->newS3Client();

        $result = $s3Client->putObject([
            'Bucket' => $this->s3Config['bucket'],
            'Key' => $fileName .'.'. $extension,
            'Body' => $content,
            'ContentType' => 'text/csv',
            'ACL' => 'public-read',
        ])->toArray();
        
        return $result['ObjectURL'];
    }

    /**
     * List all file of AwsBucket
     * @return array
     */
    public function listFiles()
    {   
        $s3Client = $this->newS3Client();

        return $s3Client->listObjects([
            'Bucket' => $this->s3Config['bucket'],
        ])->toArray();
    }

    /**
     * Delete files of AwsBucket
     * @param string $fileName key of the file
     * @return string
     */
    public function deleteFile(string $fileName)
    {
        $s3Client = $this->newS3Client();

        return $s3Client->deleteObject([
            'Bucket' => $this->s3Config['bucket'],
            'Key' => $fileName,
        ])->toArray();
    }

    /**
     * @codeCoverageIgnore
     * method newS3Client
     * create S3 Client instance
     * @return \Aws\S3\S3Client
     */
    public function newS3Client()
    {
        return new S3Client($this->s3Config);
    }
}
