<?php
declare(strict_types=1);
namespace Madj2k\DrSeo\PageTitle;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\PageTitle\PageTitleProviderInterface;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class WebsiteTitleProvider
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
*  @package Madj2k_DrSeo
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class WebsiteTitleProvider implements PageTitleProviderInterface
{

	protected const DEFAULT_PROPERTIES = 'seo_title,title';
    protected const DEFAULT_GLUE = ' – ';


    /**
     * @var TYPO3\CMS\Core\Site\SiteFinder
     */
    private ?SiteFinder $siteFinder = null;


	/**
	 * @param TYPO3\CMS\Core\Site\SiteFinder $siteFinder
	 */
	public function __construct(SiteFinder $siteFinder)
	{
        $this->siteFinder = $siteFinder;
	}

    
    /**
     * @param array $configuration
     * @return string
     * @throws TYPO3\CMS\Core\Exception\SiteNotFoundException
     */
	public function getTitle(array $configuration = []): string
	{

        // get relevant fields
        $title = '';
        $fields = GeneralUtility::trimExplode(',', $configuration['properties'] ?? self::DEFAULT_PROPERTIES, true);
        $separator = $configuration['separator'] ?? self::DEFAULT_GLUE;

        $usedField = '';
        foreach ($fields as $field) {
            $value = $this->getTypoScriptFrontendController()->page[$field];
            if ($value) {

                // store last used field and remove soft hyphens (if any)
                $usedField = $field;
                $title = str_replace('­', '', strip_tags($value));
                break;
            }
        }

        $site = $this->siteFinder->getSiteByPageId($this->getTypoScriptFrontendController()->page['uid']);
        $titleArray = [
            $site->getAttribute('websiteTitle'),
        ];

        if ($title) {

            // add title of page
            $titleArray[] = $title;

            // no website-title prefix if alternative field is used
            if ($usedField == 'seo_title') {
                $titleArray = [
                    $title,
                ];
            }
        }

        // merge
		return implode(' – ', $titleArray);
	}


	/**
	 * @return TypoScriptFrontendController
	 */
	private function getTypoScriptFrontendController(): TypoScriptFrontendController
	{
		return $GLOBALS['TSFE'];
	}
}
