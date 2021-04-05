<?php

namespace Scarbous\MrTemplate\Template\Entity;

class BackendLayouts extends AbstractTsConfig implements TsConfigInterface
{
    function getTsConfig(Template $template, array $page): string
    {
        return sprintf('<INCLUDE_TYPOSCRIPT: source="DIR:EXT:%s/Configuration/TsConfig/Page/Mod/WebLayout/BackendLayouts" extensions="tsconfig">',
            $template->getExKey());
    }
}
