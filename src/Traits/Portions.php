<?php
namespace Stephane888\HtmlBootstrap\Traits;

use Stephane888\HtmlBootstrap\LoaderDrupal;

trait Portions {

  /**
   *
   * @return string[][]
   */
  public function getdefault_rx_logos()
  {
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

  /**
   * Retourne le template.
   * Les differentes valeurs sont
   * - circle_animate
   * - ..
   *
   * @param array $param
   */
  public function template_rx_logos($rx_logos, $template)
  {
    $fileName = '';
    if ($template == 'circle_animate') {
      LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Utility/RxLogos/CircleAnimate/style.scss'));
      $fileName = \file_get_contents($this->BasePath . '/Utility/RxLogos/CircleAnimate/Drupal.html.twig');
    }
    return [
      '#type' => 'inline_template',
      '#template' => $fileName,
      '#context' => [
        'rx_logos' => $rx_logos
      ]
    ];
  }
}