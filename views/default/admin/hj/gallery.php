<?php

if (!elgg_is_active_plugin('hypeFormBuilder')) {
    echo 'Settings in this section will be available once you install hypeFormBuilder (or other hypeJunction plugins that extend hypeGallery)';
    return true;
}

echo elgg_view('admin/hj/sections/extend', array('plugin' => 'gallery'));
