<?php

namespace Scarbous\MrTemplate\Tests\Unit\Template\Entity;

use Scarbous\MrTemplate\Template\Entity\TsConfigInterface;

trait AbstractTsConfigTrait
{
    /**
     * @var TsConfigInterface
     */
    protected $tsConfig;

    /**
     * @test
     *
     * return void
     */
    public function testGetHeaderWithComment(): void
    {
        foreach (explode(LF, $this->tsConfig->getHeader(true)) as $line) {
            if (!trim($line)) {
                continue;
            }

            self::assertStringStartsWith(
                '#',
                $line
            );
        }
    }
}
