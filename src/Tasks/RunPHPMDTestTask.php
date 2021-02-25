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
class RunPHPMDTestTask
{
    /** @var SendCommandToTerminalTask $sendCommandToTerminalTask */
    private $sendCommandToTerminalTask;

    /** @var GetConfigurationFileDataTask $getConfigurationFileDataTask */
    private $getConfigurationFileDataTask;

    /**
     * RunPHPMDTestTask constructor.
     * @param SendCommandToTerminalTask $sendCommandToTerminalTask
     * @param GetConfigurationFileDataTask $getConfigurationFileDataTask
     */
    public function __construct(
        SendCommandToTerminalTask $sendCommandToTerminalTask,
        GetConfigurationFileDataTask $getConfigurationFileDataTask
    ) {
        $this->sendCommandToTerminalTask = $sendCommandToTerminalTask;
        $this->getConfigurationFileDataTask = $getConfigurationFileDataTask;
    }

    /**
     * @param array $modifiedFiles
     */
    public function execute(?array $modifiedFiles)
    {
        $config = $this->getConfigurationFileDataTask->execute();
        $ruleset = $config['phpmd']['ruleset'];
        $command = './vendor/bin/phpmd "'.implode(',', $modifiedFiles).'" text '.$ruleset;
        $result = $this->sendCommandToTerminalTask->execute($command);
        print(implode(PHP_EOL, $result)).PHP_EOL;
    }
}