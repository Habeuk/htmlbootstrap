<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\Traits\Portions;
use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\ThemeUtility;

class Headers implements ControllerInterface {
  use Portions;

  protected $BasePath = '';

  function __construct($path = null)
  {
    $this->BasePath = $path;
  }

  public static function loadFields($model, &$form, $options)
  {
    $ThemeUtility = new ThemeUtility();
  }

  /**
   *
   * @return array
   */
  public static function listModels()
  {
    return [
      'logo_center' => 'logo_center',
      'LogoLeftMenu' => 'LogoLeftMenu',
      'LogoLeftMenuRight_M1' => 'LogoLeftMenuRight_M1',
      'RxLeftMenuRight_M1' => 'RxLeftMenuRight_M1'
    ];
  }

  /**
   * Load file headers and pass variable.
   * Using default template 'inline_template'
   */
  public function loadFile($options)
  {
    if (isset($options['type']) && $options['type'] == 'logo_center') {
      /**
       * Bloc branding
       */
      $branding = 'Votre logo';
      if (isset($options['branding'])) {
        $branding = $options['branding'];
      }
      /**
       * Bloc account_menu
       */
      $account_menu = '';
      if (isset($options['account_menu'])) {
        $account_menu = $options['account_menu'];
      }
      /**
       * Bloc rx logo
       */
      if (isset($options['rx_logo'])) {
        $rx_logo = $options['rx_logo'];
      } else {
        $rx_logo = $this->getdefault_rx_logos();
      }
      $rx_logo = $this->template_rx_logos($rx_logo, 'circle_animate');
      LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Headers/LogoCenter/style.scss'), 'Header');
      return [
        '#type' => 'inline_template',
        '#template' => \file_get_contents($this->BasePath . '/Sections/Headers/LogoCenter/Drupal.html.twig'),
        '#context' => [
          'branding' => $branding,
          'account_menu' => $account_menu,
          'rx_logo' => $rx_logo
        ]
      ];
    } elseif (isset($options['type']) && $options['type'] == 'LogoLeftMenuRight_M1') {
      return $this->load__LogoLeftMenuRight_M1($options);
    } elseif (isset($options['type']) && $options['type'] == 'RxLeftMenuRight_M1') {
      return $this->load__RxLeftMenuRight_M1($options);
    } elseif (isset($options['type']) && $options['type'] == 'LogoLeftMenu') {
      /**
       * Bloc branding
       */
      $branding = null;
      if (isset($options['branding'])) {
        $branding = $options['branding'];
      }
      /**
       * Bloc main menu.
       */
      $main_menu = null;
      if (isset($options['main_menu'])) {
        $main_menu = $options['main_menu'];
      }
      /**
       * Bloc de recherche
       */
      $search = null;
      if (isset($options['search'])) {
        $search = $options['search'];
      }
      LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Headers/LogoLeftMenu/style.scss'), 'Header');
      LoaderDrupal::addScript(\file_get_contents($this->BasePath . '/Sections/Headers/LogoLeftMenu/script.js'), 'Header');
      return [
        '#type' => 'inline_template',
        '#template' => \file_get_contents($this->BasePath . '/Sections/Headers/LogoLeftMenu/Drupal.html.twig'),
        '#context' => [
          'branding' => $branding,
          'main_menu' => $main_menu,
          'search' => $search
        ]
      ];
    }
  }

  protected function load__RxLeftMenuRight_M1($options)
  {
    /**
     * Bloc branding
     */
    $branding = null;
    if (isset($options['branding'])) {
      $branding = $options['branding'];
    }
    /**
     * Bloc main menu.
     */
    $main_menu = null;
    if (isset($options['main_menu'])) {
      $main_menu = $options['main_menu'];
    }
    LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Headers/RxLeftMenuRightM1/style.scss'), 'Header-RxLeftMenuRightM1');
    return [
      '#type' => 'inline_template',
      '#template' => \file_get_contents($this->BasePath . '/Sections/Headers/RxLeftMenuRightM1/Drupal.html.twig'),
      '#context' => [
        'branding' => $branding,
        'main_menu' => $main_menu
      ]
    ];
  }

  protected function load__LogoLeftMenuRight_M1($options)
  {
    /**
     * Bloc branding
     */
    $branding = null;
    if (isset($options['branding'])) {
      $branding = $options['branding'];
    }
    /**
     * Bloc main menu.
     */
    $main_menu = null;
    if (isset($options['main_menu'])) {
      $main_menu = $options['main_menu'];
    }
    LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Headers/LogoLeftMenuRightM1/style.scss'), 'Header');
    return [
      '#type' => 'inline_template',
      '#template' => \file_get_contents($this->BasePath . '/Sections/Headers/LogoLeftMenuRightM1/Drupal.html.twig'),
      '#context' => [
        'branding' => $branding,
        'main_menu' => $main_menu
      ]
    ];
  }
}