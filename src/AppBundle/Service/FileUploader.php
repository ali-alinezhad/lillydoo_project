<?php

namespace AppBundle\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public const IMAGE_URL = '../web/images/';


    /**
     * @param UploadedFile $file
     *
     * @return string|null
     */
    public function upload(UploadedFile $file): ?string
    {
        srand(mktime());
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName         = $originalFilename . '-' . rand() . '.' . $file->guessExtension();

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
