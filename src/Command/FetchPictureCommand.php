<?php

namespace App\Command;

use App\Entity\Image;
use App\Service\FileHelper;
use App\Service\ImageFactory;
use App\Service\NasaApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FetchPictureCommand
 * @package App\Command
 */
class FetchPictureCommand extends Command
{
    private const MEDIA_TYPE_IMAGE = "image";

    protected static $defaultName = "app:fetch-picture";

    /**
     * @var NasaApiClient
     */
    private $nasaApiClient;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ImageFactory
     */
    private $imageFactory;
    /**
     * @var FileHelper
     */
    private $fileHelper;

    public function __construct(
        NasaApiClient $nasaApiClient,
        EntityManagerInterface $entityManager,
        ImageFactory $imageFactory,
        FileHelper $fileHelper
    ) {
        parent::__construct();

        $this->nasaApiClient = $nasaApiClient;
        $this->entityManager = $entityManager;
        $this->imageFactory = $imageFactory;
        $this->fileHelper = $fileHelper;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date = new \DateTime();
        $image = $this->entityManager->getRepository(Image::class)->findOneBy(['date' => $date]);

        if (null !== $image) {
            $output->writeln("The image of the day is already saved.");
            return Command::SUCCESS;
        }

        $apiData = $this->nasaApiClient->fetchPicture($date);

        if (self::MEDIA_TYPE_IMAGE !== $apiData['media_type']) {
            $output->writeln("There's no picture today.");
            return Command::SUCCESS;
        }

        $image = $this->imageFactory->createFromNasaData($apiData);

        $this->fileHelper->saveImage($apiData['url'], $image->getImage());

        $this->entityManager->persist($image);
        $this->entityManager->flush();

        $output->writeln("The image of the day has been saved successfully.");

        return Command::SUCCESS;
    }
}