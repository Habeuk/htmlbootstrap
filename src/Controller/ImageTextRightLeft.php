<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\Traits\Portions;
use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\ThemeUtility;
// use Drupal\debug_log\debugLog;
use Drupal\Core\Template\Attribute;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Stephane888\HtmlBootstrap\PreprocessTemplate;

class ImageTextRightLeft implements ControllerInterface {
  use Portions;
  use StringTranslationTrait;

  protected $BasePath = '';

  protected $themeObject = null;

  function __construct($path = null)
  {
    $this->BasePath = $path;
    $this->themeObject = \Drupal::theme()->getActiveTheme();
  }

  /**
   * Load file headers and pass variable.
   * Using default template 'inline_template'
   */
  public function loadFile($options)
  {
    /**
     * Get type
     */
    if (isset($options['type'])) {
      if ($options['type'] == 'default') {
        /**
         * get header
         */
        if (isset($options['header'])) {
          $header = $options['header'];
        } else {
          $header = false;
        }
        /**
         * get content left
         */
        if (isset($options['ContentLeft'])) {
          $ContentLeft = $options['ContentLeft'];
        } else {
          $img = '/defaultfile/CarouselBootstrap/images/ab.jpg';
          $ContentLeft = $this->template_img($img);
        }

        /**
         * Get content right
         */
        if (isset($options['ContentRight'])) {
          $ContentRight = $options['ContentRight'];
        } else {
          $ContentRight = [];
          $ContentRight[] = $this->template_htmltag('We are the Best', 'h2');
          $ContentRight[] = $this->template_htmltag($this->getFauxTexte());
          $ContentRight = $this->template_inline_template($ContentRight);
          $ContentRight = $this->templateCenterVertHori($ContentRight, 'flex-column');
        }
        $filename = \file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/Default/Drupal.html.twig');
        return [
          '#type' => 'inline_template',
          '#template' => $filename,
          '#context' => [
            'header' => $header,
            'ContentLeft' => $ContentLeft,
            'ContentRight' => $ContentRight
          ]
        ];
      } elseif ($options['type'] == 'ModelM1') {
        return $this->loadModelM1($options);
      } elseif ($options['type'] == 'ModelM2') {
        return $this->loadModelM2($options);
      } elseif ($options['type'] == 'ModelM3') {
        return $this->loadModelM3($options);
      } elseif ($options['type'] == 'static_image') {
        return $this->loadStaticImage($options);
      } elseif ($options['type'] == 'content_text') {
        return $this->loadContentText($options);
      } elseif ($options['type'] == 'bloc_contact') {
        return $this->loadBlocContact($options);
      }
    }
  }

  public static function listModels()
  {
    return [
      'default' => 'default',
      'ModelM1' => "ModelM1 (model avec l'image à droite ou gauche)",
      'ModelM2' => 'ModelM2',
      'ModelM3' => 'ModelM3',
      'static_image' => 'Image Static',
      'content_text' => 'Zone de text/html',
      'bloc_contact' => 'bloc pour le contact'
    ];
  }

  public static function loadFields($model, &$form, $options)
  {
    $ThemeUtility = new ThemeUtility();
    if ($model == 'ModelM1') {
      /**
       * le champs description
       */
      $name = 'display_small';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : 0;
      $ThemeUtility->addCheckboxTree($name, $form, 'display_small', $FieldValue);
      /**
       * le champs description
       */
      $name = 'img_before';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : 0;
      $ThemeUtility->addCheckboxTree($name, $form, 'img_before', $FieldValue);
      /**
       * le champs titre
       */
      $name = 'title';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'Titre', $FieldValue);
      /**
       * le champs description
       */
      $name = 'text';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextareaSimpleTree($name, $form, 'Description', $FieldValue);

      /**
       * le champs titre
       */
      $name = 'button';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'button text', $FieldValue);

      /**
       * le champs titre
       */
      $name = 'button_link';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'button link', $FieldValue);

      /**
       * le champs image
       */
      $name = 'img_url';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addImageTree($name, $form, 'Image', $FieldValue);

      /**
       * le champs description
       */
      $name = 'img_in_bg';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : 0;
      $ThemeUtility->addCheckboxTree($name, $form, 'img_in_bg', $FieldValue);

      /**
       * le champs description
       */
      $name = 'align_text';
      $options__align_item = [
        'd-flex align-items-center' => 'aligne au centre ( verticalment)'
      ];
      $FieldValue = (! empty($options[$name])) ? $options[$name] : 0;
      $ThemeUtility->addSelectTree($name, $form, $options__align_item, 'align_item', $FieldValue);

      /**
       * le champs titre
       */
      $name = 'col_text';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : 'col-lg-6';
      $ThemeUtility->addTextfieldTree($name, $form, 'class col_text ', $FieldValue);

      /**
       * le champs titre
       */
      $name = 'col_image';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : 'col-lg-6';
      $ThemeUtility->addTextfieldTree($name, $form, 'class col_image ', $FieldValue);

      /**
       * le champs titre
       */
      $name = 'button_btn';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : 'btn-outline-success';
      $ThemeUtility->addTextfieldTree($name, $form, 'class btn_color ', $FieldValue);
    } elseif ($model == 'ModelM2') {
      /**
       * le champs sup_title
       */
      $name = 'sup_title';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'titre au dessus', $FieldValue);
      /**
       * le champs titre
       */
      $name = 'title';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'Titre', $FieldValue);
      /**
       * le champs button_link
       */
      $name = 'button_link';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '#';
      $ThemeUtility->addTextfieldTree($name, $form, 'Lien du bouton ', $FieldValue);
      /**
       * le champs button
       */
      $name = 'button';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'contenu du bouton ', $FieldValue);
      /**
       * le champs image
       */
      $name = 'img';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addImageTree($name, $form, 'Image', $FieldValue);
    } elseif ($model == 'ModelM3') {
      /**
       * le champs image
       */
      $name = 'img';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addImageTree($name, $form, 'Image', $FieldValue);
      /**
       * le champs img_small
       */
      $name = 'img_small';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addImageTree($name, $form, 'Image Small', $FieldValue);

      /**
       * le champs sup_title
       */
      $name = 'sup_title';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'titre au dessus dans le bloc', $FieldValue);

      /**
       * le champs header_title
       */
      $name = 'header_title';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'titre de l\'entete ', $FieldValue);

      /**
       * le champs header_description
       */
      $name = 'header_description';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'Description de l\'entete ', $FieldValue);
      /**
       * le champs header_description
       */
      $name = 'header_description';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextareaSimpleTree($name, $form, 'Description de l\'entete ', $FieldValue);

      /**
       * le champs nombre_item
       */
      $name = 'nombre_item';
      $nombre_item = $FieldValue = (! empty($options[$name])) ? $options[$name] : 3;
      $ThemeUtility->addTextfieldTree($name, $form, 'Nombre de lists', $FieldValue);
      $container = 'lists';

      for ($i = 0; $i < $nombre_item; $i ++) {
        $form[$container][$i] = [
          '#type' => 'details',
          '#title' => 'Blocs : ' . ($i + 1),
          '#open' => false
        ];
        /**
         * le champs titre
         */
        $name = 'text';
        $FieldValue = (! empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : '';
        $ThemeUtility->addTextfieldTree($name, $form[$container][$i], 'Titre', $FieldValue);
      }

      /**
       * le champs title
       */
      $name = 'title';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'Titre ', $FieldValue);

      /**
       * le champs title
       */
      $name = 'description';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextareaSimpleTree($name, $form, 'Description ', $FieldValue);

      /**
       * le champs title
       */
      $name = 'button';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'Button ', $FieldValue);

      /**
       * le champs button_link
       */
      $name = 'button_link';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'Button link', $FieldValue);
    } elseif ($model == 'static_image') {
      /**
       * le champs sup_title
       */
      $name = 'sup_title';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'titre au dessus', $FieldValue);
      /**
       * le champs titre
       */
      $name = 'title';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'Titre', $FieldValue);
      /**
       * le champs sup_title
       */
      $name = 'sub_title';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'titre en dessous', $FieldValue);
      /**
       * le champs image
       */
      $name = 'img';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addImageTree($name, $form, 'Image', $FieldValue);
    } elseif ($model == 'content_text') {
      $styles_images = PreprocessTemplate::loadAllStyleMedia();
      /**
       * text
       */
      $name = 'text';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextareaSimpleTree($name, $form, 'Texte', $FieldValue);

      /**
       * le champs description
       */
      $name = 'show_bg';
      $FieldValue = $show_bg = (! empty($options[$name])) ? $options[$name] : 0;
      $ThemeUtility->addCheckboxTree($name, $form, 'img_in_bg', $FieldValue);
      if ($show_bg) {
        /**
         * list images styles.
         *
         * @var array $styles_images.
         */
        $name = 'image_style_bg';
        $FieldValue = (isset($options[$name])) ? $options[$name] : 'large';
        $ThemeUtility->addSelectTree($name, $form, $styles_images, "Selectionner le style d'image", $FieldValue);
        /**
         * le champs image
         */
        $name = 'image_bg';
        $FieldValue = (isset($options[$name])) ? $options[$name] : '';
        $ThemeUtility->addImageTree($name, $form, 'Image bg', $FieldValue);
      }
    } elseif ($model == 'bloc_contact') {
      $ListWebform = ManageBlock::getListWebform();
      $styles_images = PreprocessTemplate::loadAllStyleMedia();

      /**
       * text
       */
      $name = 'title_header';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form, 'Texte', $FieldValue);

      /**
       * text
       */
      $name = 'desc_header';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addTextareaSimpleTree($name, $form, 'Texte', $FieldValue);
      /**
       * le champs titre
       */
      $name = 'col_address';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : 'col-lg-6';
      $ThemeUtility->addTextfieldTree($name, $form, 'class col_address ', $FieldValue);

      /**
       * le champs titre
       */
      $name = 'col_forms';
      $FieldValue = (! empty($options[$name])) ? $options[$name] : 'col-lg-6';
      $ThemeUtility->addTextfieldTree($name, $form, 'class col_forms ', $FieldValue);

      /**
       * list images styles.
       *
       * @var array $styles_images.
       */
      $name = 'forms';
      $FieldValue = (isset($options[$name])) ? $options[$name] : 'large';
      $ThemeUtility->addSelectTree($name, $form, $ListWebform, "Selectionner le formulaire", $FieldValue);

      /**
       * le champs description
       */
      $name = 'show_bg';
      $FieldValue = $show_bg = (! empty($options[$name])) ? $options[$name] : 0;
      $ThemeUtility->addCheckboxTree($name, $form, 'img_in_bg', $FieldValue);
      if ($show_bg) {
        /**
         * list images styles.
         *
         * @var array $styles_images.
         */
        $name = 'image_style_bg';
        $FieldValue = (isset($options[$name])) ? $options[$name] : 'large';
        $ThemeUtility->addSelectTree($name, $form, $styles_images, "Selectionner le style d'image", $FieldValue);
        /**
         * le champs image
         */
        $name = 'image_bg';
        $FieldValue = (isset($options[$name])) ? $options[$name] : '';
        $ThemeUtility->addImageTree($name, $form, 'Image bg', $FieldValue);
      }

      /**
       * le champs nombre_item
       */
      $name = 'nombre_item';
      $nombre_item = $FieldValue = (! empty($options[$name])) ? $options[$name] : 3;
      $ThemeUtility->addTextfieldTree($name, $form, 'Nombre de bloc d\'infos ', $FieldValue);
      $container = 'cards';

      for ($i = 0; $i < $nombre_item; $i ++) {
        $form[$container][$i] = [
          '#type' => 'details',
          '#title' => 'Blocs : ' . ($i + 1),
          '#open' => false
        ];
        /**
         * le champs titre
         */
        $name = 'text';
        $FieldValue = (! empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : '';
        $ThemeUtility->addTextfieldTree($name, $form[$container][$i], 'Titre', $FieldValue);

        /**
         * le champs titre
         */
        $name = 'icone';
        $FieldValue = (! empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : '';
        $ThemeUtility->addTextfieldTree($name, $form[$container][$i], 'Titre', $FieldValue);

        /**
         * le champs titre
         */
        $name = 'description';
        $FieldValue = (! empty($options[$container][$i][$name])) ? $options[$container][$i][$name] : '';
        $ThemeUtility->addTextareaSimpleTree($name, $form[$container][$i], 'Titre', $FieldValue);
      }
    }
  }

  public static function loadFieldsNodes($model, &$form, $options)
  {
    $ThemeUtility = new ThemeUtility();
    $ManageNode = new ManageNode();
    if ('content_text' == $model) {
      $contentTypes = $ManageNode->getContentType();

      /**
       * le champs selection du type de contenu
       */
      $name = 'content_type';
      $FieldValue = $bundle = (! empty($options[$name])) ? $options[$name] : '';
      $ThemeUtility->addSelectTree($name, $form, $contentTypes, 'Selectionner le type de contenu', $FieldValue);
      if ($bundle != '') {
        $listsFields = $ManageNode->getFieldsNode($bundle);

        /**
         * le champs titre
         */
        $name = 'text';
        $FieldValue = (! empty($options[$name])) ? $options[$name] : '';
        $ThemeUtility->addSelectTree($name, $form, $listsFields, 'Champs Texte', $FieldValue);

        /**
         * le champs sup_title
         */
        $name = 'nid';
        $FieldValue = (! empty($options[$name])) ? $options[$name] : 1;
        $ThemeUtility->addTextfieldTree($name, $form, 'Nid', $FieldValue);
      }
    }
  }

  protected function loadModelM3($options)
  {
    /**
     * Get content img_before
     */
    if (isset($options['img_before'])) {
      $img_before = $options['img_before'];
    } else {
      $img_before = true;
    }

    /**
     * Get content img_before
     */
    if (isset($options['img'])) {
      $img = $this->getImageUrlByFid($options['img'], $this->themeObject->getName() . '_513x500');
    } else {
      $img = [
        'img_url' => '/' . drupal_get_path('theme', $this->themeObject->getName()) . '/defaultfile/ImageTextRightLeft/ModelM2/21205351-portrait-de-confiance-jeune-homme-d-affaires-avec-les-bras-croisés-dans-le-bureau.jpg',
        'img_alt' => '',
        'img_class' => ''
      ];
    }
    /**
     * Get content img_small
     */
    if (isset($options['img_small'])) {
      $img_small = $this->getImageUrlByFid($options['img_small'], $this->themeObject->getName() . '_228x158');
    } else {
      $img_small = [
        'img_url' => '/' . drupal_get_path('theme', $this->themeObject->getName()) . '/defaultfile/ImageTextRightLeft/ModelM2/portrait-homme-affaires-afro-americain-attrayant-souriant-exterieur_33839-1295.jpg',
        'img_alt' => '',
        'img_class' => ''
      ];
    }

    /**
     * Get content sup_title
     */
    if (isset($options['sup_title'])) {
      $sup_title = $options['sup_title'];
    } else {
      $sup_title = '<i class="fas fa-hammer"></i> <span>30 year term life insurance </span>';
    }

    /**
     * Get content header_title
     */
    if (isset($options['header_title'])) {
      $header_title = $options['header_title'];
    } else {
      $header_title = 'Welcome 30 year term life insurance ';
    }
    /**
     * Get content description
     */
    if (isset($options['header_description'])) {
      $header_description = $options['header_description'];
    } else {
      $faker = \Faker\Factory::create();
      $faker->seed(129888882258);
      $header_description = $faker->unique()->realText(rand(110, 130));
    }

    /**
     * Get content description
     */
    if (isset($options['description'])) {
      $description = $options['description'];
    } else {
      $faker = \Faker\Factory::create();
      $faker->seed(129888882258);
      $description = $faker->unique()->realText(rand(220, 250));
    }

    /**
     * Get content lists
     */
    if (isset($options['lists'])) {
      $lists = $options['lists'];
    } else {
      $lists = [];
      $faker = \Faker\Factory::create();
      $faker->seed(129888882258);
      for ($i = 0; $i <= 2; $i ++) {
        $lists[] = [
          'text' => $faker->unique()->realText(rand(15, 30))
        ];
      }
    }

    /**
     * Get content header_title
     */
    if (isset($options['title'])) {
      $title = $options['title'];
    } else {
      $title = 'Provider you with quality competitive coverage';
    }

    /**
     * Get content button
     */
    if (isset($options['button'])) {
      $button = $options['button'];
    } else {
      $button = 'ours services';
    }
    /**
     * Get content button
     */
    if (isset($options['button_link'])) {
      $button_link = $options['button_link'];
    } else {
      $button_link = '#';
    }

    $filename = \file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/ModelM3/Drupal.html.twig');
    LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/ModelM3/style.scss'), 'ImageTextRightLeft-ModelM3');
    return [
      '#type' => 'inline_template',
      '#template' => $filename,
      '#context' => [
        'img' => $img,
        'img_small' => $img_small,
        'sup_title' => $sup_title,
        'title' => $title,
        'button' => $button,
        'button_link' => $button_link,
        'img_before' => $img_before,
        'header_title' => $header_title,
        'description' => $description,
        'lists' => $lists,
        'header_description' => $header_description
      ]
    ];
  }

  protected function loadContentText($options)
  {
    /**
     * Get content title
     */
    if (isset($options['title'])) {
      $title = $options['title'];
    } else {
      $title = 'Provider you with quality competitive coverage';
    }

    /**
     * Get content sup_title
     */
    if (isset($options['sup_title'])) {
      $sup_title = $options['sup_title'];
    } else {
      $sup_title = 'Provider you with quality';
    }

    /**
     * Get content texte
     */
    if (isset($options['show_bg'])) {
      $show_bg = $options['show_bg'];
    } else {
      $show_bg = 0;
    }

    /**
     * Get content texte
     */
    if (isset($options['text'])) {
      $text = $options['text'];
    } else {
      $faker = \Faker\Factory::create();
      $faker->seed(129888882258);
      $text = $faker->paragraphs(5, true);
    }
    $wrapper_attribute = new Attribute();

    if ($show_bg) {
      $wrapper_attribute->addClass('img-bg');
      $url_image = $this->getImageUrlByFid($options['image_bg'], $options['image_style_bg']);
      $wrapper_attribute->setAttribute('style', 'background-image:url(' . $url_image['img_url'] . ')');
    }
    $wrapper_attribute->addClass([
      'section',
      'zone-custom-template'
    ]);

    /**
     * .
     */

    LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Suggestions/sections/imgLeftRight/ZoneCustomTemplate/style.scss'), 'ImageTextRightLeft-ContentText');
    return [
      '#theme' => 'zone_custom_template',
      '#sup_title' => $sup_title,
      '#title' => $title,
      '#orther_vars' => [
        'show_header' => false,
        'themename' => $this->themeObject->getName()
      ],
      '#attributes' => $wrapper_attribute,
      '#text' => $text
    ];
  }

  protected function loadBlocContact($options)
  {
    /**
     * Get content title
     */
    if (isset($options['title_header'])) {
      $title_header = $options['title_header'];
    } else {
      $title_header = "Let's Lalk About Your Idea";
    }

    /**
     * Get content title
     */
    if (isset($options['desc_header'])) {
      $desc_header = $options['desc_header'];
    } else {
      $desc_header = "Our next drew much you with rank. Tore many held age hold rose than our. She literature sentiments any contrasted. Set aware joy sense young now tears china shy.";
    }

    /**
     * Get content title
     */
    if (isset($options['cards'])) {
      $cards = $options['cards'];
    } else {
      $cards = $this->getBlocContact();
    }

    /**
     * Get content title
     */
    if (isset($options['col_address'])) {
      $col_address = $options['col_address'];
    } else {
      $col_address = 'col-md-4';
    }

    /**
     * Get content title
     */
    if (isset($options['col_forms'])) {
      $col_forms = $options['col_forms'];
    } else {
      $col_forms = 'col-md-8';
    }

    /**
     * Get content title
     */
    if (isset($options['show_bg'])) {
      $show_bg = $options['show_bg'];
    } else {
      $show_bg = 0;
    }

    /**
     * Get content title
     */
    if (isset($options['image_style_bg'])) {
      $image_style_bg = $options['image_style_bg'];
    } else {
      $image_style_bg = '';
    }

    /**
     * Get content title
     */
    if (isset($options['image_bg'])) {
      $image_bg = $this->getImageUrlByFid($options['image_bg'], $image_style_bg);
    } else {
      $image_bg = null;
    }

    /**
     * Get content title
     */
    if (isset($options['forms'])) {
      $forms = ManageBlock::loadWebform($options['forms']);
    } else {
      $forms = $this->getBlocContactForms();
    }
    //
    $attribute_address = new Attribute();
    $attribute_address->addClass($col_address);
    //
    $attribute_form = new Attribute();
    $attribute_form->addClass($col_forms);
    //
    $wrapper_attribute = new Attribute();
    if ($show_bg) {
      $wrapper_attribute->addClass('show_bg');
      if ($image_bg) {
        $wrapper_attribute->setAttribute('style', 'background-image:url(' . $image_bg['img_url'] . ')');
      }
    }

    $img_before = 1;
    LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Suggestions/sections/imgLeftRight/blocContact/style.scss'), 'ImageTextRightLeft-blocContact');
    return [
      '#theme' => 'bloc_contact',
      '#title_header' => $title_header,
      '#desc_header' => $desc_header,
      '#cards' => $cards,
      '#forms' => $forms,
      '#orther_vars' => [
        'img_before' => $img_before
      ],
      '#attributes' => $wrapper_attribute,
      '#attribute_address' => $attribute_address,
      '#attribute_form' => $attribute_form
    ];
  }

  protected function getBlocContact()
  {
    return [
      [
        'text' => 'Office Location',
        'icone' => '<i class="fas fa-map-marked-alt"></i>',
        'description' => '<span>22 Baker Street,<br> London, United Kingdom,<br> W1U 3BW</span>'
      ],
      [
        'text' => 'Office Hours',
        'icone' => '<i class="fas fa-clock"></i>',
        'description' => '<span>info@yourdomain.com<br>admin@yourdomain.com</span>'
      ],
      [
        'text' => 'Phone',
        'icone' => '<i class="fas fa-phone"></i>',
        'description' => '<span>+44-20-7328-4499 <br>+99-34-8878-9989</span>'
      ],
      [
        'text' => 'Email',
        'icone' => '<i class="fas fa-envelope-open"></i>',
        'description' => '<span>info@yourdomain.com<br>admin@yourdomain.com</span>'
      ]
    ];
  }

  protected function getBlocContactForms()
  {
    return '<form action="assets/mail/contact.php" method="POST" class="contact-form">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group">
                                        <input class="form-control" id="name" name="name" placeholder="Name" type="text">
                                        <span class="alert-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input class="form-control" id="email" name="email" placeholder="Email*" type="email">
                                        <span class="alert-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input class="form-control" id="phone" name="phone" placeholder="Phone" type="text">
                                        <span class="alert-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group comments">
                                        <textarea class="form-control" id="comments" name="comments" placeholder="Tell Us About Project *"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <button type="submit" name="submit" id="submit">
                                        Send Message <i class="fa fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Alert Message -->
                            <div class="col-md-12 alert-notification">
                                <div id="message" class="alert-msg"></div>
                            </div>
                        </form>';
  }

  protected function loadStaticImage($options)
  {
    // dump($options);
    /**
     * Get content img_before
     */
    if (isset($options['img'])) {
      $img = $this->getImageUrlByFid($options['img'], $this->themeObject->getName() . '_slider_home_small');
    } else {
      $img = [
        'img_url' => '/' . drupal_get_path('theme', $this->themeObject->getName()) . '/defaultfile/bg/688263.jpg',
        'img_alt' => '',
        'img_class' => ''
      ];
    }
    /**
     * Get content sup_title
     */
    if (isset($options['sup_title'])) {
      $sup_title = $this->t($options['sup_title']);
    } else {
      $sup_title = 'Provider you with quality';
    }

    /**
     * Get content title
     */
    if (isset($options['title'])) {
      $title = $this->t($options['title']);
    } else {
      $title = 'Provider you with quality competitive coverage';
    }

    /**
     * Get content sub_title
     */
    if (isset($options['sub_title'])) {
      $sub_title = t($options['sub_title']);
    } else {
      $sub_title = 'Provider you with quality competitive coverage';
    }
    $wrapper_attribute = $wrapper_attribute_mobile = new Attribute();

    $wrapper_attribute->addClass([
      'lazyload'
    ]);
    if (! empty($img['img_url'])) {
      $style = "background-image:url('" . $img['img_url'] . "')";
      $wrapper_attribute->setAttribute('style', $style);
      $imgs = $this->getImagesSliderResponssive($options['img'], $this->themeObject->getName());
      $image_responsive = '';
      foreach ($imgs as $img_list) {
        $image_responsive .= $img_list['img_url'] . ' ' . $img_list['size'] . ',';
      }
      $wrapper_attribute->setAttribute('data-bgset', $image_responsive);
      $wrapper_attribute->setAttribute('data-sizes', 'auto');
      $wrapper_attribute_mobile->setAttribute('data-bgset', $image_responsive);
      $wrapper_attribute_mobile->setAttribute('data-sizes', 'auto');
    }
    // $filename = \file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/StaticImage/Drupal.html.twig');
    LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Suggestions/sections/imgLeftRight/StaticImage/style.scss'), 'StaticImage');
    return [
      '#theme' => 'static_image',
      '#img' => $img,
      '#sup_title' => $sup_title,
      '#sub_title' => $sub_title,
      '#title' => $title,
      '#orther_vars' => [
        'type_tag' => 'h1'
      ],
      '#attributes' => $wrapper_attribute,
      '#attributes_mobile' => $wrapper_attribute_mobile
    ];
  }

  /**
   *
   * @param string $options
   * @return array
   */
  protected function loadModelM2($options)
  {
    /**
     * Get content img_before
     */
    if (isset($options['img_before'])) {
      $img_before = $options['img_before'];
    } else {
      $img_before = false;
    }

    /**
     * Get content img_before
     */
    if (isset($options['img'])) {
      $img = $this->getImageUrlByFid($options['img'], $this->themeObject->getName() . '_570x394');
    } else {
      $img = [
        'img_url' => '/' . drupal_get_path('theme', $this->themeObject->getName()) . '/defaultfile/ImageTextRightLeft/ModelM2/portrait-homme-affaires-afro-americain-attrayant-souriant-exterieur_33839-1295.jpg',
        'img_alt' => '',
        'img_class' => ''
      ];
    }

    /**
     * Get content img_before
     */
    if (isset($options['sup_title'])) {
      $sup_title = $options['sup_title'];
    } else {
      $sup_title = 'Welcome to assurance company';
    }

    /**
     * Get content img_before
     */
    if (isset($options['button'])) {
      $button = $options['button'];
    } else {
      $button = 'Make appointment';
    }
    /**
     * Get content img_before
     */
    if (isset($options['button_link'])) {
      $button_link = $options['button_link'];
    } else {
      $button_link = '#';
    }

    /**
     * Get content img_before
     */
    if (isset($options['title'])) {
      $title = $options['title'];
    } else {
      $title = 'Get insurance for your better future';
    }

    /**
     * Get content img_before
     */
    if (isset($options['background_url'])) {
      $background_url = $options['background_url'];
    } else {
      $background_url = '/' . drupal_get_path('theme', $this->themeObject->getName()) . '/defaultfile/bg/white-fence.jpg';
    }

    /**
     *
     * @var string $filename
     */
    $filename = \file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/ModelM2/Drupal.html.twig');
    LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/ModelM2/style.scss'), 'ImageTextRightLeft-ModelM2');
    return [
      '#type' => 'inline_template',
      '#template' => $filename,
      '#context' => [
        'img' => $img,
        'sup_title' => $sup_title,
        'title' => $title,
        'button' => $button,
        'button_link' => $button_link,
        'img_before' => $img_before,
        'background_url' => $background_url
      ]
    ];
  }

  /**
   *
   * @param array $options
   * @return string[]|string[][]
   */
  protected function loadModelM1($options)
  {
    /**
     * Get content img_before
     */
    if (isset($options['img_before'])) {
      $img_before = $options['img_before'];
    } else {
      $img_before = false;
    }
    /**
     * Get content header
     */
    if (isset($options['header'])) {
      $header = $options['header'];
    } else {
      $header = '';
    }
    /**
     * Get content img_url
     */
    if (isset($options['img_url'])) {
      $img_url = $this->getImageUrlByFid($options['img_url']);
      $img_url = $img_url["img_url"];
    } else {
      $img_url = '/' . drupal_get_path('theme', $this->themeObject->getName()) . '/defaultfile/ImageTextRightLeft/ModelM1/flash-screenshot.png';
    }
    /**
     * Get content img_alt
     */
    if (isset($options['img_alt'])) {
      $img_alt = $options['img_alt'];
    } else {
      $img_alt = '';
    }
    /**
     * Get content img_class
     */
    if (isset($options['img_class'])) {
      $img_class = $options['img_class'];
    } else {
      $img_class = '';
    }
    /**
     * Get content title.
     */
    if (isset($options['title'])) {
      $title = $options['title'];
    } else {
      $faker = \Faker\Factory::create();
      $faker->seed(129888882258);
      $title = $faker->realText(rand(30, 50));
    }
    /**
     * Get content title.
     */
    if (isset($options['text'])) {
      $text = $options['text'];
    } else {
      $faker = \Faker\Factory::create();
      $faker->seed(129888882258);
      $text = $faker->realText(rand(300, 320));
    }
    /**
     * Get content button.
     */
    if (isset($options['button'])) {
      $button = $options['button'];
    } else {
      $button = 'Make appointment';
    }
    /**
     * Get content button.
     */
    if (isset($options['button_link'])) {
      $button_link = $options['button_link'];
    } else {
      $button_link = '#';
    }
    /**
     * Get content button.
     */
    if (isset($options['button_btn'])) {
      $button_btn = $options['button_btn'];
    } else {
      $button_btn = 'btn-outline-success';
    }

    /**
     * Get content button.
     */
    if (isset($options['align_text'])) {
      $align_text = $options['align_text'];
    } else {
      $align_text = '';
    }

    /**
     * Get content button.
     */
    if (isset($options['col_text'])) {
      $col_text = $options['col_text'];
    } else {
      $col_text = 'col-lg-6';
    }

    /**
     * Get content button.
     */
    if (isset($options['col_image'])) {
      $col_image = $options['col_image'];
    } else {
      $col_image = 'col-lg-6';
    }

    /**
     * Get content button.
     */
    if (isset($options['img_in_bg'])) {
      $img_in_bg = $options['img_in_bg'];
    } else {
      $img_in_bg = 0;
    }

    /**
     * display small.
     */
    if (isset($options['display_small'])) {
      $display_small = $options['display_small'];
    } else {
      $display_small = 0;
    }

    $filename = \file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/ModelM1/Drupal.html.twig');
    LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/ModelM1/style.scss'), 'ImageTextRightLeft-ModelM1');
    if ($display_small) {
      LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/ImageTextRightLeft/ModelM1/style-small.scss'), 'ImageTextRightLeft-ModelM1-small');
    }
    $Attribute_img = new Attribute();
    $Attribute_img->addClass($col_image);
    if ($img_in_bg) {
      $Attribute_img->addClass('image-in-bg');
      $Attribute_img->setAttribute('style', 'background-image:url(' . $img_url . ')');
      $img_url = false;
    }
    $Attribute_text = new Attribute();
    $Attribute_text->addClass($col_text);
    if (! empty($align_text)) {
      $Attribute_text->addClass($align_text);
    }
    return [
      '#type' => 'inline_template',
      '#template' => $filename,
      '#context' => [
        'header' => $header,
        'img_url' => $img_url,
        'img_alt' => $img_alt,
        'img_class' => $img_class,
        'title' => $title,
        'text' => $text,
        'button' => $button,
        'img_before' => $img_before,
        'button_link' => $button_link,
        'button_btn' => $button_btn,
        'attribute_text' => $Attribute_text,
        'attribute_img' => $Attribute_img
      ]
    ];
  }
}