<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\Traits\Portions;
use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\ThemeUtility;
use Drupal\Core\Template\Attribute;
use Drupal\debug_log\debugLog;
use Drupal\Core\StringTranslation\StringTranslationTrait;

class Headers implements ControllerInterface {
  use Portions;
  use StringTranslationTrait;

  protected $BasePath = '';

  protected $themeObject = null;

  function __construct($path = null)
  {
    $this->BasePath = $path;
    $this->themeObject = \Drupal::theme()->getActiveTheme();
  }

  public static function loadFields($model, &$form, $options)
  {
    $ThemeUtility = new ThemeUtility();
    /**
     * le champs titre
     */
    $name = 'show_icone_home';
    $FieldValue = (isset($options[$name])) ? $options[$name] : 0;
    $ThemeUtility->addCheckboxTree($name, $form, 'show_icone_home', $FieldValue);
    if ($model == 'LogoLeftMenuRight_M2') {
      $list_blocks = ManageBlock::getListBloks();
      /**
       * block_right
       */
      $name = 'block_right';
      $FieldValue = (isset($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addSelectTree($name, $form, $list_blocks, 'Selectionner le bloc à droite', $FieldValue);
      /**
       * menu static au scroll.
       */
      $name = 'static_menu';
      $FieldValue = (isset($options[$name])) ? $options[$name] : 1;
      $ThemeUtility->addCheckboxTree($name, $form, 'Menu static au scroll', $FieldValue);
      /**
       * contenu en dessous du menu.
       */
      $name = 'menu_bellow_content';
      $FieldValue = (isset($options[$name])) ? $options[$name] : 0;
      $ThemeUtility->addCheckboxTree($name, $form, 'Menu au dessus du contenu', $FieldValue);
      /**
       * Show selecteur langue
       */
      $name = 'show_right';
      $FieldValue = (isset($options[$name])) ? $options[$name] : 1;
      $ThemeUtility->addCheckboxTree($name, $form, 'Show block langue', $FieldValue);
    }
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
      'RxLeftMenuRight_M1' => 'RxLeftMenuRight_M1',
      'LogoLeftMenuRight_M2' => 'LogoLeftMenuRight_M2'
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
    } elseif (isset($options['type']) && $options['type'] == 'LogoLeftMenuRight_M2') {
      return $this->load__LogoLeftMenuRight_M2($options);
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

  protected function load__LogoLeftMenuRight_M2($options)
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
    /**
     * Bloc data_right.
     */
    if (isset($options['data_right'])) {
      $data_right = ManageBlock::loadBlock($options['data_right']);
    } else {
      $data_right = $this->displaySelectLang();
    }
    /**
     * bloc static_menu
     */
    if (isset($options['static_menu'])) {
      $static_menu = $options['static_menu'];
    } else {
      $static_menu = false;
    }
    /**
     * bloc static_menu
     */
    if (isset($options['menu_bellow_content'])) {
      $menu_bellow_content = $options['menu_bellow_content'];
    } else {
      $menu_bellow_content = false;
    }
    /**
     * Block
     */
    if (isset($options['show_right'])) {
      $show_right = $options['show_right'];
    } else {
      $show_right = 1;
    }

    LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/Headers/LogoLeftMenuRightM2/style.scss'), 'Header');
    LoaderDrupal::addScript(\file_get_contents($this->BasePath . '/Sections/Headers/LogoLeftMenuRightM2/script.js'), 'Header');
    return [
      '#type' => 'inline_template',
      '#template' => \file_get_contents($this->BasePath . '/Sections/Headers/LogoLeftMenuRightM2/Drupal.html.twig'),
      '#context' => [
        'branding' => $branding,
        'main_menu' => $main_menu,
        'data_right' => $data_right,
        'static_menu' => $static_menu,
        'menu_bellow_content' => $menu_bellow_content,
        'show_right' => $show_right
      ]
    ];
  }

  protected function displaySelectLang()
  {
    $attribute = new Attribute();
    // $attribute->addClass('kksa88');
    return [
      '#theme' => 'menu',
      '#attributes' => [
        'class' => [
          'select-langue'
        ]
      ],
      '#items' => [
        [
          'title' => 'Fr',
          'url' => \Drupal\Core\Url::fromUserInput('#Fr'),
          'attributes' => $attribute,
          'in_active_trail' => true
        ],
        [
          'title' => 'En',
          'url' => \Drupal\Core\Url::fromUserInput('#En'),
          'attributes' => $attribute,
          'in_active_trail' => false
        ]
      ]
    ];
  }
}