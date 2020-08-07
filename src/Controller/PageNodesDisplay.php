<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\Traits\Portions;
use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\ThemeUtility;
use Drupal\node\Entity\Node;
use Drupal\debug_log\debugLog;
use Drupal\Core\Template\Attribute;
use Stephane888\HtmlBootstrap\PreprocessTemplate;

class PageNodesDisplay {
  use Portions;

  protected $mode_display = [];

  protected $BasePath = '';

  protected $themeObject = null;

  protected static $machine_name = null;

  protected static $listes_fields = [];

  public static $default_options = [];

  function __construct($path = null)
  {
    $this->BasePath = $path;
    $this->themeObject = \Drupal::theme()->getActiveTheme();
  }

  /**
   * Ajoute des options sur les pages de nodes.
   *
   * @param object $variables
   * @param array $displays
   * @param string $machine_name
   */
  public function loadPagePlugins(&$variables, $displays, Node $node, $theme_name = 'themeconsultant')
  {
    // dump($displays);
    $machine_name = $node->bundle();

    if (isset($displays[$machine_name])) {
      if (isset($displays[$machine_name]['addedplugins']))
        foreach ($displays[$machine_name]['addedplugins'] as $key => $display) {
          $region_name = (! empty($display['region'])) ? $display['region'] : 'header';
          if ($key == 'headerbackground' && $display['status']) {
            if ($display['provider'] == 'custom') {
              $headerbackground = [
                '#theme' => 'header_bg',
                '#content_top' => '<p>content_top</p>',
                '#image' => $this->getImageUrlByFid($display['image'], $theme_name . '_1920_370')
              ];
              $headerbackground['#content_top'] = $variables['page'][$region_name][$theme_name . '_breadcrumbs'];
              unset($variables['page'][$region_name][$theme_name . '_breadcrumbs']);
              $variables['page'][$region_name]['header_bg'][] = $headerbackground;
            } elseif ($display['provider'] == 'node') {
              $atrribute = new Attribute();
              $display['fields'] = $this->loadFieldsNode($node, $display['fields']);
              // dump($display['fields']['image']['#items']->getValue());
              if (! empty($display['fields']['image']['#items'])) {
                $display['fields']['image'] = $this->getUrlImageFromNodeItem($display['fields']['image']['#items']);
                $atrribute->setAttribute('style', 'background-image:url(' . $display['fields']['image']['img_url'] . ')');
              }

              // debugLog::logs($display['fields']['image']['#items'], 'image_field', 'kint_custom');
              $headerbackground = [
                '#theme' => 'header_bg',
                '#content_top' => (! empty($display['fields']['content_top'])) ? $display['fields']['content_top'] : '',
                '#content_center' => (! empty($display['fields']['content_center'])) ? $display['fields']['content_center'] : '',
                '#content_bottom' => (! empty($display['fields']['content_bottom'])) ? $display['fields']['content_bottom'] : '',
                '#image' => (! empty($display['fields']['image'])) ? $display['fields']['image'] : '',
                '#attributes' => $atrribute
              ];
              $variables['page'][$region_name]['header_bg'][] = $headerbackground;
            }
            // LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Suggestions/sections/PageNodesDisplay/headerBg/style.scss'), 'PageNodesDisplay-header_bg');
          } elseif ($key == 'static-header' && $display['status']) {
            $wrapper_attribute = $wrapper_attribute_mobile = new Attribute();
            $wrapper_attribute->addClass([
              'lazyload'
            ]);
            $img = [];
            $image = $node->get($display['img'])->getValue();
            $image = reset($image);
            if (! empty($image) && $image["target_id"]) {
              $image = [
                $image["target_id"]
              ];
              $img = $this->getImageUrlByFid($image, $this->themeObject->getName() . '_slider_home_small');
            }
            if (! empty($img['img_url'])) {
              $style = "background-image:url('" . $img['img_url'] . "')";
              $wrapper_attribute->setAttribute('style', $style);
              $imgs = $this->getImagesSliderResponssive($image, $this->themeObject->getName());
              $image_responsive = '';
              foreach ($imgs as $img_list) {
                $image_responsive .= $img_list['img_url'] . ' ' . $img_list['size'] . ',';
              }
              $wrapper_attribute->setAttribute('data-bgset', $image_responsive);
              $wrapper_attribute->setAttribute('data-sizes', 'auto');
              $wrapper_attribute_mobile->setAttribute('data-bgset', $image_responsive);
              $wrapper_attribute_mobile->setAttribute('data-sizes', 'auto');
            }
            $static_image = [
              '#theme' => 'static_image',
              '#img' => $img,
              '#sup_title' => $node->{$display['sup_title']}->view(),
              '#sub_title' => $node->{$display['sub_title']}->view(),
              '#title' => $node->{$display['title']}->view(),
              '#orther_vars' => [
                'type_tag' => 'h2'
              ],
              '#attributes' => $wrapper_attribute,
              '#attributes_mobile' => $wrapper_attribute_mobile
            ];
            $variables['page']['before_content']['header_bg'][] = $static_image;
          }
        }
      if (isset($displays[$machine_name]['nodes']))
        foreach ($displays[$machine_name]['nodes'] as $key => $display) {
          if ($display['status']) {
            $variables['page']['header']['header_bg']['#attached']['library'][] = $theme_name . '/page-node';
            /**
             * ajout de la class pour faire remonté le contenu.
             */
            if (! empty($display['options_nodes']['show_content_over'])) {
              $variables['page']['content']['wrapper_attribute']['class'][] = 'content-over';
              $variables['page']['header']['header_bg']['#attached']['library'][] = $theme_name . '/page_content_over';
            }
          }
        }
    }
  }

  public function genereFiles($displays, $dir)
  {
    foreach ($displays as $display) {

      /**
       * Find plugins activate.
       */
      $this->genereNodesFiles($display['nodes'], $dir);
      /**
       * Add css for pluggins.
       */
      if (isset($display['addedplugins'])) {
        $this->generePlugginsFiles($display['addedplugins'], $dir);
      }
    }
    /**
     * Build scss for page.
     */
    $directory = DRUPAL_ROOT . '/' . $dir . '/scss';
    $this->createfile('page_node_scss', $directory, '.scss');

    /**
     * Build scss for node.
     */
    $directory = DRUPAL_ROOT . '/' . $dir . '/scss';
    $this->createfile('node_scss', $directory, '.scss');

    /**
     * Delete file session
     */
    $this->deleteFileSession();
  }

  protected function deleteFileSession()
  {
    $files = [
      'node_scss',
      'page_node_scss'
    ];
    LoaderDrupal::deleteSession($files);
  }

  public function genereNodesFiles($displays, $dir)
  {
    foreach ($displays as $tamplate_name => $display) {
      if ($display['status'] && $display['model']) {
        $this->loadNodeFile($display, $tamplate_name);
        $directory = DRUPAL_ROOT . '/' . $dir . '/templates/nodes';
        $this->createfile($tamplate_name, $directory);
      }
    }
  }

  public function generePlugginsFiles($addedplugins, $dir)
  {
    foreach ($addedplugins as $key => $value) {
      if ($key == 'headerbackground' && $value['status']) {
        $content_file = file_get_contents($this->BasePath . '/Suggestions/sections/PageNodesDisplay/headerBg/style.scss');
        LoaderDrupal::addData('page_node_scss', $content_file, $key);
      } elseif ($value) {
        ;
      }
    }
  }

  public function loadNodeFile($display, $page)
  {
    if ($display['model'] == 'default') {
      $content_file = file_get_contents($this->BasePath . '/Templates/nodes/default/Drupal.html.twig');
      LoaderDrupal::addData($page, $content_file, $display['model']);
      $content_file = file_get_contents($this->BasePath . '/Templates/nodes/default/style.scss');
      LoaderDrupal::addData('node_scss', $content_file, $display['model']);
    } elseif ($display['model'] == 'ebook') {
      $content_file = file_get_contents($this->BasePath . '/Templates/nodes/ebook/Drupal.html.twig');
      LoaderDrupal::addData($page, $content_file, $display['model']);
      $content_file = file_get_contents($this->BasePath . '/Templates/nodes/ebook/style.scss');
      LoaderDrupal::addData('node_scss', $content_file, $display['model']);
    } elseif ($display['model'] == 'header_static_image') {
      $content_file = file_get_contents($this->BasePath . '/Templates/nodes/headerStaticImage/Drupal.html.twig');
      LoaderDrupal::addData($page, $content_file, $display['model']);
      $content_file = file_get_contents($this->BasePath . '/Templates/nodes/headerStaticImage/style.scss');
      LoaderDrupal::addData('node_scss', $content_file, $display['model']);
    } elseif ('logement_1000px' == $display['model']) {
      $content_file = file_get_contents($this->BasePath . '/Templates/nodes/Logement1000px/Drupal.html.twig');
      LoaderDrupal::addData($page, $content_file, $display['model']);
      $content_file = file_get_contents($this->BasePath . '/Templates/nodes/Logement1000px/style.scss');
      LoaderDrupal::addData('node_scss', $content_file, $display['model']);
    }
  }

  public static function loadFields($model, &$form, $options)
  {
    // dump($options);
    $ThemeUtility = new ThemeUtility();
    $ManageNode = new ManageNode();
    $content_types = [
      'all-content-type' => 'model de contenu par default (Tous type de page)'
    ];
    $content_types += $ManageNode->getContentType();
    foreach ($content_types as $machine_name => $content_type) {
      static::$default_options = [
        'machine_name' => $machine_name,
        'default_value' => $content_type
      ];
      /**
       * Add section
       */
      $form[$machine_name] = array(
        '#type' => 'details',
        '#title' => $content_type,
        "#tree" => true
      );
      /**
       * Le champs selection du type de contenu.
       * Not use
       */
      // $name = 'model';
      // $FieldValue = (! empty($options[$machine_name][$name])) ? $options[$machine_name][$name] : '';
      // $ThemeUtility->addSelectTree($name, $form[$machine_name], static::listModels(), 'Selectionner le modele', $FieldValue);

      /**
       * Le champs permettant d'identifier si le contenu est surchargé.
       */
      $name = 'status';
      $FieldValue = (isset($options[$machine_name][$name])) ? $options[$machine_name][$name] : 0;
      $ThemeUtility->addCheckboxTree($name, $form[$machine_name], 'Utiliser un theme personnalisé', $FieldValue);

      /**
       * Classe
       */
      $name = 'classes';
      $FieldValue = (isset($options[$machine_name][$name])) ? $options[$machine_name][$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form[$machine_name], 'Classe PAGE CSS (region content) use by par tous les pages ou specifiques à une entité', $FieldValue);

      //
      $options[$machine_name] = (isset($options[$machine_name])) ? $options[$machine_name] : [];
      $form[$machine_name] = (isset($form[$machine_name])) ? $form[$machine_name] : [];
      static::loadNodeTemplate($machine_name, $form[$machine_name], $options[$machine_name], $ThemeUtility, $ManageNode);
    }
  }

  protected static function loadNodeTemplate($machine_name, &$form, $options, ThemeUtility $ThemeUtility, ManageNode $ManageNode)
  {
    // $ThemeUtility = new ThemeUtility();
    static::$machine_name = $machine_name;
    $listes_fields = [];
    if ($machine_name != 'all-content-type') {
      $listes_fields = $ManageNode->getFieldsNode($machine_name);
      static::$machine_name = $machine_name;
      static::$listes_fields = $listes_fields;
      /**
       * features
       */
      $container = 'addedplugins';
      // $form['messages'][] = static::template_htmltag__static('Ajout de fonctionnalités ', 'h4');
      if (! isset($options[$container])) {
        $options[$container] = [];
      }
      $form[$container] = array(
        '#type' => 'details',
        '#title' => 'Ajout de fonctionnalités',
        "#open" => false
      );
      $callback = 'page_header_background';
      $wrapper = 'header_background' . $machine_name;
      static::loadHeaderBackground($form[$container], $options[$container], $callback, $wrapper, $ThemeUtility, $ManageNode);
      $callback = 'page_header_background';
      $wrapper = 'header_background' . $machine_name;
      static::loadStaticHeader($form[$container], $options[$container], $callback, $wrapper, $ThemeUtility, $ManageNode);
    }

    /**
     * Nodes displys
     */
    if (! isset($options['nodes'])) {
      $options['nodes'] = [];
    }
    if ($machine_name == 'all-content-type') {
      $machine_name = '';
    } elseif ($machine_name != '') {
      $machine_name = '--' . $machine_name;
    }
    $form['messages2'][] = static::template_htmltag__static('Affichage des modes d\'affichage ', 'h4');
    $form['messages2'][] = static::template_htmltag__static('Effecer le cache à chaque foix que vous modifiez le template ', 'small');
    foreach (static::mode_display() as $key => $value) {
      if ($key == 'default-node') {
        $key = '';
      } elseif ($key != '') {
        $key = '--' . $key;
      }
      $container = 'node' . $machine_name . '' . $key;
      /**
       * Add section
       */
      $form['nodes'][$container] = array(
        '#type' => 'details',
        '#title' => $value,
        "#open" => false
      );
      /**
       * Le champs selection du type de contenu
       */
      $name = 'status';
      $FieldValue = (isset($options['nodes'][$container][$name])) ? $options['nodes'][$container][$name] : 0;
      $ThemeUtility->addCheckboxTree($name, $form['nodes'][$container], 'Utiliser un theme personnalisé', $FieldValue);
      /**
       * classe
       */
      $name = 'classes';
      $FieldValue = (isset($options['nodes'][$container][$name])) ? $options['nodes'][$container][$name] : '';
      $ThemeUtility->addTextfieldTree($name, $form['nodes'][$container], 'Classe css', $FieldValue);
      /**
       * Le champs selection du type de contenu
       */
      $name = 'model';
      $FieldValue = $model = (! empty($options['nodes'][$container][$name])) ? $options['nodes'][$container][$name] : '';
      $ThemeUtility->addSelectTree($name, $form['nodes'][$container], static::listModelsNodes(), 'Selectionner le model', $FieldValue);

      if ($model == 'logement_1000px') {
        $sup_container = 'options_nodes';
        /**
         * localisation
         */
        $name = 'localisation';
        $FieldValue = $model = (! empty($options['nodes'][$container][$sup_container][$name])) ? $options['nodes'][$container][$sup_container][$name] : '';
        $ThemeUtility->addSelectTree($name, $form['nodes'][$container][$sup_container], $listes_fields, 'Selectionner le champs pour la localisation', $FieldValue);
        /**
         * user
         */
        $name = 'user';
        $FieldValue = $model = (isset($options['nodes'][$container][$sup_container][$name])) ? $options['nodes'][$container][$sup_container][$name] : 0;
        $ThemeUtility->addCheckboxTree($name, $form['nodes'][$container][$sup_container], 'Selectionner le nom dutilisateur', $FieldValue);
        /**
         * user
         */
        $name = 'date_update';
        $FieldValue = $model = (isset($options['nodes'][$container][$sup_container][$name])) ? $options['nodes'][$container][$sup_container][$name] : 0;
        $ThemeUtility->addCheckboxTree($name, $form['nodes'][$container][$sup_container], 'Selectionner la date', $FieldValue);
        /**
         * user
         */
        $name = 'price';
        $FieldValue = $model = (! empty($options['nodes'][$container][$sup_container][$name])) ? $options['nodes'][$container][$sup_container][$name] : '';
        $ThemeUtility->addSelectTree($name, $form['nodes'][$container][$sup_container], $listes_fields, 'Selectionner le champs pour le prix', $FieldValue);
        /**
         * user
         */
        $name = 'price_suffix';
        $FieldValue = $model = (! empty($options['nodes'][$container][$sup_container][$name])) ? $options['nodes'][$container][$sup_container][$name] : '';
        $ThemeUtility->addSelectTree($name, $form['nodes'][$container][$sup_container], $listes_fields, 'Selectionner le champs pour le price_suffix', $FieldValue);
      } elseif ($model == 'header_static_image') {
        $sup_container = 'options_nodes';
        /**
         * Le champs selection du type de contenu.
         */
        $name = 'show_content_over';
        $FieldValue = (isset($options['nodes'][$container][$sup_container][$name])) ? $options['nodes'][$container][$sup_container][$name] : 1;
        $ThemeUtility->addCheckboxTree($name, $form['nodes'][$container][$sup_container], 'Remonte le conteu avec un box-shadow', $FieldValue);
      }
    }
  }

  public static function loadStaticHeader(&$form, $options, $callback, $wrapper, ThemeUtility $ThemeUtility, ManageNode $ManageNode)
  {
    $default_options = static::$default_options;
    $providers = [
      'custom' => 'Personnaliser',
      'node' => 'Contenu'
    ];
    $container = 'static-header';
    $form[$container] = [
      '#type' => 'details',
      '#title' => 'Static Header',
      "#open" => false,
      '#attributes' => [
        'id' => $wrapper
      ]
    ];
    /**
     * Display
     */
    $name = 'status';
    $FieldValue = (isset($options[$container][$name])) ? $options[$container][$name] : 0;
    $ThemeUtility->addCheckboxTree($name, $form[$container], 'Afficher ce bloc ', $FieldValue);
    /**
     * Select provider
     */
    $name = 'provider';
    $FieldValue = $provider = (! empty($options[$container][$name])) ? $options[$container][$name] : 'node';
    $ThemeUtility->addSelectTree($name, $form[$container], $providers, 'Selectionner le fournisseur', $FieldValue);
    $ThemeUtility->AddAjaxTree($name, $form[$container], $callback, $wrapper);
    if ($provider == 'node') {
      $bundle = $default_options['machine_name'];
      $Fields = $ManageNode->getFieldsNode($bundle);
      /**
       * Select sub_title
       */
      $name = 'sup_title';
      $FieldValue = (! empty($options[$container][$name])) ? $options[$container][$name] : '';
      $ThemeUtility->addSelectTree($name, $form[$container], $Fields, 'Titre au dessus', $FieldValue);
      /**
       * Select sub_title
       */
      $name = 'title';
      $FieldValue = (! empty($options[$container][$name])) ? $options[$container][$name] : '';
      $ThemeUtility->addSelectTree($name, $form[$container], $Fields, 'Titre au centre', $FieldValue);
      /**
       * Select sub_title
       */
      $name = 'sub_title';
      $FieldValue = (! empty($options[$container][$name])) ? $options[$container][$name] : '';
      $ThemeUtility->addSelectTree($name, $form[$container], $Fields, 'Titre en dessous', $FieldValue);
      /**
       * Le champs image
       */
      $name = 'img';
      $FieldValue = (! empty($options[$container][$name])) ? $options[$container][$name] : '';
      $ThemeUtility->addSelectTree($name, $form[$container], $Fields, 'Titre en dessous', $FieldValue);
    }
  }

  public static function loadHeaderBackground(&$form, $options, $callback, $wrapper, ThemeUtility $ThemeUtility, ManageNode $ManageNode)
  {
    $providers = [
      'custom' => 'Personnaliser',
      'node' => 'Contenu'
    ];
    // $ThemeUtility = new ThemeUtility();
    $container = 'headerbackground';
    $form[$container] = array(
      '#type' => 'details',
      '#title' => 'Header Background',
      "#open" => false,
      '#attributes' => [
        'id' => $wrapper
      ]
    );
    /**
     * Display
     */
    $name = 'status';
    $FieldValue = (isset($options[$container][$name])) ? $options[$container][$name] : 0;
    $ThemeUtility->addCheckboxTree($name, $form[$container], 'Afficher ce bloc ', $FieldValue);
    /**
     * breadcrumb
     */
    $name = 'breadcrumb';
    $FieldValue = (isset($options[$container][$name])) ? $options[$container][$name] : 0;
    $ThemeUtility->addCheckboxTree($name, $form[$container], 'Afficher la file d\'arianne ', $FieldValue);

    /**
     * Select provider
     */
    $ThemeUtility = new ThemeUtility();
    $name = 'region';
    $FieldValue = (! empty($options[$container][$name])) ? $options[$container][$name] : 'header';
    $ThemeUtility->addSelectTree($name, $form[$container], $ThemeUtility->get_regions(), 'Selectionner la region', $FieldValue);

    /**
     * Select provider
     */
    $name = 'provider';
    $FieldValue = $provider = (! empty($options[$container][$name])) ? $options[$container][$name] : 'custom';
    $ThemeUtility->addSelectTree($name, $form[$container], $providers, 'Selectionner le fournisseur', $FieldValue);
    $ThemeUtility->AddAjaxTree($name, $form[$container], $callback, $wrapper);

    if ($provider == 'custom') {
      /**
       * image.
       */
      $name = 'image';
      $FieldValue = $provider = (! empty($options[$container][$name])) ? $options[$container][$name] : '';
      $ThemeUtility->addImageTree($name, $form[$container], 'Image', $FieldValue);
      /**
       * content top.
       */
      $name = 'content_top';
      $FieldValue = (! empty($options[$container][$name])) ? $options[$container][$name] : '';
      $ThemeUtility->addTextareaSimpleTree($name, $form[$container], 'content top', $FieldValue);
      /**
       * content center.
       */
      $name = 'content_center';
      $FieldValue = (! empty($options[$container][$name])) ? $options[$container][$name] : '';
      $ThemeUtility->addTextareaSimpleTree($name, $form[$container], 'Content center', $FieldValue);
      /**
       * content bottom.
       */
      $name = 'content_bottom';
      $FieldValue = (! empty($options[$container][$name])) ? $options[$container][$name] : '';
      $ThemeUtility->addTextareaSimpleTree($name, $form[$container], 'Content bottom', $FieldValue);
    } elseif ($provider == 'node') {
      $sub_container = 'fields';
      /**
       * image.
       */
      $name = 'image';
      $FieldValue = $provider = (! empty($options[$container][$sub_container][$name])) ? $options[$container][$sub_container][$name] : '';
      $ThemeUtility->addSelectTree($name, $form[$container][$sub_container], static::$listes_fields, 'selectionner le champs Image', $FieldValue);

      $styles_images = PreprocessTemplate::loadAllStyleMedia();
      $name = 'image_style';
      $FieldValue = (isset($options[$container][$name])) ? $options[$container][$name] : 'large';
      $ThemeUtility->addSelectTree($name, $form[$container], $styles_images, "Selectionner le style d'image", $FieldValue);

      /**
       * content top.
       */
      $name = 'content_top';
      $FieldValue = (! empty($options[$container][$sub_container][$name])) ? $options[$container][$sub_container][$name] : '';
      $ThemeUtility->addSelectTree($name, $form[$container][$sub_container], static::$listes_fields, 'selectionner le content_top', $FieldValue);
      /**
       * content center.
       */
      $name = 'content_center';
      $FieldValue = (! empty($options[$container][$sub_container][$name])) ? $options[$container][$sub_container][$name] : '';
      $ThemeUtility->addSelectTree($name, $form[$container][$sub_container], static::$listes_fields, 'selectionner le content_center', $FieldValue);
      /**
       * content bottom.
       */
      $name = 'content_bottom';
      $FieldValue = (! empty($options[$container][$sub_container][$name])) ? $options[$container][$sub_container][$name] : '';
      $ThemeUtility->addSelectTree($name, $form[$container][$sub_container], static::$listes_fields, 'selectionner le content_bottom', $FieldValue);
    }
  }

  public function loadPageFile($display, $page)
  {
    if ($display['model'] == 'default') {
      $content_file = file_get_contents($this->BasePath . '/Templates/pages/default/Drupal.html.twig');
      LoaderDrupal::addData($page, $content_file, $display['model']);
      $content_file = file_get_contents($this->BasePath . '/Templates/pages/default/style.scss');
      LoaderDrupal::addData('page_node_scss', $content_file, $display['model']);
    } elseif ($display['model'] == 'ebook') {
      ;
    }
  }

  protected static function mode_display()
  {
    return [
      'full' => 'Full',
      'teaser' => 'Teaser',
      'default-node' => 'Default node'
    ];
  }

  protected function createfile($file_name, $directory, $ext = ".html.twig")
  {
    if (! \file_exists($directory))
      \Drupal::service('file_system')->mkdir($directory, 0775, true, null);
    $datas = LoaderDrupal::getSessionValue($file_name);
    // dump($datas);
    if (! empty($datas)) {
      $data = '';
      if ($ext == '.scss') {
        if (defined('KEY_LOAD_SCSS') && KEY_LOAD_SCSS == 'loarder2') {
          $data .= '@import "defaut/loader_model1.scss";';
        } else {
          $data .= '@import "defaut/models.scss"; ';
        }
        $data .= "\n";
      }
      foreach ($datas as $value) {
        $data .= $value;
      }
      LoaderDrupal::file_save($directory . '/' . $file_name . $ext, $data);
    }
    /**
     * After delete session
     */
    LoaderDrupal::DeleteSessionValue($file_name);
  }

  protected function deleteFile($file_name, $directory, $ext = ".html.twig")
  {
    if (\file_exists($directory . '/' . $file_name . $ext)) {
      LoaderDrupal::file_delete($directory . '/' . $file_name . $ext);
    }
  }

  /**
   * List model pour les nodes.
   *
   * @return string[]
   */
  public static function listModelsNodes()
  {
    return [
      'default' => 'default',
      'ebook' => 'Livres',
      'header_static_image' => 'header_static_image', // Ce nom doit etre mis à jour, car il ne correspond pas.
      'logement_1000px' => 'logement 1000px'
    ];
  }

  /**
   * List model pour les pages.
   *
   * @return string[]
   */
  public static function listModels()
  {
    return [
      'default' => 'default',
      'ebook' => 'Livres'
    ];
  }
}