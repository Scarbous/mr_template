<?php

$EM_CONF[$_EXTKEY] = [
    'title'            => 'Mr.Template',
    'description'      => 'Add Templates to SitesConfig.',
    'category'         => 'fe',
    'author'           => 'Sascha Heilmeier',
    'author_email'     => 'sheilmeier@gmail.com',
    'state'            => 'beta',
    'clearCacheOnLoad' => true,
    'version'          => '1.1.4',
    'uploadfolder'     => false,
    'constraints'      => [
        'depends'   => [
            'typo3' => '10.4.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests'  => [],
    ]
];
