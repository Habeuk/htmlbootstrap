<?php

namespace Stephane888\HtmlBootstrap;

/**
 *
 * @author stephane
 * @deprecated delete in 4x wb_universe.
 */
class PreprocessNode {
  
  public function ApplySettingPlugins(&$variables, $theme_name) {
    $displays = theme_get_setting($theme_name . '_pagenodesdisplay', $theme_name);
    // dump($displays);
    $variables["node"];
    $content = $variables["content"];
    $machine_name = $variables["node"]->bundle();
    if (isset($displays[$machine_name])) {
      if (isset($displays[$machine_name]['addedplugins'])) {
        foreach ($displays[$machine_name]['addedplugins'] as $key => $display) {
          if ($key == 'headerbackground' && $display['status']) {
            if ($display['provider'] == 'custom') {
              $variables['attributes']['class'][] = 'header-background';
            }
          }
        }
      }
      if (isset($displays[$machine_name]['nodes'])) {
        foreach ($displays[$machine_name]['nodes'] as $key => $value) {
          if (!empty($value['options_nodes'])) {
            $wbu_content = [];
            foreach ($value['options_nodes'] as $key_field => $field_name) {
              if (\strstr($field_name, 'field_') && isset($content[$field_name])) {
                $wbu_content[$key_field] = $content[$field_name];
                unset($variables["content"][$field_name]);
              }
              else {
                $wbu_content[$key_field] = $field_name;
              }
            }
            $variables['wbu_content'] = $wbu_content;
          }
        }
      }
    }
    /**
     * Ajoute un numero.
     * Custom function pour le site terremploi.kksa
     */
    if ($machine_name == 'annonce') {
      $nid_annonce = $variables["node"]->id();
      $nid_annonce = date('Y') . '-' . str_pad($nid_annonce, 3, '0', STR_PAD_LEFT);
      if (empty($variables['wbu_content']['id_article_html'])) {
        $variables['wbu_content']['id_article'] = $nid_annonce;
        $variables['wbu_content']['id_article_html'] = [
          '#markup' => '<div class="pt-2"> Annonce n° ' . $nid_annonce . '</div>'
        ];
      }
      if (empty($variables['wbu_content']['content_end'])) {
        $variables['wbu_content']['content_end'] = [
          '#markup' => '<div class="pt-2"><a href="/form/candidater-a-cette-annonce?annone=' . $nid_annonce . '" class="btn btn-outline-info js-form-submit ">Candidater à cette annonce</a></div>'
        ];
      }
      // dump($variables['wbu_content']);
    }
  }
  
}