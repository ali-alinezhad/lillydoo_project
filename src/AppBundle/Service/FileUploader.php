<?php

namespace AppBundle\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    protected const IMAGE_URL = '../web/images/';


    /**
     * @param UploadedFile $file
     *
     * @return string|null
     */
    public function upload(UploadedFile $file): ?string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName         = $originalFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        }
        catch (FileException $e) {
            return null;
        }

        return 'images/' . $fileName;
    }


    /**
     * @param $fileName
     *
     * @return void|null
     */
    public function remove($fileName)
    {
        $fileSystem = new Filesystem();
        try {
            $fileSystem->remove('../web/' . $fileName);
        }
        catch (FileException $e) {
            return null;
        }
    }


    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return self::IMAGE_URL;
    }
}