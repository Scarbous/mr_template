<?php

namespace Scarbous\MrTemplate\Tests\Unit\Template\Entity;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class TemplateTest extends UnitTestCase
{
    use TemplateTrait;

    /**
     * @test
     *
     * return void
     */
    public function testGetTypoScriptStaticFiles(): void
    {
        self::assertSame(
            [
                'EXT:mr_template/Extensions/news/Configuration/TypoScript',
                'EXT:mr_template/Configuration/TypoScript/Page',
                'EXT:mr_template/Configuration/TypoScript/Config',
                'EXT:mr_template/Configuration/TypoScript'
            ],
            $this->getTemplate()->getTypoScriptStaticFiles()
        );
    }

    /**
     * @test
     */
    public function testGetPageTsConfig(): void
    {
        // remove Header which is tested in \Scarbous\MrTemplate\Tests\Unit\Template\Entity\AbstractTsConfigTrait::testGetHeaderWithComment
        $lines = explode(LF, $this->getTemplate()->getPageTsConfig([]));
        $config = implode(LF, array_filter($lines, function ($line) {
            return trim($line) && $line[0] != '#';
        }));

        self::assertSame(
            '<INCLUDE_TYPOSCRIPT: source="DIR:EXT:mr_template/Configuration/TsConfig/Test.tsconfig">' . LF
            . '<INCLUDE_TYPOSCRIPT: source="DIR:EXT:mr_template/Configuration/TsConfig/Page/Mod/WebLayout/BackendLayouts" extensions="tsconfig">',
            $config
        );
    }

    /**
     * @test
     *
     * return void
     */
    public function testGetIdentifier(): void
    {

        self::assertSame(
            'mr_template/base',
            $this->getTemplate()->getIdentifier()
        );
    }

    /**
     * @test
     *
     * return void
     */
    public function testGetExtKey(): void
    {
        self::assertSame(
            'mr_template',
            $this->getTemplate()->getExKey()
        );
    }

    /**
     * @test
     *
     * return void
     */
    public function testGetLabel(): void
    {
        self::assertSame(
            'Mr.Template base',
            $this->getTemplate()->getLabel()
        );
    }

    /**
     * @test
     *
     * return void
     */
    public function testGetIcon(): void
    {
        self::assertSame(
            'EXT:mr_template/Resources/Public/Icons/Extension.svg',
            $this->getTemplate()->getIcon()
        );
    }
}
