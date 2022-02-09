<?php

namespace Scarbous\MrTemplate\Template\Entity;


interface TsConfigInterface
{
    /**
     * @return string
     */
    public function getHeader():string;

    /**
     * @param Template $template
     * @param array $page the rootPage record
     *
     * @return string
     */
    public function getTsConfig(Template $template, array $page): string;
}
