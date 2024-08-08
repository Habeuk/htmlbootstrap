<?php
namespace Stephane888\HtmlBootstrap\Controller;

use Stephane888\HtmlBootstrap\LoaderDrupal;
use Stephane888\HtmlBootstrap\Traits\Portions;

class CallActions {
  use Portions;

  protected $BasePath = '';

  protected $themeObject = null;

  function __construct($path = null)
  {
    $this->BasePath = $path;
    $this->themeObject = \Drupal::theme()->getActiveTheme();
  }

  /**
   * Using default template 'inline_template'
   */
  public function loadFile($options)
  {
    /**
     * Get title
     */
    if (isset($options['title'])) {
      $title = $options['title'];
    } else {
      $title = 'Risus Ultricies Magna';
    }

    /**
     * Get title
     */
    if (isset($options['text'])) {
      $text = $options['text'];
    } else {
      $text = $this->template_htmltag('Nullam id dolor id nibh ultricies vehicula ut id elit. Aenean lacinia bibendum nulla.');
    }

    /**
     * Get link
     */
    if (isset($options['link'])) {
      $link = $options['link'];
    } else {
      $link = '#';
    }

    /**
     * Get title
     */
    if (isset($options['text_link'])) {
      $text_link = $options['text_link'];
    } else {
      $text_link = 'Learn More';
    }

    /**
     * Get type
     */
    if (isset($options['type'])) {
      if ($options['type'] == 'TextActionModele1') {
        $fileName = \file_get_contents($this->BasePath . '/Sections/CallActions/TextActionModele1/Drupal.html.twig');
        LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/CallActions/TextActionModele1/style.scss'), 'TextActionModele1');
      }
    } else {
      $fileName = \file_get_contents($this->BasePath . '/Sections/CallActions/TextActionModele1/Drupal.html.twig');
      LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Sections/CallActions/TextActionModele1/style.scss'), 'TextActionModele1');
    }

    return [
      '#type' => 'inline_template',
      '#template' => $fileName,
      '#context' => [
        'text' => $text,
        'title' => $title,
        'text_link' => $text_link,
        'link' => $link
      ]
    ];
  }
}
