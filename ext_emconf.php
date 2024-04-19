<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "simple_consent"
 *
 * Auto generated by Extension Builder
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
	'title' => 'Dr. SEO',
	'description' => '',
	'category' => 'plugin',
	'author' => 'Steffen Kroggel',
	'author_email' => 'developer@steffenkroggel.de',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => '0',
	'clearCacheOnLoad' => 0,
	'version' => '12.4.5',
	'constraints' => [
		'depends' => [
            'typo3' => '10.4.0-12.4.99',
            'seo' => '10.4.0-12.4.99',
        ],
		'conflicts' => [
		],
		'suggests' => [
            'persisted_sanitized_routing' => '1.0.4',
        ],
	],
];
