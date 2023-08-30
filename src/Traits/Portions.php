<?php

namespace Stephane888\HtmlBootstrap\Traits;

use Stephane888\HtmlBootstrap\LoaderDrupal;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Template\Attribute;
use Stephane888\HtmlBootstrap\HelpMigrate;

trait Portions {

  private $style_image = [
    [
      'image_style' => 'slider_home_phone',
      'size' => '475w'
    ],
    [
      'image_style' => 'slider_home_tablette',
      'size' => '992w'
    ],
    [
      'image_style' => 'slider_home_lg_desktop',
      'size' => '1920w'
    ],
    [
      'image_style' => 'slider_home',
      'size' => '3840w'
    ]
  ];

  /**
   *
   * @return string[][]
   */
  public function getdefault_rx_logos() {
    return static::getdefault_rx_logos_static();
  }

  public static function getdefault_rx_logos_static() {
    return [
      [
        'icone' => '<i class="fab fa-facebook-f"></i>',
        'url' => '#',
        'type' => 'facebook'
      ],
      [
        'icone' => '<i class="fab fa-twitter"></i>',
        'url' => '#',
        'type' => 'twitter'
      ],
      [
        'icone' => '<i class="fab fa-dribbble"></i>',
        'url' => '#',
        'type' => 'dribbble'
      ],
      [
        'icone' => '<i class="fab fa-pinterest-p"></i>',
        'url' => '#',
        'type' => 'pinterest'
      ]
    ];
  }

  public static function loadMenu($menu_name) {
    $MenuTreeParameters = new MenuTreeParameters();
    $menu_tree = \Drupal::menuTree();
    $menuTreeFooter = $menu_tree->load($menu_name, $MenuTreeParameters);
    return $menu_tree->build($menuTreeFooter);
  }

  /**
   * Retourne le template ou l'affichage pour les liens des rx.
   * Les differentes valeurs sont
   * - circle_animate
   * - ..
   *
   * @param array $param
   */
  public function template_rx_logos($rx_logos, $template) {
    $fileName = '';
    if ($template == 'circle_animate') {
      LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Utility/RxLogos/CircleAnimate/style.scss'), 'template_rx_logos');
      $fileName = \file_get_contents($this->BasePath . '/Utility/RxLogos/CircleAnimate/Drupal.html.twig');
    } elseif ($template == 'flat') {
      LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Utility/RxLogos/Flat/style.scss'), 'template_rx_logos');
      $fileName = \file_get_contents($this->BasePath . '/Utility/RxLogos/Flat/Drupal.html.twig');
    } elseif ($template == 'flat-small') {
      LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Utility/RxLogos/FlatSmall/style.scss'), 'template_rx_logos');
      $fileName = \file_get_contents($this->BasePath . '/Utility/RxLogos/FlatSmall/Drupal.html.twig');
    }
    return [
      '#type' => 'inline_template',
      '#template' => $fileName,
      '#context' => [
        'rx_logos' => $rx_logos
      ]
    ];
  }

  public static function model_rx_logos() {
    return [
      'circle_animate' => 'circle_animate',
      'flat' => 'flat Big',
      'flat-small' => 'flat Small'
    ];
  }

  public function template_fb_page_plugin($options) {
    /**
     * name_page
     */
    if ($options['name_page']) {
      $name_page = $options['name_page'];
    } else {
      $name_page = 'WB-universe';
    }
    /**
     * active_sdk
     */
    if ($options['active_sdk']) {
      $active_sdk = $options['active_sdk'];
    } else {
      $active_sdk = 0;
    }
    /**
     * url_page
     */
    if ($options['url_page']) {
      $url_page = $options['url_page'];
    } else {
      $url_page = 0;
    }
    /**
     * url_page
     */
    if ($options['id_app']) {
      $id_app = $options['id_app'];
    } else {
      $id_app = 1779199215738885;
    }

    /**
     * url_page
     */
    if ($options['height']) {
      $height = $options['height'];
    } else {
      $height = 300;
    }

    $fileName = \file_get_contents($this->BasePath . '/Utility/fbPagePlugin/Drupal.html.twig');
    return [
      '#type' => 'inline_template',
      '#template' => $fileName,
      '#context' => [
        'name_page' => $name_page,
        'active_sdk' => $active_sdk,
        'url_page' => $url_page,
        'id_app' => $id_app,
        'height' => $height
      ]
    ];
  }

  /**
   * Template to center content.
   */
  public function templateCenterVertHori($datas, $classe = null) {
    return [
      '#type' => 'inline_template',
      '#template' => '<div class="d-flex w-100 h-100 align-items-center justify-content-center {{classe}}">{{ datas | raw}}</div>',
      '#context' => [
        'datas' => $datas,
        'classe' => $classe
      ]
    ];
  }

  /**
   * template to center content.
   */
  public function template_inline_template($datas, $classe = null) {
    return [
      '#type' => 'inline_template',
      '#template' => '<div class="{{classe}}">{{datas | raw}}</div>',
      '#context' => [
        'datas' => $datas,
        'classe' => $classe
      ]
    ];
  }

  /**
   * L'image doit etre dans le dossier.
   *
   * @param string $img_url
   * @param string $alt
   * @return string[]
   */
  public function template_img($img_url, $alt = '', $classe = '') {
    if (!$this->themeObject) {
      die('');
    }
    return [
      '#type' => 'inline_template',
      '#template' => '<img src="{{img_url}}" class="img-fluid {{classe}}" alt="{{alt}}" />',
      '#context' => [
        'img_url' => '/' . HelpMigrate::getPatch('theme', $this->themeObject->getName()) . $img_url,
        'alt' => $alt,
        'classe' => $classe
      ]
    ];
  }

  /**
   * get fake texte.
   *
   * @return string
   */
  public function getFauxTexte() {
    return "
      Lorem ipsum dolor sit amet consectetur adipisicing elit sedc dnmo eiusmod tempor incididunt ut labore et dolore magna
      aliqua uta enim ad minim ven iam quis nostrud exercitation ullamco labor nisi ut aliquip exea commodo consequat duis
      aute irudre dolor in elit sed uta labore dolore reprehender.
      <br>
      Lorem ipsum dolor sit amet consectetur adipisicing elit sedc dnmo eiusmod tempor incididunt ut labore et dolore magna aliqua uta enim ad
      minim ven iam quis nostrud exercitation ullamco labor nisi ut aliquip exea commodo consequat
      duis aute irudre dolor in elit sed uta labore dolore reprehender.
      ";
  }

  public function getImageUrlByFid($fid, $image_style = null) {
    if (!empty($fid[0])) {
      $file = \Drupal\file\Entity\File::load($fid[0]);
      if ($file) {
        if (!empty($image_style) && \Drupal\image\Entity\ImageStyle::load($image_style)) {
          $img_url = \Drupal\image\Entity\ImageStyle::load($image_style)->buildUrl($file->getFileUri());
        } else {
          $img_url = file_create_url($file->getFileUri());
        }

        return [
          'img_url' => $img_url
        ];
      }
    }
    return [];
  }

  public function getImagesSliderResponssive($fid, $theme_name) {
    $imgs = [];
    $styles = $this->style_image;
    $i = 0;
    foreach ($styles as $style) {
      $imgs[$i] = $this->getImageUrlByFid($fid, $theme_name . '_' . $style['image_style']);
      $imgs[$i]['size'] = $style['size'];
      $i++;
    }
    return $imgs;
  }

  public function getResponsiveImageUrlByFid($fid, $style_image) {
    $imgs = [];
    $i = 0;
    foreach ($style_image as $key => $style) {
      $imgs[$i] = $this->getImageUrlByFid($fid, $key);
      $imgs[$i]['size'] = $style['size'];
      $i++;
    }
    return $imgs;
  }

  public function setBackgroundBgset(Attribute &$wrapper_attribute, $imgs = []) {
    $image_responsive = '';
    foreach ($imgs as $img_list) {
      $image_responsive .= $img_list['img_url'] . ' ' . $img_list['size'] . ',';
    }
    $wrapper_attribute->setAttribute('data-bgset', $image_responsive);
    $wrapper_attribute->setAttribute('data-sizes', 'auto');
  }

  public function setSyleImage($styles) {
    $this->style_image = $styles;
  }

  /**
   * Ajoute un conteneur html, par defaut un p.
   * Utilise la methode 'html_tag'.
   *
   * @param string $string
   * @param string $tag
   * @return string[]
   */
  public function template_htmltag($string, $tag = 'p', $class = null, $id = null) {
    return static::template_htmltag__static($string, $tag, $class, $id);
  }

  public static function template_htmltag__static($string, $tag = 'p', $class = null, $id = null) {
    $html = [
      '#type' => 'html_tag',
      '#tag' => $tag,
      '#value' => $string
    ];
    if ($class) {
      $html['#attributes']['class'][] = $class;
    }
    if ($id) {
      $html['#attributes']['id'] = $id;
    }
    return $html;
  }

  /**
   * Build dropdown menu
   *
   * @param string $class
   * @param array $links
   * @return string[]
   */
  public function buildDropdownMenu($links, $class = '') {
    $attribute = new Attribute();
    $menu = [
      '#theme' => 'menu',
      '#attributes' => [
        'class' => [
          'dropdown-menu',
          $class
        ]
      ],
      '#items' => []
    ];
    $data_dropdown_menu = '';
    if (!empty($links)) {
      $first_link = reset($links);
      if (!isset($first_link['label'])) {
        $links = $this->TransformdToDropdownMenu($links);
      }
    }

    foreach ($links as $link) {
      if ($link['active']) {
        $data_dropdown_menu = $link['label'];
      }
      $menu['#items'][] = [
        'title' => $link['label'],
        'url' => \Drupal\Core\Url::fromUserInput('#'),
        'attributes' => $attribute,
        'in_active_trail' => $link['active']
      ];
    }
    return [
      '#theme' => 'dropdown_menu',
      '#attributes' => [
        'class' => [
          'dropdown-menu'
        ]
      ],
      '#orther_vars' => [
        'button_class' => ''
      ],
      '#data' => $data_dropdown_menu,
      '#menu' => $menu
    ];
  }

  protected function TransformdToDropdownMenu($links) {
    $first_item = true;
    $new_links = [];
    foreach ($links as $key => $link) {
      $new_links[] = [
        'label' => $link,
        'active' => $first_item
      ];
      $first_item = false;
    }
    return $new_links;
  }

  protected function getUrlImageFromNodeItem(\Drupal\file\Plugin\Field\FieldType\FileFieldItemList $items, $style = "") {
    $result = $items->getValue();
    $result = reset($result);
    if (!empty($result['target_id'])) {
      return $this->getImageUrlByFid([
        $result['target_id']
      ]);
    }
    return null;
  }

  /**
   * Contruit le fichier css à partir des chemins abolutes;
   *
   * @param string $Scss_file
   * @param string $css_file
   */
  public function buildCss($Scss_file, $css_file) {
    require_once DRUPAL_ROOT . '/../vendor/stephane888/htmlbootstrap/vendor/autoload.php';
    $parser = new \ScssPhp\ScssPhp\Compiler();
    $data = '@import "' . DRUPAL_ROOT . '/../vendor/stephane888/htmlbootstrap/scss/defaut/loader_model_module.scss";';
    // $data = '@import "/siteweb/sogesti/public/vendor/stephane888/htmlbootstrap/scss/defaut/loader_model1.scss";';
    $data .= '@import "' . $Scss_file . '";';
    $result = $parser->compile($data);
    $monfichier = fopen($css_file, 'w+');
    fputs($monfichier, $result);
    fclose($monfichier);
  }

  /**
   *
   * @param \Drupal\node\Entity\Node $node
   * @param array $options
   *          doit contenir la clee à utilisé dans le template et le machine name du champs ['image'=>'field_image']
   * @return array
   */
  protected function loadFieldsNode(\Drupal\node\Entity\Node $node, $options) {
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    if ($language && $node->hasTranslation($language)) {
      foreach ($options as $key => $field) {

        if (!empty($field)) {
          $translation = $node->getTranslation($language);
          $options[$key] = $translation->{$field}->view([
            'label' => 'hidden'
          ]);
        } else {
          unset($options[$key]);
        }
      }
    } else {
      foreach ($options as $key => $field) {
        if (!empty($field)) {
          $options[$key] = $node->{$field}->view([
            'label' => 'hidden'
          ]);
        } else {
          unset($options[$key]);
        }
      }
    }
    return $options;
  }
}
