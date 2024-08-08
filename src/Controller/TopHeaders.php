<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\Traits\Portions;
use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\ThemeUtility;
use Drupal\Core\Template\Attribute;
use Drupal\debug_log\debugLog;
use Drupal\Core\StringTranslation\StringTranslationTrait;

class TopHeaders implements ControllerInterface {
  use Portions;
  use StringTranslationTrait;

  protected $BasePath = '';

  protected $themeObject = null;

  function __construct($path = null)
  {
    $this->BasePath = $path;
    $this->themeObject = \Drupal::theme()->getActiveTheme();
  }

  public function loadFile($options)
  {
    if (isset($options['type'])) {
      $wrapper_attribute = new Attribute();
      if ($options['type'] == 'default') {
        /**
         * Get content sup_title
         */
        if (isset($options['items'])) {
          $items = $options['items'];
        } else {
          $items = $this->loadDefaultText();
        }
        LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Suggestions/sections/TopHeaders/Default/style.scss'), 'TopHeaders');
        return [
          '#theme' => 'top_header_default',
          '#items' => $items,
          '#orther_vars' => [],
          '#attributes' => $wrapper_attribute
        ];
      }
    }
  }

  public static function listModels()
  {
    return [
      'default' => 'model default'
    ];
  }

  public static function loadFields($model, &$form, $options)
  {
    $ThemeUtility = new ThemeUtility();
    /**
     * le champs titre
     */
    $name = 'title';
    $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
    $ThemeUtility->addTextfieldTree($name, $form, 'Titre', $FieldValue);
  }

  /**
   * loadDefaultText
   */
  protected function loadDefaultText()
  {
    $items = [];
    $items[] = 'Welcome to Emarket ! Wrap new offers / gift every single day on Weekends – New Coupon code: Happy2017';
    $items[] = $this->buildDropdownMenu([
      [
        'label' => '€ Euro',
        'active' => true
      ],
      [
        'label' => '£ Pound Sterling',
        'active' => false
      ],
      [
        'label' => '$ US Dollar',
        'active' => false
      ]
    ], 'select-currency');
    $items[] = $this->buildDropdownMenu([
      [
        'label' => 'French',
        'active' => true
      ],
      [
        'label' => 'English',
        'active' => true
      ]
    ], 'select-langue');
    return $items;
  }
}