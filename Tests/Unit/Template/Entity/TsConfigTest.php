<?php

namespace Scarbous\MrTemplate\Tests\Unit\Template\Entity;

use Scarbous\MrTemplate\Template\Entity\Template;
use Scarbous\MrTemplate\Template\Entity\TsConfig;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class TsConfigTest extends UnitTestCase
{
    use AbstractTsConfigTrait;

    const TS_CONFIG_FILE_PATH = 'EXT:mr_template/Configuration/TsConfig/Test.tsconfig';

    /**
     * @var Template
     */
    private $template;


    /**
     * @return void
     */
    public function setup(): void
    {
        parent::setUp();

        $this->template = $this->getAccessibleMock(Template::class, [], [], '', false, false);
        $this->tsConfig = new TsConfig(self::TS_CONFIG_FILE_PATH);
    }

    /**
     * @test
     *
     * return void
     */
    public function testGetHeader(): void
    {
        self::assertSame(
            self::TS_CONFIG_FILE_PATH,
            $this->tsConfig->getHeader()
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function testGetTsConfig(): void
    {
        self::assertSame(
            "@import 'EXT:mr_template/Configuration/TsConfig/Test.tsconfig'",
            $this->tsConfig->getTsConfig($this->template, [])
        );
    }
}
