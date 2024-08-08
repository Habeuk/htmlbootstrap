<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\Traits\Portions;
use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\ThemeUtility;
use Drupal\debug_log\debugLog;

class StylePage implements ControllerInterface {
  use Portions;

  protected $BasePath = '';

  protected $themeObject = null;

  function __construct($path = null)
  {
    $this->BasePath = $path;
    $this->themeObject = \Drupal::theme()->getActiveTheme();
  }

  /**
   *
   * {@inheritdoc}
   * @see \Stephane888\HtmlBootstrap\Controller\ControllerInterface::loadFile()
   */
  public function loadFile($options)
  {
    if (isset($options['type'])) {
      if ($options['type'] == 'page-consultant-01') {
        LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/StylePage/page-consultant-01/style.scss'), 'init_-_header_StylePage-page-consultant-01');
      }
    }
  }

  public static function listModels()
  {
    return [
      'page-consultant-01' => 'page-consultant-01'
    ];
  }

  public static function loadFields($model, &$form, $options)
  {
    ;
  }
}