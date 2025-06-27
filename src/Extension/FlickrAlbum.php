<?php

/**
 * @package         Joomla.Plugin
 * @subpackage      System.flickralbum
 *
 * @copyright   (C) 2025 HKweb <https://hkweb.nl>
 * @license         GNU General Public License version 3 or later
 */

namespace HKweb\Plugin\Content\FlickrAlbum\Extension;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;

/**
 * Plugin to enable loading Flickr Album into content (e.g. articles)
 * This uses the {flickr 1234567890} syntax
 *
 * @since  25.26.0
 */
final class FlickrAlbum extends CMSPlugin
{
	/**
	 * Replaces {flickr album=ALBUMID user=USERID} tags with Flickr album embed code.
	 *
	 * @param   string    $context  The context of the content being passed to the plugin.
	 * @param   object   &$article  The article object.  Note $article->text is also available
	 * @param   mixed    &$params   The article params
	 * @param   integer   $page     The 'page' number
	 *
	 * @return  boolean
	 *
	 * @since   25.26.0
	 */
	public function onContentPrepare(string $context, object &$article, mixed &$params, int $page = 0): bool
	{
		// Only execute if $article is an object and has a text property
		if (!\is_object($article) || !property_exists($article, 'text') || \is_null($article->text))
		{
			return false;
		}

		// Match: {flickr album=ALBUMID} or {flickr album=ALBUMID user=USERID} or with double quotes
		$pattern = '/{flickr\s+album=(?:"([^"]+)"|([0-9a-zA-Z]+))(?:\s+user=(?:"([^"]+)"|([0-9a-zA-Z@_]+)))?}/i';

		// Do not continue when article does not contain '{flickr }'
		if (!str_contains($article->text, '{flickr '))
		{
			return false;
		}

		// Remove macros and don't run this plugin when the content is being indexed
		if ($context === 'com_finder.indexer')
		{
			$article->text = preg_replace($pattern, '', $article->text);

			return false;
		}

		// Find all instances of plugin and put in $matches.
		$article->text = preg_replace_callback(
			$pattern,
			fn($matches) => $this->renderFlickrAlbum($matches),
			$article->text
		);

		return true;

	}

	/**
	 * Renders the Flickr album embed code.
	 *
	 * @param   array  $matches
	 *
	 * @return  string
	 *
	 * @since   25.26.0
	 */
	private function renderFlickrAlbum(array $matches): string
	{
		$albumId = $matches[1];
		$userId = $matches[2] ?? null;

		// If userId not given in tag, get from plugin settings (but no fallback value!)
		if (!$userId) {
			$userId = trim($this->params->get('flickr_userid', ''));
		}

		// If still empty, return error message
		if (empty($userId)) {
			Factory::getApplication()->enqueueMessage(
				'Flickr User ID is not set. Please specify it in the tag or plugin settings.', 'error'
			);
			return ''; // Remove the tag from the content
		}

		$embedUrl = "https://www.flickr.com/photos/$userId/albums/$albumId/player/";

		return <<<HTML
<div class="flickr-album-embed" style="overflow:hidden;position:relative;">
    <iframe src="$embedUrl" width="100%" height="500" frameborder="0" allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>
</div>
HTML;
	}
}
