<?php
/**
 * @author      Webjump Developer Team <developer@webjump.com.br>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br Copyright
 *
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace MouraoAnalizer\Commands;

use MouraoAnalizer\Tasks\GetAllModifiedFilesTask;
use MouraoAnalizer\Tasks\RunUnitTestModifiedFilesTask;
use MouraoAnalizer\Tasks\TestClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunUnitTestsCommand extends Command
{
    /** @var RunUnitTestModifiedFilesTask $runUnitTestModifiedFilesTask */
    private $runUnitTestModifiedFilesTask;

    /** @var GetAllModifiedFilesTask $getAllModifiedFilesTask */
    private $getAllModifiedFilesTask;

    /**
     * RunUnitTestsCommand constructor.
     * @param string|null $name
     * @param string|null $description
     * @param RunUnitTestModifiedFilesTask $runUnitTestModifiedFilesTask
     * @param GetAllModifiedFilesTask $getAllModifiedFilesTask
     */
    public function __construct(
        string $name = null,
        string $description = null,
        RunUnitTestModifiedFilesTask $runUnitTestModifiedFilesTask,
        GetAllModifiedFilesTask $getAllModifiedFilesTask
    ) {
        parent::__construct($name);
        $this->setDescription($description)
            ->addArgument('target-branch', InputArgument::REQUIRED)
            ->addArgument('source-branch', InputArgument::REQUIRED);

        $this->runUnitTestModifiedFilesTask = $runUnitTestModifiedFilesTask;
        $this->getAllModifiedFilesTask = $getAllModifiedFilesTask;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $modifiedFiles = $this->getAllModifiedFilesTask->execute(
            $input->getArgument('target-branch'),
            $input->getArgument('source-branch')
        );
        $this->runUnitTestModifiedFilesTask->execute($modifiedFiles);
        return 1;
    }
}