<?php
namespace Stephane888\HtmlBootstrap\Traits;

trait Examples {

  /**
   * Test du template 'inline_template'
   */
  public function Test_InlineTemplate()
  {
    $name = 'Stephane kouwa test';
    return [
      '#type' => 'inline_template',
      '#template' => "{% trans %} Hello {% endtrans %} <strong>{{name}}</strong>; <div>{% trans %} Save {% endtrans %}</div> ", // les parties qui doivent etre traduite sont entre {% trans %} et les valeurs dynamique dans {{name}} ( le contexte permet de tranferer ces variables )
      '#context' => [ // contient les variables qui doivents etre transmise au templates.
        'name' => $name
      ]
    ];
  }

  /**
   * Test du template 'link'
   */
  public function Test_Link()
  {
    return [
      '#title' => t('Examples'),
      '#type' => 'link',
      '#url' => \Drupal\Core\Url::fromRoute('node.add_page')
    ];
  }

  /**
   * Test du template 'html_tag'
   */
  public function Test_HtmlTag()
  {
    return [
      '#type' => 'html_tag',
      '#tag' => 'p',
      '#value' => t('Hello World')
    ];
  }
}