<?php

$EM_CONF[$_EXTKEY] = [
    'title'            => 'Mr.Template',
    'description'      => 'Add Templates to SitesConfig.',
    'category'         => 'fe',
    'author'           => 'Sascha Heilmeier',
    'author_email'     => 'sheilmeier@gmail.com',
    'state'            => 'beta',
    'clearCacheOnLoad' => true,
    'version'          => '0.1.1',
    'uploadfolder'     => false,
    'constraints'      => [
        'depends'   => [
            'typo3' => '10.4.0-10.4.99',
        ],
        'conflicts' => [],
        'suggests'  => [],
    ],
];
