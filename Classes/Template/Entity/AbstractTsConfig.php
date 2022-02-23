<?php

namespace Scarbous\MrTemplate\Template\Entity;

abstract class AbstractTsConfig implements TsConfigInterface
{
    /**
     * @inheritDoc
     */
    public function getHeader(bool $comment = false): string
    {
        return $comment ? $this->wrapHeader(static::class) : static::class;
    }

    /**
     * @param string $header
     * @return string
     */
    protected function wrapHeader(string $header): string
    {
        return LF . str_repeat('#', strlen($header) + 10) . LF .
            '##### ' . $header . ' #####' . LF .
            str_repeat('#', strlen($header) + 10) . LF;
    }
}
