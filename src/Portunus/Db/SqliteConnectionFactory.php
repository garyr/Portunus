<?php

namespace Portunus\Db;

class SqliteConnectionFactory
{
    private $packageDir;
    private $filename;
    private $dataDir;

    public function __construct($packageDir = null, $filename = null, $dataDir = null)
    {
        $this->packageDir = $packageDir;
        $this->filename = $filename;
        $this->dataDir = $dataDir;
    }

    public function setPackageDir($packageDir)
    {
        $this->packageDir = $packageDir;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function setDataDir($dataDir)
    {
        $this->dataDir = $dataDir;
    }

    public function getConnection()
    {
        if (!preg_match('/^\//', $this->dataDir)) {
            $this->dataDir = $this->resolveRelativePath($this->dataDir);
        }

        return array(
            'driver' => 'pdo_sqlite',
            'path' => sprintf('%s/%s', $this->dataDir, $this->filename),
        );
    }

    private function resolveRelativePath($dataDir)
    {
        $vendorDir = null;
        $baseDir = __DIR__ . '/../../..';
        foreach (array($baseDir . '/../../autoload.php', $baseDir . '/../vendor/autoload.php', $baseDir . '/vendor/autoload.php') as $file) {
            if (file_exists($file)) {
                $vendorDir = realpath(dirname($file));
                break;
            }
        }

        return realpath(sprintf('%s/%s', $vendorDir,  $dataDir));
    }
}