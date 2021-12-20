<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

/**
 * abstracting operations needed for the app the file system
 * Class FileHelper
 * @package App\Service
 */
class FileHelper
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var string
     */
    private $imageDir;

    public function __construct(Filesystem $filesystem, string $imageDir)
    {
        $this->filesystem = $filesystem;
        $this->imageDir = $imageDir;
    }

    /**
     * Copy a file to the image directory
     * @param string $originalFilepath
     * @param string $filename
     */
    public function saveImage(string $originalFilepath, string $filename): void
    {
        $this->filesystem->copy($originalFilepath, sprintf('%s%s', $this->imageDir, $filename));
    }
}