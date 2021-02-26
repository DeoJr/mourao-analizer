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
 * Class RunPHPMDTestTask
 * @package MouraoAnalizer\Tasks
 */
class RunPHPCPDTestTask
{
    /** @var SendCommandToTerminalTask $sendCommandToTerminalTask */
    private $sendCommandToTerminalTask;

    /**
     * RunPHPMDTestTask constructor.
     * @param SendCommandToTerminalTask $sendCommandToTerminalTask
     */
    public function __construct(
        SendCommandToTerminalTask $sendCommandToTerminalTask
    ) {
        $this->sendCommandToTerminalTask = $sendCommandToTerminalTask;
    }

    /**
     * @param array $modifiedFiles
     */
    public function execute(?array $modifiedFiles)
    {
        if (empty($modifiedFiles)) {
            return 0;
        }
        $command = './vendor/bin/phpcpd '.implode(' ', $modifiedFiles);
        $result = $this->sendCommandToTerminalTask->execute($command);
        print(implode(PHP_EOL, $result)).PHP_EOL;
    }
}