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
   * Retourne le template ou l'affichage pour les liens des rx.
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
      LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Utility/RxLogos/CircleAnimate/style.scss'), 'template_rx_logos');
      $fileName = \file_get_contents($this->BasePath . '/Utility/RxLogos/CircleAnimate/Drupal.html.twig');
    } elseif ($template == 'flat') {
      LoaderDrupal::addStyle(\file_get_contents($this->BasePath . '/Utility/RxLogos/Flat/style.scss'), 'template_rx_logos');
      $fileName = \file_get_contents($this->BasePath . '/Utility/RxLogos/Flat/Drupal.html.twig');
    }
    return [
      '#type' => 'inline_template',
      '#template' => $fileName,
      '#context' => [
        'rx_logos' => $rx_logos
      ]
    ];
  }

  /**
   * Template to center content.
   */
  public function templateCenterVertHori($datas, $classe = null)
  {
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
  public function template_inline_template($datas, $classe = null)
  {
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
  public function template_img($img_url, $alt = '', $classe = '')
  {
    return [
      '#type' => 'inline_template',
      '#template' => '<img src="{{img_url}}" class="img-fluid {{classe}}" alt="{{alt}}" />',
      '#context' => [
        'img_url' => drupal_get_path('theme', 'theme_builder') . $img_url,
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
  public function getFauxTexte()
  {
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

  /**
   * Ajoute un conteneur html, par defaut un p.
   * Utilise la methode 'html_tag'.
   *
   * @param string $string
   * @param string $tag
   * @return string[]
   */
  public function template_htmltag($string, $tag = 'p')
  {
    return [
      '#type' => 'html_tag',
      '#tag' => $tag,
      '#value' => $string
    ];
  }
}