<?php 

namespace App\FileManager;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileManager 
{

    public function __construct(
        private Filesystem $filesystem,
        private SluggerInterface $slugger,
        private KernelInterface $kernel
    ) {}


    public function add(string $path, UploadedFile $file)
    {
        $fileDirectory = $this->kernel->getProjectDir() . $path;
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = $this->slugger->slug($originalFileName) . '-' . uniqid() . '.' . $file->guessExtension();
        $file->move($fileDirectory, $fileName);
        return $fileName;
    }

    // public function check(): bool
    // {}

    public function remove(string $path): bool
    {
        try{
            $fullFilePath = $this->kernel->getProjectDir() . $path;
            $this->filesystem->remove($fullFilePath);
        } catch (IOException $e)
        {
            return false;
        }
        return true;
    }
}