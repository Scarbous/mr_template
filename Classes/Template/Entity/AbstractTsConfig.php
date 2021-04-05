<?php

namespace Scarbous\MrTemplate\Template\Entity;

abstract class AbstractTsConfig implements TsConfigInterface
{

    /**
     * @inheritDoc
     */
    public function getHeader(): string
    {
        return self::class;
    }
}
