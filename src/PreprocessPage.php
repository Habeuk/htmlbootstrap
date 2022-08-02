<?php

namespace Stephane888\HtmlBootstrap;

use Stephane888\HtmlBootstrap\Traits\DisplaySection;
use Symfony\Component\HttpFoundation\Session\Session;
use ScssPhp\ScssPhp\Compiler;
use Drupal\Core\Template\Attribute;
use Stephane888\HtmlBootstrap\ThemeUtility;
use PhpParser\Node\Stmt\Foreach_;
use Stephane888\HtmlBootstrap\LoaderDrupal;

class PreprocessPage {
  protected $is_front = false;
  protected static $theme_name;
  
  use DisplaySection;
  
  public function createTemplates($theme_name, $displays = null, $force = false) {
    if ((isset($_GET['template']) && $_GET['template'] == 'build') || $force) {
      if (!$displays) {
        $displays = theme_get_setting($theme_name . '_pagenodesdisplay', $theme_name);
      }
      $url_theme = \drupal_get_path('theme', $theme_name);
      $LoaderDrupal = new LoaderDrupal();
      $LoaderDrupal->createFiles($displays, $url_theme);
    }
  }
  
  public function setThemeName($theme_name) {
    static::$theme_name = $theme_name;
  }
  
  public function loadSection($theme_name, &$variables) {
    $this->setThemeName($theme_name);
    if ($variables['is_front'])
      $this->is_front = $variables['is_front'];
    $LoaderDrupal = new LoaderDrupal();
    
    /**
     * Load content from layout manager.
     */
    if (theme_get_setting($theme_name . '_layout_manager_status', $theme_name)) {
      static::getLayoutManager($LoaderDrupal, $variables, 'layout_manager');
    }
    
    /**
     * Get style for pages
     */
    if (theme_get_setting($theme_name . '_stylepage_status', $theme_name)) {
      static::getStylePage($LoaderDrupal, $variables);
    }
    
    /**
     * get top headers
     * Not use for now.
     */
    if (theme_get_setting($theme_name . '_topheader_status', $theme_name)) {
      static::getTopHeaders($LoaderDrupal, $variables);
    }
    /**
     * get headers
     */
    if (theme_get_setting($theme_name . '_header_status', $theme_name)) {
      static::getHeaders($LoaderDrupal, $variables);
    }
    /**
     * get sliders
     */
    if (theme_get_setting($theme_name . '_slide_status', $theme_name) && $LoaderDrupal->filterByRouteName(theme_get_setting($theme_name . '_slide_routes', $theme_name))) {
      static::getSliders($LoaderDrupal, $variables);
    }
    /**
     * get card
     */
    if (theme_get_setting($theme_name . '_cards_status', $theme_name)) {
      static::getCards($LoaderDrupal, $variables);
    }
    
    /**
     * get PriceLists
     */
    if (theme_get_setting($theme_name . '_pricelists_status', $theme_name)) {
      static::getPriceLists($LoaderDrupal, $variables);
    }
    
    /**
     * Get CallActions
     */
    if (theme_get_setting($theme_name . '_callactions_status', $theme_name)) {
      static::getCallActions($LoaderDrupal, $variables);
    }
    
    /**
     * Get carouselcards
     */
    if (theme_get_setting($theme_name . '_carouselcards_status', $theme_name)) {
      static::getCarouselCards($LoaderDrupal, $variables);
    }
    
    /**
     * Get Comments
     */
    if (theme_get_setting($theme_name . '_comments_status', $theme_name)) {
      static::getComments($LoaderDrupal, $variables);
    }
    
    /**
     */
    if (theme_get_setting($theme_name . '_imagetextrightleft_status', $theme_name)) {
      static::getImageTextRightLeft($LoaderDrupal, $variables);
    }
    
    /**
     * Get footers
     */
    if (theme_get_setting($theme_name . '_footers_status', $theme_name)) {
      static::getFooters($LoaderDrupal, $variables);
    }
    
    /**
     * load plugins page (node)
     */
    $route_name = \Drupal::routeMatch()->getRouteName();
    $node = \Drupal::routeMatch()->getParameter('node');
    $displays = theme_get_setting($theme_name . '_pagenodesdisplay', $theme_name);
    $Attribute = new Attribute();
    // dump($variables);
    $defaultClass = $this->getDisplaysClass($displays);
    if (!empty($variables['page']['content']['displays_class'])) {
      if (empty($defaultClass))
        $defaultClass = [];
      $defaultClass += $variables['page']['content']['displays_class'];
      unset($variables['page']['content']['displays_class']);
    }
    // $node =new \Drupal\node\Entity\Node();
    // dump($displays);
    if ($node) {
      $defaultClassEntity = $this->getDisplaysClass($displays, $node->bundle());
      $Attribute->addClass('page-node-custom');
      if ($defaultClassEntity) {
        $Attribute->addClass($defaultClassEntity);
      }
      else {
        // dump($defaultClass);
        $Attribute->addClass($defaultClass);
      }
      
      $variables['page']['content']['attributes'] = $Attribute;
      $wrapper_attribute = new Attribute();
      $wrapper_attribute->addClass('region-content');
      $variables['page']['content']['wrapper_attribute'] = $wrapper_attribute;
      
      // loadPagePlugins
      $LoaderDrupal->loadPagePlugins($variables, $displays, $node, $theme_name);
      // dump($node->bundle());
    }
    elseif ('entity.taxonomy_term.canonical' == $route_name) {
      if (!empty($variables['page']['content']['attributes'])) {
        $variables['page']['content']['attributes']->addClass('page-term-custom');
      }
      else {
        $Attribute->addClass('page-term-custom');
        $Attribute->addClass($defaultClass);
        $variables['page']['content']['attributes'] = $Attribute;
      }
    }
    elseif (!$this->is_front && \strstr($route_name, 'user.')) {
      /**
       * En attendant de trouver une meilleur approche pour les pages de
       * connextions, on ajoute une classe.
       */
      $_Attribute = $Attribute->addClass([
        'container',
        'page-user-custom',
        'my-5'
      ]);
      $variables['page']['content']['attributes'] = $_Attribute;
    } // doit etre configurable.
    elseif (!$this->is_front && $route_name == 'entity.webform.canonical') {
      /**
       * Pour les pages webfomrs.
       * [ Affichage statiques à modifier plus tard ].
       */
      $_Attribute = $Attribute->addClass([
        'container',
        'container-md',
        'my-5'
      ]);
      $variables['page']['content']['entete'] = [
        '#type' => 'html_tag',
        '#tag' => 'h1',
        '#value' => $variables['page']['#title']
      ];
      $variables['page']['content']['entete']['#weight'] = -100;
      $variables['page']['content']['attributes'] = $_Attribute;
      // dump($variables);
    }
    elseif ('view.frontpage.page_1' != $route_name) {
      $Attribute->addClass('page-orther-custom');
      $Attribute->addClass($defaultClass);
      $variables['page']['content']['attributes'] = $Attribute;
    }
  }
  
  public function getDisplaysClass($displays, $content_type = null) {
    if (!$content_type) {
      if (!empty($displays['all-content-type']['status'])) {
        return (!empty($displays['all-content-type']['classes'])) ? $displays['all-content-type']['classes'] : '';
      }
    }
    elseif ($content_type) {
      if (!empty($displays[$content_type]['status'])) {
        return (!empty($displays[$content_type]['classes'])) ? $displays[$content_type]['classes'] : '';
      }
    }
    return false;
  }
  
  /**
   * permet de :
   * - Retirer le message par defaut quand il nya pas de contenu + plus le
   * contenu par defaut;
   * - retier les onglets d'editions pour les utilisateurs qui ne sont pas
   * admins.
   *
   * @param array $variables
   * @param string $theme_name
   */
  public function ApplyActions(&$variables, $theme_name = 'themeconsultant') {
    /**
     * Remove system page in front.
     */
    if (isset($variables['is_front']) && $variables['is_front']) {
      unset($variables['page']['content']['system_main']);
    }
    /**
     * remove default message in fornt
     */
    if ($variables['is_front']) {
      // dump($variables['page']['content']);
      unset($variables['page']['content'][$theme_name . '_page_title']);
      unset($variables['page']['content'][$theme_name . '_content']);
    }
    
    /**
     * Remove edit for all user except admibistrator
     */
    if (!\Drupal\user\Entity\User::load(\Drupal::currentUser()->id())->hasRole('administrator')) {
      unset($variables['page']['content'][$theme_name . '_local_tasks']);
    }
  }
  
  public function AddLibrary(&$variables, $theme_name = 'themeconsultant') {
    /**
     * Ajout les fichiers de style et Scripts.
     *
     * @var $node
     */
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node) {
      $variables['page']['content']['#attached']['library'][] = $theme_name . '/page-node';
    }
  }
  
  public static function LoadTemplates($theme_name) {
    return [
      // views-view-field-cutom
      'views_view_field_cutom' => [
        'variables' => [
          'field' => []
        ]
      ],
      'image_url' => [
        'variables' => [
          'image_style' => NULL,
          'file' => null,
          'width' => null,
          'height' => null
        ],
        'path' => drupal_get_path('theme', $theme_name) . '/templates/layouts'
        // 'path' => 'templates/layouts'
      ],
      'header_bg' => [
        'variables' => [
          'image' => NULL,
          'content_top' => NULL,
          'content_center' => NULL,
          'content_bottom' => NULL,
          'attributes' => [],
          'orther_vars' => []
        ],
        'path' => drupal_get_path('theme', $theme_name) . '/templates/Suggestions/sections/PageNodesDisplay/headerBg'
      ],
      'item_list_custom' => [
        'variables' => [
          'image' => NULL,
          'content_top' => NULL,
          'content_center' => NULL,
          'content_bottom' => NULL
        ]
      ],
      'static_image' => [
        'variables' => [
          'sub_title' => NULL,
          'title' => NULL,
          'sup_title' => NULL,
          'img' => NULL,
          'attributes' => [],
          'orther_vars' => [],
          'attributes_mobile' => []
        ],
        'path' => drupal_get_path('theme', $theme_name) . '/templates/Suggestions/sections/imgLeftRight/StaticImage'
      ],
      'zone_custom_template' => [
        'variables' => [
          'sup_title' => NULL,
          'title' => NULL,
          'text' => NULL,
          'attributes' => [],
          'orther_vars' => []
        ],
        'path' => drupal_get_path('theme', $theme_name) . '/templates/Suggestions/sections/imgLeftRight/ZoneCustomTemplate'
      ],
      'footer_menu_rx' => [
        'variables' => [
          'footer_menu' => NULL,
          'rx_logo' => NULL,
          'end_left' => NULL,
          'end_right' => NULL,
          'end_right_link' => NULL,
          'attributes' => [],
          'orther_vars' => []
        ],
        'path' => drupal_get_path('theme', $theme_name) . '/templates/Suggestions/sections/Footers/FooterMenuRx'
      ],
      'top_header_default' => [
        'variables' => [
          'items' => [],
          'attributes' => [],
          'orther_vars' => []
        ],
        'path' => drupal_get_path('theme', $theme_name) . '/templates/Suggestions/sections/TopHeaders/Default'
      ],
      'dropdown_menu' => [
        'variables' => [
          'data' => NULL,
          'menu' => [],
          'attributes' => [],
          'orther_vars' => []
        ],
        'path' => drupal_get_path('theme', $theme_name) . '/templates/Suggestions/Bootstrap'
      ],
      'bloc_contact' => [
        'variables' => [
          'title_header' => NULL,
          'desc_header' => null,
          'forms' => NULL,
          'cards' => [],
          'attributes' => [],
          'attribute_address' => [],
          'attribute_form' => [],
          'orther_vars' => []
        ],
        'path' => drupal_get_path('theme', $theme_name) . '/templates/Suggestions/sections/imgLeftRight/blocContact'
      ]
    ];
  }
  
  public function Preprocess_field__image(&$variables, $theme_name) {
    // dump($variables);
    $variables['#attached']['library'][] = $theme_name . '/owlcarousel';
  }
  
  /**
   * load scss csss
   */
  public function _load_scss($theme_name) {
    if (isset($_GET['build']) && $_GET['build'] == 'scss') {
      /**
       * il ya un soucis à ce niveau, si on eneleve le chemin cela ne fonctionne
       * plus.
       */
      $parser = new Compiler();
      // build bootstrap end default style theme
      $theme_root = DRUPAL_ROOT . '/' . \drupal_get_path('theme', $theme_name);
      
      /**
       * Formattes les fichiers scss du theme enfants
       */
      $scss_config_bootstrap = $this->childrenThemeFormarteScss($parser, $theme_root);
      
      /**
       *
       * @var string $result
       */
      $result = $parser->compile($scss_config_bootstrap . '@import "' . $theme_root . '/scss/bootstrap-overlay.scss";');
      $filename = $theme_root . '/css/bootstrap-overlay.css';
      $monfichier = fopen($filename, 'w+');
      fputs($monfichier, $result);
      fclose($monfichier);
      
      // build custom style
      if (LOAD_SCSS_BY_SESSION && $this->is_front) {
        // dump('_load_scss');
        $Session = new Session();
        $styles = $Session->get('theme-style', []);
        if (!empty($styles)) {
          $style = '';
          if (isset($styles['init'])) {
            $style .= $styles['init'];
            $style .= "\n";
            unset($styles['init']);
          }
          foreach ($styles as $key => $sty) {
            if (strstr($key, 'init_-_header')) {
              $style .= $styles[$key];
              $style .= "\n";
              unset($styles[$key]);
            }
          }
          $style .= implode("\n", $styles);
          // dump($styles);
          // $Session->remove('theme-style');
          // kint($style);
          /**
           * on enregistre le fichier generere en scss.
           */
          $filename = $theme_root . '/scss/style-auto.scss';
          $monfichier = fopen($filename, 'w+');
          fputs($monfichier, $style);
          fclose($monfichier);
          /**
           * compilation du fichier.
           */
          $result = $parser->compile($scss_config_bootstrap . '@import "' . $filename . '";');
          /**
           * on sauvegarde le fichier css generé.
           */
          $filename = $theme_root . '/css/style-auto.css';
          $monfichier = fopen($filename, 'w+');
          fputs($monfichier, $result);
          fclose($monfichier);
          /**
           * Get script
           */
          $scripts = $Session->get('theme-script', []);
          $script = implode("\n", $scripts);
          /**
           * On enregistre le fichier generere en js.
           */
          $filename = $theme_root . '/js/script-auto.js';
          $monfichier = fopen($filename, 'w+');
          fputs($monfichier, $script);
          fclose($monfichier);
        }
      }
      
      // build custom style
      $result = $parser->compile($scss_config_bootstrap . '@import "' . $theme_root . '/scss/style.scss";');
      $filename = $theme_root . '/css/style.css';
      $monfichier = fopen($filename, 'w+');
      fputs($monfichier, $result);
      fclose($monfichier);
      
      // build custom style
      // $result = $parser->compile($scss_config_bootstrap . '@import "' .
      // $theme_root . '/scss/accueill.scss";');
      // $filename = $theme_root . '/css/accueill.css';
      // $monfichier = fopen($filename, 'w+');
      // fputs($monfichier, $result);
      // fclose($monfichier);
      // build custom style
      // $result = $parser->compile($scss_config_bootstrap . '@import "' .
      // $theme_root . '/scss/sign-in.scss";');
      // $filename = $theme_root . '/css/sign-in.css';
      // $monfichier = fopen($filename, 'w+');
      // fputs($monfichier, $result);
      // fclose($monfichier);
      // build custom style
      $result = $parser->compile($scss_config_bootstrap . '@import "' . $theme_root . '/scss/style-admin.scss";');
      $filename = $theme_root . '/css/style-admin.css';
      $monfichier = fopen($filename, 'w+');
      fputs($monfichier, $result);
      fclose($monfichier);
      
      // build custom style
      // $result = $parser->compile($scss_config_bootstrap . '@import "' .
      // $theme_root . '/scss/ckeditor_custom.scss";');
      // $filename = $theme_root . '/css/ckeditor_custom.css';
      // $monfichier = fopen($filename, 'w+');
      // fputs($monfichier, $result);
      // fclose($monfichier);
      // build custom member-ship
      // $result = $parser->compile($scss_config_bootstrap . '@import "' .
      // $theme_root . '/scss/member-ship.scss";');
      // $filename = $theme_root . '/css/member-ship.css';
      // $monfichier = fopen($filename, 'w+');
      // fputs($monfichier, $result);
      // fclose($monfichier);
      // // build custom member-ship
      // $result = $parser->compile($scss_config_bootstrap . '@import "' .
      // $theme_root . '/scss/page_node_scss.scss";');
      // $filename = $theme_root . '/css/page-node.css';
      // $monfichier = fopen($filename, 'w+');
      // fputs($monfichier, $result);
      // fclose($monfichier);
      // build custom member-ship
      // $result = $parser->compile($scss_config_bootstrap . '@import "' .
      // $theme_root . '/scss/node_scss.scss";');
      // $filename = $theme_root . '/css/node.css';
      // $monfichier = fopen($filename, 'w+');
      // fputs($monfichier, $result);
      // fclose($monfichier);
      // build custom maintenance-page
      $result = $parser->compile($scss_config_bootstrap . '@import "' . $theme_root . '/scss/maintenance-page.scss";');
      $filename = $theme_root . '/css/maintenance-page.css';
      $monfichier = fopen($filename, 'w+');
      fputs($monfichier, $result);
      fclose($monfichier);
      
      // build custom maintenance-page
      // $result = $parser->compile($scss_config_bootstrap . '@import "' .
      // $theme_root . '/scss/page-content-over.scss";');
      // $filename = $theme_root . '/css/page-content-over.css';
      // $monfichier = fopen($filename, 'w+');
      // fputs($monfichier, $result);
      // fclose($monfichier);
    
    /**
     * delete session
     */
      // $this->_delete_scss();
    }
  }
  
  /**
   * Permet de formater les fichiers css present dans le theme enfants.
   * retourne le fichier de configuration, pour pouvoir surcharcher les valeurs
   * de
   * bootstrap.
   */
  protected function childrenThemeFormarteScss(Compiler $parser, $theme_root_parent) {
    $ThemeUtility = new ThemeUtility();
    $themes = $ThemeUtility->themeObject->getBaseThemeExtensions();
    $scss_config_bootstrap = '';
    if (!empty($themes)) {
      
      if (\array_key_first($themes) == "wb_universe") {
        
        $theme_root = DRUPAL_ROOT . '/' . \drupal_get_path('theme', $ThemeUtility->themeName);
        // dump($theme_root_parent);
        $theme_scss = $theme_root . '/scss/autos';
        if (\file_exists($theme_root . '/scss/_variables_custom.scss')) {
          $scss_config_bootstrap = '@import "' . $theme_root . '/scss/_variables_custom.scss"; ';
        }
        // Creer le fichier style-auto.css à partir du contenu du dossier
        // /scss/autos.
        if (\file_exists($theme_scss)) {
          
          // $style = file_get_contents($theme_root_parent .
          // '/scss/style.scss');
          $style = $scss_config_bootstrap . '@import "' . $theme_root_parent . '/scss/loader_model_module2.scss"; ';
          //
          $file_system = \Drupal::service('file_system');
          $list_scss = $file_system->scanDirectory($theme_scss, '/.scss/');
          // dump($list_scss);
          ksort($list_scss);
          $import_scss = $style;
          foreach ($list_scss as $key => $scs) {
            $import_scss .= " \n/* Fichier : $key */ \n\n ";
            $import_scss .= '@import "' . $key . '"; ';
          }
          // dump($import_scss);
          // on construit la scss.
          $result = $parser->compile($import_scss);
          $filename = $theme_root . '/css/style-auto.css';
          $monfichier = fopen($filename, 'w+');
          fputs($monfichier, $result);
          fclose($monfichier);
        }
      }
    }
    return $scss_config_bootstrap;
  }
  
  protected function _delete_scss() {
    dump('delete');
    $Session = new Session();
    if ($Session->has('theme-style')) {
      $Session->remove('theme-style');
    }
    if ($Session->has('theme-script')) {
      $Session->remove('theme-script');
    }
  }
  
}
