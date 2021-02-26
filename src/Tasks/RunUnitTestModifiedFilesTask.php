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
 * Class RunUnitTestTask
 * @package MouraoAnalizer\Tasks
 */
class RunUnitTestModifiedFilesTask
{
    /** @var GetAllUnitTestFilesTask $getAllUnitTestFilesTask */
    private $getAllUnitTestFilesTask;


    /** @var SendCommandToTerminalTask $sendCommandToTerminalTask */
    private $sendCommandToTerminalTask;

    /** @var SaveUnitTestFileConfiguration $saveUnitTestFileConfiguration */
    private $saveUnitTestFileConfiguration;

    /**
     * RunUnitTestTask constructor.
     * @param GetAllUnitTestFilesTask $getAllUnitTestFilesTask
     * @param SendCommandToTerminalTask $sendCommandToTerminalTask
     * @param SaveUnitTestFileConfiguration $saveUnitTestFileConfiguration
     */
    public function __construct(
        GetAllUnitTestFilesTask $getAllUnitTestFilesTask,
        SendCommandToTerminalTask $sendCommandToTerminalTask,
        SaveUnitTestFileConfiguration $saveUnitTestFileConfiguration
    ) {
        $this->getAllUnitTestFilesTask = $getAllUnitTestFilesTask;
        $this->sendCommandToTerminalTask = $sendCommandToTerminalTask;
        $this->saveUnitTestFileConfiguration = $saveUnitTestFileConfiguration;
    }

    public function execute(array $modifiedFiles)
    {
        if (empty($modifiedFiles)) {
            exit(0);
        }
        $unitTestFiles = $this->getAllUnitTestFilesTask->execute($modifiedFiles);

        $testFiles = [];
        foreach ($unitTestFiles as $unitTestFile) {
            $rowFile = '<directory suffix="Test.php">'.$unitTestFile.'</directory>';
            array_push($testFiles, $rowFile);
        }

        $coverageFiles = [];
        foreach ($modifiedFiles as $modifiedFile) {
            $rowFile = '<directory suffix=".php">'.$modifiedFile.'</directory>';
            array_push($coverageFiles, $rowFile);
        }
        $this->saveUnitTestFileConfiguration->execute($testFiles, $coverageFiles);

        $command = './vendor/bin/phpunit --coverage-html test-new --coverage-clover dev/tests/unit/test-reports/phpunit.coverage.xml ';
        $result = $this->sendCommandToTerminalTask->execute($command);
        print(implode(PHP_EOL, $result)).PHP_EOL;
    }
}