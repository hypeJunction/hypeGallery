<?php
/**
 * View Gallery Widget Contents
 *
 * @package hypeJunction
 * @subpackage hypeGallery
 * @category Gallery
 * @category Widgets
 * @category Views
 *
 * @uses hjGallery
 *
 * @return string HTML
 */
?><?php
$widget = elgg_extract('entity', $vars, false);

if (!$widget) {
    return true;
}

$segment = $widget->getContainerEntity();
$section = $vars['entity']->section;

if (!$limit = $vars['entity']->num_display)
    $limit = 0;

$content = $segment->getSectionContent($section, $widget, $limit);

$content_view = elgg_view_entity_list($content, array('full_view' => true, 'list_class' => 'hj-view-list', 'item_class' => 'hj-view-entity elgg-state-draggable', 'view_params' => 'gallery', 'widget' => $widget));

$form = hj_framework_get_data_pattern('object', $section);

$params = array(
    'st' => $section,
    'f' => $form->guid,
    'c' => $segment->guid,
    'w' => $widget->guid,
    'context' => 'gallery',
    'hd' => 'hjgallery',
    'vp' => 'gallery'
);

$params = hj_framework_extract_params_from_params($params);

$footer_menu = elgg_view_menu('hjsectionfoot', array(
    'handler' => 'hjsection',
    'sort_by' => 'priority',
    'class' => 'elgg-menu-hz hj-menu-hz',
    'params' => $params
        ));

$body = <<<HTML
    $content_view
HTML;

echo $body;
echo $footer_menu;