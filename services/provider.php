<?php

/**
 * @package         Joomla.Plugin
 * @subpackage      System.flickralbum
 *
 * @copyright   (C) 2025 HKweb <https://hkweb.nl>
 * @license         GNU General Public License version 3 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use HKweb\Plugin\Content\FlickrAlbum\Extension\FlickrAlbum;

return new class () implements ServiceProviderInterface {
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 *
	 * @since   4.4.0
	 */
	public function register(Container $container): void
	{
		$container->set(
			PluginInterface::class,
			function (Container $container) {
				$plugin = new FlickrAlbum(
					$container->get(DispatcherInterface::class),
					(array) PluginHelper::getPlugin('content', 'flickralbum'),
				);
				$plugin->setApplication(Factory::getApplication());

				return $plugin;
			}
		);
	}
};
