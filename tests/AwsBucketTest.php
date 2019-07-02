<?php

namespace AwsBucketTest;

use AwsBucket\AwsBucket;
use \Mockery;
use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use PHPUnit\Framework\TestCase;

class AwsBucketHelperTest extends TestCase
{
    /**
     * @covers AwsBucket\AwsBucket::__construct
     */
    public function testAwsBucketHelperCanBeInstanciated()
    {
        $configs = [];
        $awsBucket = new AwsBucket($configs);
        $this->assertInstanceOf(AwsBucket::class, $awsBucket);
    }

    /**
     * @covers AwsBucket\AwsBucket::putFile
     */
    public function testPutFile()
    {
        $result = [
            'ObjectURL' => 'https://url/file.ext',
        ];

        $sqsClientMock = Mockery::mock(S3Client::class);
        $sqsClientMock->shouldReceive('putObject')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $sqsClientMock->shouldReceive('toArray')
            ->once()
            ->withAnyArgs()
            ->andReturn($result)
            ->getMock();

        $awsBucketPartialMock = Mockery::mock(AwsBucket::class)
            ->makePartial();

        $awsBucketPartialMock->shouldReceive('newS3Client')
            ->once()
            ->andReturn($sqsClientMock);

        $content = 'this is your file content';
        $name = 'sample';
        $extension = 'txt';

        $file = $awsBucketPartialMock->putFile($content, $name, $extension);
        $this->assertEquals($file, 'https://url/file.ext');
    }

    /**
     * @covers AwsBucket\AwsBucket::listFiles
     */
    public function testListFiles()
    {
        $result = [];

        $sqsClientMock = Mockery::mock(S3Client::class);
        $sqsClientMock->shouldReceive('listObjects')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $sqsClientMock->shouldReceive('toArray')
            ->once()
            ->withAnyArgs()
            ->andReturn($result)
            ->getMock();

        $awsBucketPartialMock = Mockery::mock(AwsBucket::class)
            ->makePartial();

        $awsBucketPartialMock->shouldReceive('newS3Client')
            ->once()
            ->andReturn($sqsClientMock);

        $list = $awsBucketPartialMock->listFiles();
        $this->assertEquals($list, []);
    }

    /**
     * @covers AwsBucket\AwsBucket::deleteFile
     */
    public function testDeleteFile()
    {
        $result = 'https://url/file.ext';

        $sqsClientMock = Mockery::mock(S3Client::class);
        $sqsClientMock->shouldReceive('deleteObject')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $sqsClientMock->shouldReceive('toArray')
            ->once()
            ->withAnyArgs()
            ->andReturn($result)
            ->getMock();

        $awsBucketPartialMock = Mockery::mock(AwsBucket::class)
            ->makePartial();

        $awsBucketPartialMock->shouldReceive('newS3Client')
            ->once()
            ->andReturn($sqsClientMock);

        $deleted = $awsBucketPartialMock->deleteFile('file.ext');
        $this->assertEquals($deleted, $result);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
