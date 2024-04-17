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

use GeorgRinger\News\Domain\Model\News;
use TYPO3\CMS\Core\PageTitle\AbstractPageTitleProvider;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class NewsTitleProvider
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_DrSeo
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @see \GeorgRinger\News\Seo\NewsTitleProvider
 */
class NewsTitleProvider extends AbstractPageTitleProvider
{

	private const DEFAULT_PROPERTIES = 'title';


	/**
	 * @param SiteFinder $siteFinder
	 */
	public function __construct(private readonly SiteFinder $siteFinder)
	{

	}


	/**
	 * @param News $news
	 * @param array $configuration
	 * @throws \TYPO3\CMS\Core\Exception\SiteNotFoundException
	 */
	public function setTitleByNews(News $news, array $configuration = []): void
	{
		$title = '';
		$fields = GeneralUtility::trimExplode(',', $configuration['properties'] ?? self::DEFAULT_PROPERTIES, true);

		$usedField = '';
		foreach ($fields as $field) {
			$getter = 'get' . ucfirst($field);
			$value = $news->$getter();
			if ($value) {
				$usedField = $field;
				$title = str_replace('­', '', strip_tags($value));;
				break;
			}
		}

		if ($title) {
			$site = $this->siteFinder->getSiteByPageId($this->getTypoScriptFrontendController()->page['uid']);

			$titles = [
				$site->getAttribute('websiteTitle'),
				$title,
			];

			if ($usedField == 'alternativeTitle') {
				$titles = [
					$title,
				];
			}

			// do something
			$this->title = implode(' – ', $titles);
		}
	}


	/**
	 * @param string $title
	 * @return void
	 */
	public function setTitle(string $title): void
	{
		$this->title = $title;
	}


	/**
	 * @return TypoScriptFrontendController
	 */
	private function getTypoScriptFrontendController(): TypoScriptFrontendController
	{
		return $GLOBALS['TSFE'];
	}
}
