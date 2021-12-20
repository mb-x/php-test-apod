<?php

namespace App\Service;

use App\Entity\Image;

/**
 * A factory for creating Image Class instances
 * Class ImageFactory
 * @package App\Service
 */
class ImageFactory
{
    /**
     * Creating Image instances from NASA API DATA
     * @param array $apiData
     * @return Image
     * @throws \Exception
     */
    public function createFromNasaData(array $apiData): Image
    {
        $date = new \DateTime();
        $pathParts = pathinfo($apiData['url']);
        $fileName = sprintf('%s.%s', $date->format('d-m-Y'), $pathParts['extension']);

        $image = new Image();
        $image->setTitle($apiData['title'])
            ->setDate($date)
            ->setExplanation($apiData["explanation"])
            ->setImage($fileName);

        return $image;
    }
}