<?php

namespace AwsBucket;

use \Aws\S3\S3Client;

class AwsBucket
{
    public $s3Client;
    public $s3Config;

    /**
     * Constructor
     * @param S3Client $s3Client
     */
    public function __construct(S3Client $s3Client)
    {
        $this->s3Client = $s3Client;
    }

    /**
     * Set S3Client Configs
     * @param string $filePatch patch of the file
     * @param string $extension file extension
     * @return string
     */
    public function setS3Config(Array $s3Config)
    {
        $this->s3Config = $s3Config;
    }

    /**
     * PutFile into AwsBucketHelper
     * @param string $filePatch patch of the file
     * @param string $extension file extension
     * @return string
     */
    public function putFile($file)
    {
        $fileName = md5(rand(1, 999) . $file->getClientOriginalName());
        $extension = $file->getClientOriginalExtension();

        $result = $this->s3Client->putObject([
            'Bucket' => $this->s3Config['bucket'],
            'Key' => $fileName .'.'. $extension,
            'SourceFile' => $file,
            'ContentType' => 'text/csv',
            'ACL' => 'public-read',
        ])->toArray();
        
        return $result['ObjectURL'];
    }

    /**
     * List all file of AwsBucketHelper
     * @return array
     */
    public function listFiles()
    {
        return $this->s3Client->listObjects([
            'Bucket' => $this->s3Config['bucket'],
        ])->toArray();
    }

    /**
     * Delete files of AwsBucketHelper
     * @param string $fileName key of the file
     * @return string
     */
    public function deleteFile(string $fileName)
    {
        return $this->s3Client->deleteObject([
            'Bucket' => $this->s3Config['bucket'],
            'Key' => $fileName,
        ])->toArray();
    }
}