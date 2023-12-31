<?php

namespace Ketcau\Doctrine\ORM\Mapping\Driver;

use Doctrine\Persistence\Mapping\MappingException;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver as AnnotationDriverBase;

class AnnotationDriver extends AnnotationDriverBase
{
    protected $trait_proxies_directory;

    public function setTraitProxiesDirectory($directory): void
    {
        $this->trait_proxies_directory = $directory;
    }


    public function getAllClassNames(): ?array
    {
        if ($this->classNames !== null) {
            return $this->classNames;
        }

        if ($this->paths === []) {
            throw MappingException::pathRequiredForDriver(static::class);
        }

        $classes = [];
        $includedFiles = [];

        foreach ($this->paths as $path) {
            if (!is_dir($path)) {
                throw MappingException::fileMappingDriversRequireConfiguredDirectoryPath($path);
            }

            $iterator = new \RegexIterator(
                new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                ),
                '/^.+'. preg_quote($this->fileExtension). '$/i',
                \RecursiveRegexIterator::GET_MATCH
            );

            foreach ($iterator as $file)
            {
                $sourceFile = $file[0];

                if (!preg_match('(^phar:)i', $sourceFile)) {
                    $sourceFile = realpath($sourceFile);
                }

                foreach ($this->excludePaths as $excludePath) {
                    $exclude = str_replace('\\', '/', realpath($excludePath));
                    $current = str_replace('\\', '/', $sourceFile);

                    if (strpos($current, $exclude) !== false) {
                        continue 2;
                    }
                }

                $projectDir = realpath(__DIR__. '/../../../../../../');
                if ('\\' === DIRECTORY_SEPARATOR) {
                    $path = str_replace('\\', '/', $path);
                    $this->trait_proxies_directory = str_replace('\\', '/', $this->trait_proxies_directory);
                    $sourceFile = str_replace('\\', '/', $sourceFile);
                    $projectDir = str_replace('\\', '/', $projectDir);
                }

                $proxyFile = str_replace($projectDir, $this->trait_proxies_directory, $path). '/'. basename($sourceFile);
                if (file_exists($proxyFile)) {
                    require_once $proxyFile;
                    $sourceFile = $proxyFile;
                } else {
                    require_once $sourceFile;
                }

                $includedFiles[] = realpath($sourceFile);
            }
        }

        $declared = get_declared_classes();

        foreach ($declared as $className) {
            $rc = new \ReflectionClass($className);
            $sourceFile = $rc->getFileName();
            if (in_array($sourceFile, $includedFiles)) {
                $classes[] = $className;
            }
        }

        $this->classNames = $classes;

        return $classes;
    }
}