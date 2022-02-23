<?php

namespace Scarbous\MrTemplate\Tests\Unit\Template\Entity;

use Scarbous\MrTemplate\Template\Entity\Template;

trait TemplateTrait
{

    function getTemplate(): Template
    {
        return new Template(
            'mr_template/base',
            [
                'label' => 'Mr.Template base',
                'icon' => 'EXT:mr_template/Resources/Public/Icons/Extension.svg',
                'typoScript' => [
                    'EXT:mr_template/Configuration/TypoScript/Page',
                    'EXT:mr_template/Configuration/TypoScript/Config'
                ],
                'extensions' => [
                    'news'
                ],
                'tsConfig' => [
                    'EXT:mr_template/Configuration/TsConfig/Test.tsconfig',
                    \Scarbous\MrTemplate\Template\Entity\BackendLayouts::class
                ]
            ]
        );
    }
}
