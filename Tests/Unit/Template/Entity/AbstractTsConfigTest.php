<?php

namespace Scarbous\MrTemplate\Tests\Unit\Template\Entity;

use Scarbous\MrTemplate\Template\Entity\AbstractTsConfig;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class AbstractTsConfigTest extends UnitTestCase
{
    use AbstractTsConfigTrait;

    /**
     * @var AbstractTsConfig
     */
    protected $abstractTsConfig = null;

    function setUp(): void
    {
        parent::setUp();

        $this->tsConfig = $this->getAccessibleMockForAbstractClass(AbstractTsConfig::class, ['getHeader'], '', false, false);
    }

    /**
     * @test
     *
     * return void
     */
    public function testGetHeader(): void
    {
        self::assertSame(
            get_class($this->tsConfig),
            $this->tsConfig->getHeader()
        );
    }
}
