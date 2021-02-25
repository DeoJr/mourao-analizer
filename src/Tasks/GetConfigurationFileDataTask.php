<?php
/**
 * @author      Webjump Developer Team <developer@webjump.com.br>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br Copyright
 *
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);


namespace MouraoAnalizer\Tasks;

use MouraoAnalizer\Helper\FileSystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Class GetConfigurationFileDataTask
 * @package MouraoAnalizer\Tasks
 */
class GetConfigurationFileDataTask
{
    /** @var FileSystem $fileSystem */
    private $fileSystem;

    /** @var string $fileName */
    private $fileName;

    /**
     * GetConfigurationFileDataTask constructor.
     * @param FileSystem $fileSystem
     * @param string $fileName
     */
    public function __construct(
        FileSystem $fileSystem,
        string $fileName
    ) {
        $this->fileSystem = $fileSystem;
        $this->fileName = $fileName;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        return $this->fileSystem->getYamlFileData($this->fileName);
    }
}
