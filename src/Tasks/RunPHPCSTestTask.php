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
 * Class RunPHPCSTestTask
 * @package MouraoAnalizer\Tasks
 */
class RunPHPCSTestTask
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
    public function execute(array $modifiedFiles)
    {
        $config = $this->getConfigurationFileDataTask->execute();
        $standard = $config['phpcs']['ruleset'];
        $command = "./vendor/bin/phpcs --colors --standard={$standard} ".implode(' ', $modifiedFiles);
        $result = $this->sendCommandToTerminalTask->execute($command);
        print(implode(PHP_EOL, $result)).PHP_EOL;
    }
}