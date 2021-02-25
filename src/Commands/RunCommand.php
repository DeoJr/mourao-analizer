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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    public function __construct(
        string $name = null,
        string $description = null
    ) {
        parent::__construct($name);
        $this->setDescription($description);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        print 'teste';
        return 1;
    }
}