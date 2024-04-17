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

use TYPO3\CMS\Core\PageTitle\PageTitleProviderInterface;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class WebsiteTitleProvider
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
*  @package Madj2k_DrSeo
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
final class WebsiteTitleProvider implements PageTitleProviderInterface
{

	private const DEFAULT_PROPERTIES = 'title';
	private const DEFAULT_GLUE = '" "';


	/**
	 * @param SiteFinder $siteFinder
	 */
	public function __construct(private readonly SiteFinder $siteFinder)
	{

	}

	/**
	 * @return string
	 * @throws \TYPO3\CMS\Core\Exception\SiteNotFoundException
	 */
	public function getTitle(): string
	{
		$site = $this->siteFinder->getSiteByPageId($this->getTypoScriptFrontendController()->page['uid']);
		$titles = [
			$site->getAttribute('websiteTitle'),
			$this->getTypoScriptFrontendController()->page['title'],
		];

		if ($this->getTypoScriptFrontendController()->page['seo_title']) {
			$titles = [
				$this->getTypoScriptFrontendController()->page['seo_title']
			];
		}

		// do something
		return implode(' – ', $titles);
	}

	/**
	 * @return TypoScriptFrontendController
	 */
	private function getTypoScriptFrontendController(): TypoScriptFrontendController
	{
		return $GLOBALS['TSFE'];
	}
}
