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

use MouraoAnalizer\Helper\FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Configure
 * @package Console\Commands
 */
class Configure extends Command
{
    private $ymlFileContent = [];

    private $helperQuestion;

    private $output;

    private $input;

    private $fileSystem;

    private $ymlFileName;

    /**
     * Configure constructor.
     * @param string|null $name
     * @param string|null $description
     * @param string $ymlFileName
     * @param FileSystem $fileSystem
     */
    public function __construct(
        string $name = null,
        string $description = null,
        string $ymlFileName,
        FileSystem $fileSystem
    ) {
        parent::__construct($name);
        $this->setDescription($description);
        $this->fileSystem = $fileSystem;
        $this->ymlFileName = $ymlFileName;
    }

    /**
     * Configure
     * @return void
     */
    protected function configure()
    {
        $this->setName('configure')
            ->setDescription('Configuração inicial');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->input = $input;

        $this->helperQuestion = $this->getHelper('question');
        try {
            $this->getMagentoVersion();
            $this->getPHPVersion();
            $this->getTargetBranchDefault();
            $this->getDefaultPath();
            $this->getMinPercentUnitTest();
            if($this->fileSystem->fileExist($this->ymlFileName)) {
                $this->confirmOverwriteFile($input, $output);
                return 1;
            }
            $this->writeFile();
        } catch (\Exception $exception) {
            $output->write('Some error');
        }
        return 1;
    }

    private function confirmOverwriteFile()
    {
        $question = new ConfirmationQuestion(
            '<info>O arquivo de configuração já existe, deseja substituir?</info> [<comment>N/y</comment>]',
            false,
            '/^(y)/i'
        );

        if ($this->helperQuestion->ask($this->input, $this->output, $question)) {
            $this->writeFile();
        }
    }

    private function getMagentoVersion()
    {
        $questionMagentoVersion = new Question('<info>Qual a versão do magento?</info> [<comment>2.4.1</comment>]: ', '2.4.1');
        $magentoVersion = $this->helperQuestion->ask($this->input, $this->output, $questionMagentoVersion);
        $this->ymlFileContent['magento-version'] = $magentoVersion;
    }

    private function getPHPVersion()
    {
        $questionMagentoVersion = new Question('<info>Qual a versão do PHP?</info> [<comment>7.4</comment>]: ', '7.4');
        $phpVersion = $this->helperQuestion->ask($this->input, $this->output, $questionMagentoVersion);
        $this->ymlFileContent['php-version'] = (double)$phpVersion;
    }

    private function getDefaultPath()
    {
        $questionDefaultPath = new Question('<info>Qual a pasta padrão do projeto?</info> [<comment>app/code</comment>]: ', 'app/code');
        $defaultPath = $this->helperQuestion->ask($this->input, $this->output, $questionDefaultPath);
        $this->ymlFileContent['path'] = $defaultPath;
    }

    private function getMinPercentUnitTest()
    {
        $questionPercentUnit = new Question('<info>Qual a porcentagem mínima para cobertura de teste unitário?</info> [<comment>80</comment>]: ', 80);
        $coverageLevel = $this->helperQuestion->ask($this->input, $this->output, $questionPercentUnit);
        $this->ymlFileContent['unit_test_coverage']['level'] = $coverageLevel;
    }

    private function getTargetBranchDefault()
    {
        $questionTargetBranchDefault = new Question('<info>Qual a branch padrão?</info> [<comment>develop</comment>]: ', 'develop');
        $targetBranchDefault = $this->helperQuestion->ask($this->input, $this->output, $questionTargetBranchDefault);
        $this->ymlFileContent['default_branch'] = $targetBranchDefault;
    }


    private function writeFile()
    {
        $this->ymlFileContent['phpcs'] = [
            'ruleset' => "./dev/tests/static/framework/Magento/",
        ];

        $this->ymlFileContent['phpmd'] = [
            'ruleset' => './dev/tests/static/testsuite/Magento/Test/Php/_files/phpmd/ruleset.xml',
        ];

        $this->fileSystem->writeFile($this->ymlFileName, Yaml::dump($this->ymlFileContent));
        $this->output->writeln('Arquivo de configuração gerado com sucesso.');
    }
}