<?php

declare(strict_types=1);

namespace App\FileLoader;

use App\Contracts\FileLoader\FileLoaderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

final class FileLoader implements FileLoaderInterface
{
    /**
     * @var ParameterBagInterface
     */
    private $params;
    private $inputDir;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->inputDir = $this->params->get("INPUT_DIR");
    }

    /**
     * @param $fileName
     * @return string
     */
    public function load(string $fileName): string
    {
        $finder = new Finder();
        // Get exactly by file name
        $finder->files()->in($this->inputDir)->name($fileName);

        if (!$finder->hasResults()) {
            throw new FileNotFoundException($fileName);
        }

        $contents = "";
        // TODO: Revisit the foreach loop
        // Only one iteration
        foreach ($finder as $file) {
            $contents = $file->getContents();
        }

        return $contents;
    }
}
