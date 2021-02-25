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
    private const BASE_PATH = '/home/webjump-nb097/Documentos/projetos/havan/';

    /**
     * @param string $path
     * @return false|string
     */
    public function readFile(string $path)
    {
        return file_get_contents(self::BASE_PATH . $path);
    }

    /**
     * @param string $pathFile
     * @return bool
     */
    public function fileExist(string $pathFile): bool
    {
        return file_exists(self::BASE_PATH.$pathFile);
    }

    /**
     * @param string $fileName
     * @param string $contentFile
     * @return false|int
     */
    public function writeFile(string $fileName, string $contentFile)
    {
        return file_put_contents(self::BASE_PATH.$fileName, $contentFile);
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getYamlFileData(string $path)
    {
        return Yaml::parseFile(self::BASE_PATH .$path);
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return self::BASE_PATH;
    }
}