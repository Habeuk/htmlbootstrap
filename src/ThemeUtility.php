<?php
namespace Stephane888\HtmlBootstrap;

use Drupal\Core\Database\Database;
use Drupal\image\Entity\ImageStyle;
use Drupal\Component\Utility\Random;

class ThemeUtility {

  public $image_styles = false;

  // public $regions = [];
  public $themeName;

  public $themePath;

  protected $themeObject;

  /**
   */
  public function __construct()
  {
    $this->image_styles = \Drupal::entityQuery('image_style')->execute();
    // $this->regions = $this->get_regions();
    $this->themeObject = \Drupal::theme()->getActiveTheme();
    $this->themeName = $this->themeObject->getName();
    $this->themePath = drupal_get_path('theme', $this->themeName);
  }

  public function AddRequireTree($name, &$form)
  {
    $form[$name]['#required'] = true;
  }

  /**
   * add textfield array
   */
  public function addTextfieldTree($name, &$form, $title, $default)
  {
    $form[$name] = [
      '#type' => 'textfield',
      '#title' => t($title),
      '#default_value' => $default
    ];
  }

  public function addSelectTree($name, &$form, $options, $title, $default)
  {
    $form[$name] = [
      '#type' => 'select',
      '#title' => t($title),
      '#default_value' => $default,
      '#options' => $options,
      '#empty_value' => ''
    ];
  }

  public function addCheckboxTree($name, &$form, $title = 'Affiche ce block', $default = 0)
  {
    $form[$name] = [
      '#type' => 'checkbox',
      '#title' => t($title),
      '#default_value' => $default
    ];
  }

  /**
   *
   * @param string $name
   * @param array $form
   * @param string $callback
   * @param string $wrapper
   * @param string $event
   * @param string $message
   */
  public function AddAjaxTree($name, &$form, $callback, $wrapper, $event = 'change', $message = 'Verifying entry...')
  {
    $form[$name]['#ajax'] = [
      // 'callback' => '::' . $callback, // cette methode est utilisé si le formulaire provient d'une classe.
      'callback' => $callback, // on va lire la fonction de return dans le THEMENAME.theme
      'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
      'event' => $event,
      'wrapper' => $wrapper, // This element is updated with this AJAX callback.
      'progress' => [
        'type' => 'throbber',
        'message' => $message
      ]
    ];
  }

  /**
   * add textarea
   */
  public function addTextareaTree($name, &$form, $title = 'Description', $default = '')
  {
    $rand = new Random();
    $id = $rand->name(8, true);
    $form[$name] = [
      '#type' => 'textarea',
      '#title' => t($title),
      '#default_value' => $default,
      '#prefix' => '',
      '#suffix' => '',
      '#attributes' => [
        'class' => [
          'search-form advanced-edit'
        ],
        'id' => 'id-' . $id . $name
      ]
    ];
    $form[$name . 'edit-button'] = array(
      '#type' => 'markup',
      '#allowed_tags' => [
        'span',
        'div'
      ],
      '#markup' => '<div> <span  class="button button--primary edit-via-vvvbejs" data-textarea-id="id-' . $id . $name . '">editer</span> </div>'
    );
    $form[$name . 'preview'] = array(
      '#type' => 'markup',
      '#allowed_tags' => [
        'iframe',
        'div'
      ],
      '#markup' => '<div class="content-text-edit" data-textarea-id="id-' . $id . $name . '"><iframe ></iframe></div>'
    );
  }

  public function addTextareaSimpleTree($name, &$form, $title, $default)
  {
    $form[$name] = [
      '#type' => 'textarea',
      '#title' => t($title),
      '#default_value' => $default
    ];
  }

  /**
   * add image
   */
  public function addImageTree($name, &$form, $title, $default)
  {
    $form[$name] = [
      '#type' => 'managed_file',
      '#title' => t($title),
      '#default_value' => $default,
      '#upload_location' => 'public://' . $this->themeName,
      '#submit' => [
        'themeconsultant_completsavefile'
      ]
    ];
  }

  /**
   * add textfield
   */
  public function add_checkbox($name, $group, &$form, $title = 'Affiche ce block', $default = 0)
  {
    $value = theme_get_setting($group . $name, 'multiservicem1');
    // text
    $form[$group . $name] = [
      '#type' => 'checkbox',
      '#title' => t($title),
      '#default_value' => (isset($value)) ? $value : $default
    ];
  }

  /**
   * add textarea
   */
  public function add_textarea($name, $group, $form, $title = 'title', $default = '', $prefix = '', $suffix = '')
  {
    $value = theme_get_setting($group . $name, 'multiservicem1');
    // dump($value);
    // text
    $form[$group . $name] = [
      '#type' => 'textarea',
      '#title' => t($title),
      '#default_value' => (isset($value)) ? $value : $default,
      '#prefix' => $prefix,
      '#suffix' => $suffix,
      '#attributes' => [
        'class' => [
          'search-form'
        ],
        'id' => 'id-' . $group . $name
      ]
    ];
    return $form;
  }

  /**
   * add textarea with formater
   */
  public function add_textarea_html($name, $group, $form, $title = 'title', $default = '')
  {
    $value = theme_get_setting($group . $name, 'multiservicem1');
    // dump($value);
    // text
    $form[$group . $name] = [
      '#type' => 'text_format',
      '#title' => t($title),
      '#format' => (isset($value["format"])) ? $value["format"] : 'full_html',
      '#default_value' => (isset($value["value"])) ? $value["value"] : $default,
      '#attributes' => [
        'class' => [
          'search-form'
        ],
        'id' => 'id-' . $group . $name
      ]
    ];
    return $form;
  }

  /**
   * add textarea
   */
  public function add_textarea_editeur($name, $group, $form, $title = 'title', $default = '')
  {
    $value = theme_get_setting($group . $name, 'multiservicem1');

    // text
    $form[$group . $name] = [
      '#type' => 'textarea',
      '#title' => t($title),
      '#default_value' => (! empty($value)) ? $value : $default,
      '#attributes' => [
        'class' => [
          'search-form'
        ],
        'id' => 'id-' . $group . $name
      ]
    ];
    $form[$name . 'edit-button'] = array(
      '#type' => 'markup',
      '#allowed_tags' => [
        'span',
        'div'
      ],
      '#markup' => '<div> <span  class="button button--primary edit-via-vvvbejs" data-textarea-id="id-' . $group . $name . '">editer</span> </div>'
    );
    $form[$name . 'preview'] = array(
      '#type' => 'markup',
      '#allowed_tags' => [
        'iframe',
        'div'
      ],
      '#markup' => '<div data-textarea-id="id-' . $group . $name . '"><iframe ></iframe></div>'
    );
    return $form;
  }

  /**
   * add textfield
   */
  public function add_select($name, $group, &$form, $options = [], $title = 'title', $default = '', $require = null)
  {
    $value = theme_get_setting($group . $name, 'multiservicem1');
    // text
    $form[$group . $name] = [
      '#type' => 'select',
      '#title' => t($title),
      '#default_value' => (isset($value) && $value != '') ? $value : $default,
      '#options' => $options
    ];
  }

  public function AddRequire($name, $group, &$form)
  {
    $form[$group . $name]['#required'] = true;
  }

  /**
   * Api AJAX : https://api.drupal.org/api/drupal/core%21core.api.php/group/ajax
   *
   * @param string $name
   * @param string $group
   * @param object $form
   * @param string $callback
   */
  public function AddAjax($name, $group, &$form, $callback, $wrapper, $event = 'change', $message = 'Verifying entry...')
  {
    $form[$group . $name]['#ajax'] = [
      // 'callback' => '::' . $callback, // cette methode est utilisé si le formulaire provient d'une classe.
      'callback' => $callback, // on va lire la fonction de return dans le THEMENAME.theme
      'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
      'event' => $event,
      'wrapper' => $wrapper, // This element is updated with this AJAX callback.
      'progress' => [
        'type' => 'throbber',
        'message' => $message
      ]
    ];
  }

  /**
   * add image
   */
  public function add_image($name, $group, $form, $title = 'image')
  {
    $form[$group . $name] = [
      '#type' => 'managed_file',
      '#title' => t($title),
      '#default_value' => theme_get_setting($group . $name, 'multiservicem1'),
      '#upload_location' => 'public://'
    ];
    return $form;
  }

  /**
   * add image
   */
  public function add_group_image($name, $group, $form, $nombre = 3, $title = 'image')
  {
    for ($i = 1; $i <= $nombre; $i ++) {
      $name = $name . $i;
      $form[$group . $name] = [];
      $form[$group . $name] = $this->add_image($name, $group, $form[$group . $name], $title . ' ' . $i);
    }
    return $form;
  }

  /**
   */
  public function image_texte($group, $form, $i)
  {
    // //
    $name = $i . 'title';
    $form[$group . $name] = [];
    $form[$group . $name] = $this->add_textfield($name, $group, $form[$group . $name], 'Small title');
    // //
    $name = $i . 'titlebig';
    $form[$group . $name] = [];
    $form[$group . $name] = $this->add_textfield($name, $group, $form[$group . $name], 'Big title');
    // //
    $name = $i . 'description';
    $form[$group . $name] = [];
    $form[$group . $name] = $this->add_textarea($name, $group, $form[$group . $name], 'Description');
    // //
    $name = $i . 'button';
    $form[$group . $name] = [];
    $form[$group . $name] = $this->add_button($name, $group, $form[$group . $name], $title = 'Button');
    // //
    $name = $i . 'image';
    $form[$group . $name] = [];
    $form[$group . $name] = $this->add_group_image($name, $group, $form[$group . $name], $nombre = 3);
    // //
    return $form;
  }

  /**
   */
  public function list_service($group, $form, $i, $nombreBlock = 2, $title = "bloc")
  {
    for ($k = 1; $k <= $nombreBlock; $k ++) {
      // // group field
      $ss_gp = $k . '-' . $i . 'bloc';
      $form[$group . $ss_gp . 'gp'] = array(
        '#type' => 'details',
        '#title' => $title,
        '#open' => FALSE
      );
      // //
      $name = $k . '-' . $i . 'title';
      $form[$group . $name] = [];
      $form[$group . $ss_gp . 'gp'][$group . $name] = $this->add_textfield($name, $group, $form[$group . $name], 'Title');
      // //
      $name = $k . '-' . $i . 'icone';
      $form[$group . $name] = [];
      $form[$group . $ss_gp . 'gp'][$group . $name] = $this->add_textfield($name, $group, $form[$group . $name], 'Icone');
      // //
      $name = $k . '-' . $i . 'desccription';
      $form[$group . $name] = [];
      $form[$group . $ss_gp . 'gp'][$group . $name] = $this->add_textarea($name, $group, $form[$group . $name], 'Desccription');
    }
    return $form;
  }

  /**
   */
  public function typeButton()
  {
    return [
      'btn btn-primary' => 'btn btn-primary',
      'btn btn-secondary' => 'btn btn-secondary',
      'btn btn-success' => 'btn btn-success',
      'btn btn-danger' => 'btn btn-danger',
      'btn btn-warning' => 'btn btn-warning',
      'btn btn-info' => 'btn btn-info',
      'btn btn-light' => 'btn btn-light',
      'btn btn-dark' => 'btn btn-dark',
      'btn btn-link' => 'btn btn-link',
      'btn' => 'btn'
    ];
  }

  /**
   * load demo file
   */
  public function getContentFile($filename = '', $default = '')
  {
    $filename = DRUPAL_ROOT . '/' . $this->themePath . '/plugins/VvvebJs-master/demo/default/' . $filename;
    if (is_file($filename)) {
      $data = file_get_contents($filename);
      return $data;
    }
    $default = DRUPAL_ROOT . '/' . $this->themePath . '/plugins/VvvebJs-master/demo/default/' . $default;
    if (is_file($default)) {
      $data = file_get_contents($default);
      return $data;
    }
    return 'not default content Available ';
  }

  /**
   */
  public function load_images()
  {
    // $styles = ImageStyle::loadMultiple();
    $images = [];
    $image_styles = \Drupal::entityQuery('image_style')->execute();
    $images['styles'] = $image_styles;
    $table = 'file_usage'; // file_managed
    $query = Database::getConnection()->select($table, 'fi');
    $query->fields('fi', [
      'id'
    ]);
    $query->condition("module", 'multiservicem1');
    $fids = $query->execute()->fetchAll();
    if (! empty($fids)) {
      foreach ($fids as $fid) {
        $file = \Drupal\file\Entity\File::load($fid->id);
        if ($file) {
          foreach ($image_styles as $image_style) {
            $images['images'][$fid->id][$image_style] = ImageStyle::load($image_style)->buildUrl($file->getFileUri());
          }
        }
      }
    }
    return \json_encode($images);
  }

  public function listAnimationCSS()
  {
    return [
      'fadeIn' => 'fadeIn',
      'fadeInDown' => 'fadeInDown',
      'fadeInDownBig' => 'fadeInDownBig',
      'fadeInLeft' => 'fadeInLeft',
      'fadeInUp' => 'fadeInUp'
    ];
  }

  /**
   *
   * @param string $image_style_name
   * @param int $width
   * @param int $height
   * @param string $label
   */
  function createStyleImage(string $image_style_name, int $width, int $height, string $label)
  {
    $style = \Drupal\image\Entity\ImageStyle::create(array(
      'name' => $image_style_name,
      'label' => $label
    ));
    // Create effect
    $configuration = array(
      'uuid' => NULL,
      'id' => 'image_scale_and_crop',
      'weight' => 255,
      'data' => array(
        'width' => $width,
        'height' => $height
      )
    );
    //
    $effect = \Drupal::service('plugin.manager.image.effect')->createInstance($configuration['id'], $configuration);
    $style->addImageEffect($effect->getConfiguration());
    $style->save();
  }

  /**
   * Return config layout
   *
   * @return string[][]
   */
  function config_layout_theme()
  {
    $layout = [
      'node_teaser_multiservicem1' => [
        'image' => 'image',
        'statut' => 'statut',
        'price' => 'price',
        'quick_display' => 'quick_display',
        'title' => 'title'
      ],
      'block_multiservicem1_link' => [
        'main' => 'main',
        'link' => 'link',
        'image' => 'image'
      ],
      'block_multiservicem1_profil' => [
        'left' => 'left',
        'top' => 'top',
        'bottom' => 'bottom'
      ]
    ];
    return $layout;
  }

  /**
   *
   * @return string
   */
  public function rx_sx()
  {
    return '
      <ul class="navbar-nav nav-flex-icons">
                <li class="nav-item">
                    <a class="nav-link"><i class="fab fa-facebook-f"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"><i class="fab fa-twitter"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"><i class="fab fa-instagram"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"><i class="fab fa-youtube"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"><i class="fab fa-linkedin-in"></i></a>
                </li>
      </ul>
    ';
  }

  /**
   */
  public function get_regions()
  {
    return system_region_list($this->themeName, $show = REGIONS_VISIBLE);
  }

  // // **** Manage DATE
  /**
   * get D-day
   *
   * @params
   * @params $dateFin, $dateDebut=false by default
   */
  public function JourJ($dateFin, $dateDebut = false)
  {
    if ($dateDebut) {
      $date1 = new \DateTime();
    } else {
      $date1 = new \DateTime($dateDebut);
    }
    $date2 = new \DateTime($dateFin);
    // for more information about \date_diff=> http://php.net/manual/fr/function.date-diff.php
    $interval = \date_diff($date1, $date2);
    // example retuern 3 Month 14 Days, 27 days 15H
    $prefixe = \t('Day-d');
    if ($interval->m > 0) {
      if ($interval->d > 1) {
        return $prefixe . ' ' . ($interval->m + ($interval->y * 12)) . ' ' . \t('Month') . ' ' . $interval->d . ' ' . \t('days');
      } else {
        return $prefixe . ' ' . ($interval->m + ($interval->y * 12)) . ' ' . \t('Month') . ' ' . $interval->d . ' ' . \t('day');
      }
    } else {
      if ($interval->d > 0) {
        return $prefixe . ' ' . $interval->d . \t('days') . ' ' . $interval->h . ' H';
      } else {
        return $prefixe . ' ' . $interval->d . \t('day') . ' ' . $interval->h . ' H' . ' ' . $interval->i . ' mn';
      }
    }
  }

  /**
   * add button
   */
  public function add_button($name, $group, $form, $title = 'Button')
  {
    // group field
    $form[$group . $name . 'group'] = array(
      '#type' => 'details',
      '#title' => $title,
      '#open' => FALSE
    );
    // text
    $form[$group . $name . 'group'][$group . $name] = [
      '#type' => 'textfield',
      '#title' => t('text bouton'),
      '#default_value' => theme_get_setting($group . $name, 'multiservicem1')
    ];
    // link
    $form[$group . $name . 'group'][$group . $name . 'url'] = [
      '#type' => 'textfield',
      '#title' => t('URL '),
      '#default_value' => theme_get_setting($group . $name . 'url', 'multiservicem1')
    ];
    // class or attribute
    $form[$group . $name . 'group'][$group . $name . 'class'] = [
      '#type' => 'select',
      '#title' => t('Class '),
      '#default_value' => theme_get_setting($group . $name . 'class', 'multiservicem1'),
      '#options' => $this->typeButton()
    ];
    // //
    return $form;
  }

  /**
   * add textfield
   */
  public function add_textfield($name, $group, &$form, $title = 'title', $default = '')
  {
    $value = theme_get_setting($group . $name, 'multiservicem1');
    // text
    $form[$group . $name] = [
      '#type' => 'textfield',
      '#title' => t($title),
      '#default_value' => (isset($value) && $value != '') ? $value : $default
    ];
  }
}
