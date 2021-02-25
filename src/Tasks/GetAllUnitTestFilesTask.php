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
 * Class GetAllUnitTestsFilesTask
 * @package App\Tasks
 */
class GetAllUnitTestFilesTask
{
    /**
     * Get all modified files
     *
     * @param array $modifiedFiles
     * @return array
     */
    public function execute(array $modifiedFiles): array
    {
        foreach($modifiedFiles as &$modifiedFile) {
            $fileToArray = explode('/', $modifiedFile);
            if (empty($fileToArray[3])) {
                continue;
            }
            $fileToArray[3] .= '/Test/Unit';
            $fileName = array_pop($fileToArray);
            $fileName = str_replace('.php', 'Test.php', $fileName);
            array_push($fileToArray, $fileName);
            $modifiedFile = implode('/', $fileToArray);
        }
        return $modifiedFiles;
    }
}