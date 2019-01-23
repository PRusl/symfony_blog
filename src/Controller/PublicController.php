<?php

namespace App\Controller;

use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/public")
 */
class PublicController extends AbstractController
{
    /**
     * @Route("/uploads/article_files/{fileName}", name="public_uploads", methods={"GET"})
     */
    public function index($fileName, FileUploader $fileUploader): Response
    {
        $file = $fileUploader->download($fileName);

        return $this->file($file);
    }
}