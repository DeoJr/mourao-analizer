<?php
/**
 * @author      Webjump Developer Team <developer@webjump.com.br>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br Copyright
 *
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace MouraoAnalizer;

use Symfony\Component\Console\Application as BaseApplication;

/**
 * Class Application
 * @package MouraoAnalizer
 */
class Application extends BaseApplication
{
    /**
     * Constructor.
     * @param array $commands
     */
    public function __construct(iterable $commands, string $name, string $version)
    {
        parent::__construct($name, $version);

        foreach ($commands as $command) {
            $this->add($command);
        }
    }
}