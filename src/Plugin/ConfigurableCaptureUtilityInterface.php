<?php

namespace Drupal\web_page_archive\Plugin;

use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Defines the interface for configurable capture utilities.
 *
 * @see \Drupal\web_page_archive\Annotation\CaptureUtility
 * @see \Drupal\web_page_archive\Plugin\ConfigurableCaptureUtilityBase
 * @see \Drupal\web_page_archive\Plugin\CaptureUtilityInterface
 * @see \Drupal\web_page_archive\Plugin\CaptureUtilityBase
 * @see \Drupal\web_page_archive\Plugin\CaptureUtilityManager
 * @see plugin_api
 */
interface ConfigurableCaptureUtilityInterface extends CaptureUtilityInterface, PluginFormInterface {
}
