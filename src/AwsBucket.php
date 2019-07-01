<?php

namespace AwsBucket;

use \Aws\S3\S3Client;

class AwsBucket
{
    /**
     * Constructor
     * @param S3Client $s3Client
     */
    public function __construct(S3Client $s3Client)
    {
        $this->s3Client = $s3Client;
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
        $s3Config = config('amazon.s3');

        $result = $this->s3Client->putObject([
            'Bucket' => $s3Config['bucket'],
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
        $s3Config = config('amazon.s3');

        return $this->s3Client->listObjects([
            'Bucket' => $s3Config['bucket'],
        ])->toArray();
    }

    /**
     * Delete files of AwsBucketHelper
     * @param string $fileName key of the file
     * @return string
     */
    public function deleteFile(string $fileName) {
        $s3Config = config('amazon.s3');
        return $this->s3Client->deleteObject([
            'Bucket' => $s3Config['bucket'],
            'Key' => $fileName,
        ])->toArray();
    }
}