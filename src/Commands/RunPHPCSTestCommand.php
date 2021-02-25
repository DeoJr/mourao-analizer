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
use MouraoAnalizer\Tasks\RunPHPCSTestTask;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunPHPCSTestCommand  extends Command
{
    /** @var GetAllModifiedFilesTask $getAllModifiedFilesTask */
    private $getAllModifiedFilesTask;

    /** @var RunPHPCSTestTask $runPHPCSTestTask */
    private $runPHPCSTestTask;

    /**
     * RunPHPMDTestCommand constructor.
     * @param string|null $name
     * @param string|null $description
     * @param GetAllModifiedFilesTask $getAllModifiedFilesTask
     * @param RunPHPCSTestTask $runPHPCSTestTask
     */
    public function __construct(
        string $name = null,
        string $description = null,
        GetAllModifiedFilesTask $getAllModifiedFilesTask,
        RunPHPCSTestTask $runPHPCSTestTask
    ) {
        parent::__construct($name);
        $this->setDescription($description);
        $this->getAllModifiedFilesTask = $getAllModifiedFilesTask;
        $this->runPHPCSTestTask = $runPHPCSTestTask;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $modifiedFiles = $this->getAllModifiedFilesTask->execute('marketplace', 'feature/HV-170');
        $this->runPHPCSTestTask->execute($modifiedFiles);
        return 1;
    }
}