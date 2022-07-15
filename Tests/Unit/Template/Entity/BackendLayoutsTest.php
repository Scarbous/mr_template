<?php

namespace Scarbous\MrTemplate\Tests\Template\Entity;

use Scarbous\MrTemplate\Template\Entity\BackendLayouts;
use Scarbous\MrTemplate\Template\Entity\Template;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class BackendLayoutsTest extends UnitTestCase
{
    const TEMPLATE_IDENTIFIER = 'mr_template/base';

    /**
     * @var Template
     */
    private $template;

    /**
     * @var BackendLayouts
     */
    private $backendLayouts;

    /**
     * @return void
     */
    public function setup(): void
    {
        parent::setUp();

        $this->template = new Template(self::TEMPLATE_IDENTIFIER, []);

        $this->backendLayouts = new BackendLayouts();
    }

    /**
     * @test
     *
     * @return void
     */
    public function testGetTsConfig(): void
    {
        self::assertSame(
            "@import 'EXT:mr_template/Configuration/TsConfig/Page/Mod/WebLayout/BackendLayouts'",
            $this->backendLayouts->getTsConfig($this->template, [])
        );
    }
}
