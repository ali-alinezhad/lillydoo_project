<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\FileUploader;
use PHPUnit\Util\Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploaderTest extends WebTestCase
{
    private FileUploader $fileUploader;


    protected function setUp(): void
    {
        $this->fileUploader = new FileUploader();
    }


    public function testUpload(): void
    {
        srand(mktime());
        $originalFilename = \uniqid('originalName');
        $fileName         = $originalFilename . '-' . rand() . '.jpg';

        $file = $this->createMock(UploadedFile::class);

        $file->expects(static::once())
            ->method('getClientOriginalName')
            ->willReturn($originalFilename);

        $file->expects(static::once())
            ->method('guessExtension')
            ->willReturn('jpg');

        $file->expects(static::once())
            ->method('move')
            ->with(static::equalTo(FileUploader::IMAGE_URL), static::equalTo($fileName))
            ->willReturn($file);


        static::assertStringContainsString($fileName, $this->fileUploader->upload($file));
    }


    public function testUploadWhenThrowException(): void
    {
        srand(mktime());
        $originalFilename = \uniqid('originalName');
        $fileName         = $originalFilename . '-' . rand() . '.jpg';

        $file = $this->createMock(UploadedFile::class);

        $file->expects(static::once())
            ->method('getClientOriginalName')
            ->willReturn($originalFilename);

        $file->expects(static::once())
            ->method('guessExtension')
            ->willReturn('jpg');

        $file->expects(static::once())
            ->method('move')
            ->with(static::equalTo(FileUploader::IMAGE_URL), static::equalTo($fileName))
            ->willThrowException(new FileException);

        static::assertNull($this->fileUploader->upload($file));
    }


    public function testGetTargetDirectory(): void
    {
        static::assertEquals(FileUploader::IMAGE_URL, $this->fileUploader->getTargetDirectory());
    }
}
