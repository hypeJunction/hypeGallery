<?php

namespace hypeJunction\Gallery;

$page_owner = \elgg_get_page_owner_entity();
if (!$page_owner instanceof \ElggUser || !$page_owner->canEdit()) {
    return;
}
$groups = \elgg_get_entities(array('types' => 'group', 'relationship' => 'member', 'relationship_guid' => $page_owner->guid, 'limit' => 0));
if (!$groups) {
    echo '<p>' . \elgg_echo('gallery:nogroups') . '</p>';
    return true;
}
foreach ($groups as $group) {
    $group_guids[] = $group->guid;
}
$display = get_input('display', 'albums');
echo '<div id="gallery-dashboard-groups">';
switch ($display) {
    default:
    case 'albums':
        echo \elgg_list_entities(array('types' => 'object', 'subtypes' => array(hjAlbum::SUBTYPE), 'container_guids' => $group_guids, 'full_view' => false, 'list_type' => get_input('list_type', 'gallery'), 'list_type_toggle' => true, 'gallery_class' => 'gallery-photostream', 'pagination' => true, 'limit' => get_input('limit', 20), 'offset' => get_input('offset-albums', 0), 'offset_key' => 'offset-albums'));
        break;
    case 'photostream':
        echo \elgg_list_entities(array('types' => 'object', 'subtypes' => array(hjAlbumImage::SUBTYPE), 'owner_guids' => $page_owner->guid, 'wheres' => [
            function (\Elgg\Database\QueryBuilder $qb, $main_alias) use ($group_guids) {
                $album_alias = $qb->joinEntitiesTable($main_alias, 'container_guid', 'inner', 'albcont');
                return $qb->compare("{$album_alias}.container_guid", 'IN', $group_guids, ELGG_VALUE_INTEGER);
            },
        ], 'list_type' => get_input('list_type', 'gallery'), 'list_type_toggle' => true, 'gallery_class' => 'gallery-photostream', 'full_view' => false, 'pagination' => true, 'limit' => get_input('limit', 20), 'offset' => get_input('offset-photostream', 0), 'offset_key' => 'offset-photostream'));
        break;
}
echo '</div>';