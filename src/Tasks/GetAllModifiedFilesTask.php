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

/**
 * Class GetAllModifiedFilesTask
 * @package App\Tasks
 */
class GetAllModifiedFilesTask
{
    /** @var string[] $blackList */
    private $blackList = [
        'registration.php'
    ];

    /** @var SendCommandToTerminalTask $sendCommandToTerminalTask */
    private $sendCommandToTerminalTask;

    /**
     * GetAllModifiedFilesTask constructor.
     * @param SendCommandToTerminalTask $sendCommandToTerminalTask
     */
    public function __construct(
        SendCommandToTerminalTask $sendCommandToTerminalTask
    ) {
        $this->sendCommandToTerminalTask = $sendCommandToTerminalTask;
    }

    /**
     * Get all modified files
     *
     * @param string $targetBranch
     * @param string $sourceBranch
     * @return array
     */
    public function execute(string $targetBranch, string $sourceBranch): array
    {
        $diffCommand = sprintf(
            'git diff --name-only --diff-filter=d origin/%s..origin/%s',
            $targetBranch,
            $sourceBranch
        );

        $pullRequestFiles = $this->sendCommandToTerminalTask->execute($diffCommand);
        return $this->filterValidFiles($pullRequestFiles);
    }

    /**
     * @param array|null $pullRequestFiles
     * @return array
     */
    private function filterValidFiles(?array $pullRequestFiles): array
    {
        $phpFiles = [];

        foreach ($pullRequestFiles as $file) {
            $fileInfo = pathinfo($file);

            if ($fileInfo['filename'] == 'registration') {
                continue;
            }

            if (strstr($fileInfo['dirname'], 'app/code/') === false) {
                continue;
            }

            if ($fileInfo['extension'] != 'php') {
                continue;
            }

            if (in_array($fileInfo['basename'], $this->blackList)) {
                continue;
            }

            if (substr($fileInfo['filename'], -4) == 'Test') {
                continue;
            }

            $phpFiles[] = $file;
        }
        return $phpFiles;
    }
}