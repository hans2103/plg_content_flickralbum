<?php

/**
 * @package         Joomla.Plugin
 * @subpackage      Content.FlickrAlbum
 *
 * @copyright   (C) 2025 HKweb <https://hkweb.nl>
 * @license         GNU General Public License version 3 or later; see LICENSE.txt
 * @link            https://hkweb.nl
 */

namespace HKweb\Plugin\Content\FlickrAlbum\Extension;

\defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Plugin\CMSPlugin;

/**
 * Content Plugin to embed a Flickr album in Joomla articles using
 * the {flickr album=ALBUMID user=USERID} tag.
 *
 * Recognizes the tag even if wrapped in <p>, <div>, or <span> by editors.
 *
 * @since  25.26.0
 */
final class FlickrAlbum extends CMSPlugin
{
	/**
	 * Replaces {flickr album=ALBUMID user=USERID} tags with the Flickr album embed code,
	 * even if wrapped in block/inline tags by editors.
	 *
	 * @param   string   $context  The context of the content being passed to the plugin.
	 * @param   object   $article  The article object. Note $article->text is also available.
	 * @param   mixed    $params   The article params.
	 * @param   integer  $page     The 'page' number.
	 *
	 * @return  boolean  True on success.
	 *
	 * @throws Exception
	 * @since   25.26.0
	 */
	public function onContentPrepare(string $context, object &$article, mixed &$params, int $page = 0): bool
	{
		if (!isset($article->text) || $article->text === null)
		{
			return false;
		}

		// Regex for {flickr album=ALBUMID user=USERID}, optionally wrapped in p/div/span
		$pattern = '/(?:<(p|div|span)[^>]*>\s*)?{flickr\s+album=(?:"(?P<album1>[^"]+)"|(?P<album2>[0-9a-zA-Z]+))(?:\s+user=(?:"(?P<user1>[^"]+)"|(?P<user2>[0-9a-zA-Z@_]+)))?}\s*(?:<\/\1>)?/i';

		// Only proceed if plugin tag is present
		if (stripos($article->text, '{flickr ') === false)
		{
			return false;
		}

		// Remove macros for indexing
		if ($context === 'com_finder.indexer')
		{
			$article->text = preg_replace($pattern, '', $article->text);

			return false;
		}

		$article->text = preg_replace_callback(
			$pattern,
			fn($m) => $this->renderFlickrAlbum($m),
			$article->text
		);

		return true;
	}

	/**
	 * Render the Flickr album embed code from matches.
	 *
	 * @param   array  $matches  The regex matches from the tag.
	 *
	 * @return  string  The replacement HTML for the tag.
	 *
	 * @throws Exception
	 * @since   25.26.0
	 */
	private function renderFlickrAlbum(array $matches): string
	{
		$albumId = $matches['album1'] ?? $matches['album2'] ?? '';
		$userId  = $matches['user1'] ?? $matches['user2'] ?? '';

		if (!$userId)
		{
			$userId = trim($this->params->get('flickr_userid', ''));
		}

		if ($userId === '')
		{
			Factory::getApplication()->enqueueMessage(Text::_('PLG_CONTENT_FLICKRALBUM_ERROR_NO_USERID'), 'error');

			return '';
		}

		$basePath = JPATH_PLUGINS . '/content/flickralbum/layouts';
		$embedUrl = "https://www.flickr.com/photos/$userId/albums/$albumId/player/";

		return LayoutHelper::render(
			'media.flickralbum',
			compact('albumId', 'userId', 'embedUrl'),
			$basePath
		);
	}
}
