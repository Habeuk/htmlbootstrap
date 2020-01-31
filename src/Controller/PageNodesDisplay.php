<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\Traits\Portions;
use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\ThemeUtility;

class PageNodesDisplay {
  use Portions;

  protected $mode_display = [];

  protected $BasePath = '';

  function __construct($path = null)
  {
    $this->BasePath = $path;
  }

  public function genereFiles($displays, $dir)
  {
    foreach ($displays as $key => $display) {
      if ($display['status'] && $display['model']) {
        if ($key == 'all-content-type') {
          $page = 'page--node';
          $this->loadPageFile($display, $page);
          $directory = DRUPAL_ROOT . '/' . $dir . '/templates/generates/pages';
          // dump($directory);
          $this->createfile($page, $directory);
        } else {
          $page = 'page--' . $key . '-node';
          $this->loadPageFile($display, $page);
          $directory = DRUPAL_ROOT . '/' . $dir . '/templates/generates/pages';
          $this->createfile($page, $directory);
        }
      } elseif (! $display['status']) {
        /**
         * Delete files.
         */
        if ($key == 'all-content-type') {
          $page = 'page--node';
          $directory = DRUPAL_ROOT . '/' . $dir . '/templates/generates/pages';
          $this->deleteFile($page, $directory);
        } else {
          $page = 'page--' . $key . '-node';
          $directory = DRUPAL_ROOT . '/' . $dir . '/templates/generates/pages';
          $this->deleteFile($page, $directory);
        }
        /**
         * Delete file nodes.
         */
        foreach ($display['nodes'] as $key => $value) {
          if (! $value['status']) {
            $directory = DRUPAL_ROOT . '/' . $dir . '/templates/nodes';
            $this->deleteFile($key, $directory);
          }
        }
      }
      $this->genereNodesFiles($display['nodes'], $dir);
    }
    /**
     * Build scss for page.
     */
    $directory = DRUPAL_ROOT . '/' . $dir . '/scss/generates';
    $this->createfile('page_node_scss', $directory, '.scss');

    /**
     * Build scss for page.
     */
    $directory = DRUPAL_ROOT . '/' . $dir . '/scss/generates';
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
    }
  }

  public static function loadFields($model, &$form, $options)
  {
    // dump($options);
    $ThemeUtility = new ThemeUtility();
    $ManageNode = new ManageNode();
    $content_types = [
      'all-content-type' => 'model de contenu par default'
    ];
    $content_types += $ManageNode->getContentType();
    foreach ($content_types as $machine_name => $content_type) {
      /**
       * Add section
       */
      $form[$machine_name] = array(
        '#type' => 'details',
        '#title' => $content_type,
        "#tree" => true
      );
      /**
       * Le champs selection du type de contenu
       */
      $name = 'model';
      $FieldValue = (! empty($options[$machine_name][$name])) ? $options[$machine_name][$name] : '';
      $ThemeUtility->addSelectTree($name, $form[$machine_name], static::listModels(), 'Selectionner le modele', $FieldValue);

      /**
       * Le champs selection du type de contenu
       */
      $name = 'status';
      $FieldValue = (! empty($options[$machine_name][$name])) ? $options[$machine_name][$name] : 0;
      $ThemeUtility->addCheckboxTree($name, $form[$machine_name], 'Utiliser un theme personnalisé', $FieldValue);
      static::loadNodeTemplate($machine_name, $form[$machine_name], $options[$machine_name]);
    }
  }

  protected static function loadNodeTemplate($machine_name, &$form, $options)
  {
    $ThemeUtility = new ThemeUtility();
    if (! isset($options['nodes'])) {
      $options['nodes'] = [];
    }
    if ($machine_name == 'all-content-type') {
      $machine_name = '';
    } elseif ($machine_name != '') {
      $machine_name = '--' . $machine_name;
    }
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
        "#tree" => true
      );
      /**
       * Le champs selection du type de contenu
       */
      $name = 'status';
      $FieldValue = (! empty($options['nodes'][$container][$name])) ? $options['nodes'][$container][$name] : 0;
      $ThemeUtility->addCheckboxTree($name, $form['nodes'][$container], 'Utiliser un theme personnalisé', $FieldValue);
      /**
       * Le champs selection du type de contenu
       */
      $name = 'model';
      $FieldValue = (! empty($options['nodes'][$container][$name])) ? $options['nodes'][$container][$name] : '';
      $ThemeUtility->addSelectTree($name, $form['nodes'][$container], static::listModelsNodes(), 'Selectionner le modele', $FieldValue);
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
    if (! empty($datas)) {
      $data = '';
      foreach ($datas as $value) {
        $data .= $value;
      }
      LoaderDrupal::file_save($directory . '/' . $file_name . $ext, $data);
    }
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
      'ebook' => 'Livres'
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