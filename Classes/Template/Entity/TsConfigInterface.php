<?php

namespace Scarbous\MrTemplate\Template\Entity;

interface TsConfigInterface
{
    /**
     * @param bool $comment
     * @return string
     */
    public function getHeader(bool $comment = false): string;

    /**
     * @param Template $template
     * @param array $page the rootPage record
     *
     * @return string
     */
    public function getTsConfig(Template $template, array $page): string;
}
