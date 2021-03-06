<?php

namespace Drupal\wpa_screenshot_capture\Plugin\CaptureUtility;

use Drupal\Core\Form\FormStateInterface;
use Drupal\web_page_archive\Plugin\ConfigurableCaptureUtilityBase;
use Drupal\wpa_screenshot_capture\Plugin\CaptureResponse\ScreenshotCaptureResponse;
use Screen\Capture;
use Screen\Image\Types;
use PhantomInstaller\PhantomBinary;

/**
 * Captures screenshot of a remote uri.
 *
 * @CaptureUtility(
 *   id = "wpa_screenshot_capture",
 *   label = @Translation("Screenshot capture utility", context = "Web Page Archive"),
 *   description = @Translation("Captures snapshot images for given URL", context = "Web Page Archive")
 * )
 */
class ScreenshotCaptureUtility extends ConfigurableCaptureUtilityBase {
  /**
   * Most recent response.
   *
   * @var string|null
   */
  private $response = NULL;

  /**
   * {@inheritdoc}
   */
  public function capture(array $data = []) {
    // Handle missing URLs.
    if (!isset($data['url'])) {
      throw new \Exception('Capture URL is required');
    }

    // Configure PhantomJS capture tool.
    $screenCapture = new Capture($data['url']);
    $screenCapture->binPath = PhantomBinary::getDir() . '/';
    $screenCapture->setWidth((int) $this->configuration['width']);
    $screenCapture->setClipWidth((int) $this->configuration['clip_width']);
    if (!empty($this->configuration['background_color'])) {
      $screenCapture->setBackgroundColor($this->configuration['background_color']);
    }
    $screenCapture->setImageType($this->configuration['image_type']);
    if (!empty($this->configuration['user_agent'])) {
      $screenCapture->setUserAgentString($this->configuration['user_agent']);
    }

    // Determine file locations.
    $file_name = preg_replace('/[^a-z0-9]+/', '-', strtolower($data['url']));
    $scheme = file_default_scheme();
    $folder_path = \Drupal::service('file_system')->realpath("{$scheme}://");
    $file_location = "web-page-archive/screenshots/{$data['web_page_archive']->id()}/{$data['run_uuid']}/{$file_name}.{$this->configuration['image_type']}";
    $real_file_path = "$folder_path/$file_location";
    $file_path = "{$scheme}://{$file_location}";

    // Save screenshot and set our response.
    $screenCapture->save($real_file_path);
    $this->response = new ScreenshotCaptureResponse($file_path, $data['url']);

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getResponse() {
    return $this->response;
  }

  /**
   * {@inheritdoc}
   */
  public function missingDependencies() {
    $capture_message = $this->t('Screen Capture package missing.');
    $install_message = $this->t('The Web Page Archive module must be installed via composer.');
    $installer_message = $this->t('Phantom Installer package missing.');
    $binary_message = $this->t('PhantomJS binary missing. composer.json missing "post-install-cmd" command.');

    $required_dependencies = [
      '\\Screen\\Capture' => "\\Screen\\Capture: {$capture_message} {$install_message}",
      '\\PhantomInstaller\\Installer' => "\\PhantomInstaller\\Installer: {$installer_message} {$install_message}",
      '\\PhantomInstaller\\PhantomBinary' => "\\PhantomInstaller\\PhantomBinary: {$binary_message}",
    ];

    $missing_dependencies = [];
    foreach ($required_dependencies as $dependency => $message) {
      if (!class_exists($dependency)) {
        $missing_dependencies[$dependency] = $message;
      }
    }

    return $missing_dependencies;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['intro'] = [
      '#markup' => $this->t('Some instructions go here.'),
    ];
    $form['width'] = [
      '#type' => 'number',
      '#title' => $this->t('Capture width (in pixels)'),
      '#description' => $this->t('Specify the width you would like to capture.'),
      '#default_value' => isset($this->configuration['width']) ? $this->configuration['width'] : 1280,
      '#required' => TRUE,
    ];
    $form['clip_width'] = [
      '#type' => 'number',
      '#title' => $this->t('Capture clip width (in pixels)'),
      '#description' => $this->t('Specify the clip width you would like to capture.'),
      '#default_value' => isset($this->configuration['clip_width']) ? $this->configuration['clip_width'] : 1280,
      '#required' => TRUE,
    ];
    $image_types = Types::available();
    $image_types = array_combine($image_types, $image_types);
    $form['image_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Image type'),
      '#options' => $image_types,
      '#empty_option' => $this->t('Select an image type'),
      '#default_value' => isset($this->configuration['image_type']) ? $this->configuration['image_type'] : '',
      '#required' => TRUE,
    ];
    $form['background_color'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Browser background color'),
      '#description' => $this->t('Specify the browser background color in hexidecimal format. e.g. "#ffffff"'),
      '#default_value' => isset($this->configuration['background_color']) ? $this->configuration['background_color'] : '#ffffff',
    ];
    $form['user_agent'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Browser user agent'),
      '#description' => $this->t('Specify the browser user agent. e.g. "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36"'),
      '#default_value' => isset($this->configuration['user_agent']) ? $this->configuration['user_agent'] : '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);

    $this->configuration['width'] = $form_state->getValue('width');
    $this->configuration['clip_width'] = $form_state->getValue('clip_width');
    $this->configuration['background_color'] = $form_state->getValue('background_color');
    $this->configuration['user_agent'] = $form_state->getValue('user_agent');
    $this->configuration['image_type'] = $form_state->getValue('image_type');
  }

}
