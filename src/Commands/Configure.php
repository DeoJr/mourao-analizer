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
use Symfony\Component\Console\Input\InputOption;
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
    const OPTION_MAGENTO_VERSION = 'magento-version';

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
            ->setDescription('Configuração inicial')
            ->setHelp('Recebe os parametros de versão do projeto e configura de acordo com os parametros recebido.')
            ->addOption(self::OPTION_MAGENTO_VERSION, 'mv', InputOption::VALUE_REQUIRED, 'Versão do magento');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->input = $input;

        $this->helperQuestion = $this->getHelper('question');
        try {
            $this->getMagentoVersion();
            $this->getPHPVersion();
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
        $questionMagentoVersion = new Question('<info>Qual a versão do magento?</info> [<comment>Ex: 2.3.5</comment>]: ');
        $magentoVersion = $this->helperQuestion->ask($this->input, $this->output, $questionMagentoVersion);
        $this->ymlFileContent['magento-version'] = $magentoVersion;
    }

    private function getPHPVersion()
    {
        $questionMagentoVersion = new Question('<info>Qual a versão do PHP?</info> [<comment>Ex: 7.2</comment>]: ');
        $phpVersion = $this->helperQuestion->ask($this->input, $this->output, $questionMagentoVersion);
        $this->ymlFileContent['php-version'] = (double)$phpVersion;
    }

    private function getDefaultPath()
    {
        $default = 'app/code';
        $questionDefaultPath = new Question('<info>Qual a pasta padrão do projeto?</info> [<comment>'.$default.'</comment>]: ');
        $defaultPath = $this->helperQuestion->ask($this->input, $this->output, $questionDefaultPath);
        $this->ymlFileContent['path'] = $default;
        if(!empty($defaultPath)){
            $this->ymlFileContent['path'] = $defaultPath;
        }
    }

    private function getMinPercentUnitTest()
    {
        $minDefault = 80;
        $questionPercentUnit = new Question('<info>Qual a porcentagem mínima para cobertura de teste unitário?</info> [<comment>'.$minDefault.'</comment>]: ');
        $defaultPath = $this->helperQuestion->ask($this->input, $this->output, $questionPercentUnit);
        $this->ymlFileContent['unit_test_coverage']['level'] = $minDefault;
        if(!empty($defaultPath)){
            $this->ymlFileContent['unit_test_coverage']['level'] = $defaultPath;
        }
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