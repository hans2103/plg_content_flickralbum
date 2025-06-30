<?php

/**
 * @package         Joomla.Plugin
 * @subpackage      Content.FlickrAlbum
 *
 * @copyright   (C) 2025 HKweb <https://hkweb.nl>
 * @license         GNU General Public License version 3 or later; see LICENSE.txt
 * @link            https://hkweb.nl
 */

\defined('_JEXEC') or die;

/**
 *
 * @param   string  $albumId   The Flickr Album ID
 * @param   string  $userId    The Flickr User ID
 * @param   string  $embedUrl  The Flickr Embed code
 */

/** @var array $displayData */
extract($displayData);

$albumId  = $albumId ?? false;
$userId   = $userId ?? false;
$embedUrl = $embedUrl ?? false;

if ($albumId): ?>
	<div class="flickr-album-embed" style="overflow:hidden;position:relative;">
		<iframe src="<?php echo htmlspecialchars($embedUrl, ENT_QUOTES, 'UTF-8'); ?>" width="100%" height="500" allowfullscreen style="border:0;"></iframe>
	</div>
<?php endif;
