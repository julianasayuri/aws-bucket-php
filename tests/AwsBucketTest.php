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
        $sqsClientSpy = Mockery::spy(S3Client::class);
        $awsBucket = new AwsBucket($sqsClientSpy);
        $this->assertInstanceOf(AwsBucket::class, $awsBucket);
    }

    /**
     * @covers AwsBucket\AwsBucket::setS3Config
     */
    public function testSetS3Config()
    {
        $sqsClientSpy = Mockery::spy(S3Client::class);
        $awsBucket = new AwsBucket($sqsClientSpy);

        $config = [
            'bucket' => 'bucket'
        ];

        $return = $awsBucket->setS3Config($config);
        $this->assertNull($return);
    }

    /**
     * @covers AwsBucket\AwsBucket::putFile
     */
    public function testPutFile()
    {
        file_put_contents('tests/file_name.ext', 'test');
        $result = [
            'ObjectURL' => 'https://url/file.ext',
        ];

        $uploadedFileMock = Mockery::mock(UploadedFile::class);
        $uploadedFileMock->shouldReceive('getClientOriginalName')
            ->once()
            ->withAnyArgs()
            ->andReturn('file_name');

        $uploadedFileMock->shouldReceive('getClientOriginalExtension')
            ->once()
            ->withAnyArgs()
            ->andReturn('ext');

        $sqsClientMock = Mockery::mock(S3Client::class);
        $sqsClientMock->shouldReceive('putObject')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $sqsClientMock->shouldReceive('toArray')
            ->once()
            ->withAnyArgs()
            ->andReturn($result);

        $awsBucket = new AwsBucket($sqsClientMock);
        $file = $awsBucket->putFile($uploadedFileMock, 'ext');
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
            ->andReturn($result);

        $awsBucket = new AwsBucket($sqsClientMock);
        $list = $awsBucket->listFiles();
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
            ->andReturn($result);

        $awsBucket = new AwsBucket($sqsClientMock);
        $deleted = $awsBucket->deleteFile('file.ext');
        $this->assertEquals($deleted, $result);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}