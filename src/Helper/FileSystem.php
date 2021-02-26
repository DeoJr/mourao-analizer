<?php
/**
 * @author      Webjump Developer Team <developer@webjump.com.br>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br Copyright
 *
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace MouraoAnalizer\Helper;

use Symfony\Component\Yaml\Yaml;

/**
 * Class FileSystem
 */
class FileSystem
{
    private CONST DEVELOPER_MODE = 'developer';

    private CONST PRODUCTION_MODE = 'production';

    /** @var string $basePath */
    private $basePath;

    /**
     * FileSystem constructor.
     * @param string $appMode
     */
    public function __construct(string $appMode)
    {
        if ($appMode == self::DEVELOPER_MODE) {
            $this->basePath = __DIR__.'/../../';
        }

        if ($appMode == self::PRODUCTION_MODE) {
            $this->basePath = __DIR__.'/../../../../../';
        }
    }

    /**
     * @param string $pathFile
     * @return bool
     */
    public function fileExist(string $pathFile): bool
    {
        return file_exists($this->basePath.$pathFile);
    }

    /**
     * @param string $fileName
     * @param string $contentFile
     * @return false|int
     */
    public function writeFile(string $fileName, string $contentFile)
    {
        return file_put_contents($this->basePath . $fileName, $contentFile);
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getYamlFileData(string $path)
    {
        return Yaml::parseFile($this->basePath . $path);
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }
}