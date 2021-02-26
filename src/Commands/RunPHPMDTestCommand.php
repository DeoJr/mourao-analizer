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
use MouraoAnalizer\Tasks\RunPHPMDTestTask;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RunPHPMDTestCommand
 * @package MouraoAnalizer\Commands
 */
class RunPHPMDTestCommand extends Command
{
    /** @var GetAllModifiedFilesTask $getAllModifiedFilesTask */
    private $getAllModifiedFilesTask;

    /** @var RunPHPMDTestTask $runPHPMDTestTask */
    private $runPHPMDTestTask;

    /**
     * RunPHPMDTestCommand constructor.
     * @param string|null $name
     * @param string|null $description
     * @param GetAllModifiedFilesTask $getAllModifiedFilesTask
     * @param RunPHPMDTestTask $runPHPMDTestTask
     */
    public function __construct(
        string $name = null,
        string $description = null,
        GetAllModifiedFilesTask $getAllModifiedFilesTask,
        RunPHPMDTestTask $runPHPMDTestTask
    ) {
        parent::__construct($name);
        $this->setDescription($description)
            ->addArgument('target-branch', InputArgument::REQUIRED)
            ->addArgument('source-branch', InputArgument::REQUIRED);

        $this->getAllModifiedFilesTask = $getAllModifiedFilesTask;
        $this->runPHPMDTestTask = $runPHPMDTestTask;
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
        $this->runPHPMDTestTask->execute($modifiedFiles);
        return 1;
    }
}