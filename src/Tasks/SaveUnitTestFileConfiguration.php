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

use MouraoAnalizer\Helper\FileSystem;

/**
 * Class SaveUnitTestFileConfiguration
 * @package MouraoAnalizer\Tasks
 */
class SaveUnitTestFileConfiguration
{
    /** @var FileSystem $fileSystem */
    private $fileSystem;

    /**
     * SaveUnitTestFileConfiguration constructor.
     * @param FileSystem $fileSystem
     */
    public function __construct(
        FileSystem $fileSystem
    ) {
        $this->fileSystem = $fileSystem;
    }

    public function execute(array $testFiles, array $coverageFiles)
    {
        $fileTemplate = $this->getTemplateUnitTest();
        $fileTemplate = str_replace( '{{test_files}}', implode(PHP_EOL, $testFiles), $fileTemplate);
        $fileTemplate = str_replace( '{{coverage_files}}', implode(PHP_EOL, $coverageFiles), $fileTemplate);
        $this->fileSystem->writeFile('phpunit.xml', $fileTemplate);
    }

    /**
     * @return string
     */
    public function getTemplateUnitTest(): string
    {
        return
            '<?xml version="1.0" encoding="UTF-8"?>
            <phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                     xsi:noNamespaceSchemaLocation="http://schema.phpunit.de/6.2/phpunit.xsd"
                     colors="true"
                     columns="max"
                     beStrictAboutTestsThatDoNotTestAnything="false"
                     bootstrap="dev/tests/unit/framework/bootstrap.php">
                <testsuite name="Magento Unit Tests">
                    {{test_files}}
                </testsuite>
                <filter>
                    <whitelist>
                        {{coverage_files}}
                        <exclude>
                            {{test_files}}
                        </exclude>
                    </whitelist>
                </filter>
            </phpunit>';
    }
}