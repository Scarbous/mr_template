<?php

namespace Scarbous\MrTemplate\Tests\Template\Entity;

use Scarbous\MrTemplate\Template\Entity\AbstractTsConfig;
use TYPO3\TestingFramework\Core\BaseTestCase;

class AbstractTsConfigTest extends BaseTestCase
{
    /**
     * @return void
     */
    public function setup(): void
    {
        parent::setUp();

        $this->abstractTsConfig = $this->getAccessibleMockForAbstractClass(AbstractTsConfig::class, ['getHeader'], '', false, false);
    }

    /**
     * @test
     *
     * return void
     */
    public function testGetHeader(): void
    {
        self::assertSame(
            $this->abstractTsConfig->getHeader(),
            get_class($this->abstractTsConfig)
        );
    }
}
