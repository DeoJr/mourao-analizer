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

/**
 * Class SendCommandToTerminalTask
 * @package MouraoAnalizer\Tasks
 */
class SendCommandToTerminalTask
{
    /** @var FileSystem $fileSystem */
    private $fileSystem;

    /**
     * SendCommandToTerminalTask constructor.
     * @param FileSystem $fileSystem
     */
    public function __construct(
        FileSystem $fileSystem
    ) {
        $this->fileSystem = $fileSystem;
    }

    /**
     * @param string $command
     * @return array
     */
    public function execute(string $command): array
    {
        $pullRequestFiles = [];
        $basePath = $this->fileSystem->getBasePath();
        exec("cd {$basePath} && {$command}", $pullRequestFiles);
        return $pullRequestFiles;
    }
}