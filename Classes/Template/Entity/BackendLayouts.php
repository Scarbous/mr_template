<?php

namespace Scarbous\MrTemplate\Template\Entity;

class BackendLayouts extends AbstractTsConfig implements TsConfigInterface
{
    /**
     * @param Template $template
     * @param array $page
     * @return string
     */
    function getTsConfig(Template $template, array $page): string
    {
        return sprintf("@import 'EXT:%s/Configuration/TsConfig/Page/Mod/WebLayout/BackendLayouts'",
            $template->getExKey());
    }
}
