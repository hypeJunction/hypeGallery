<?php

/**
 * Design hypeGallery items using hjForm and hjField classes
 */
function hj_gallery_setup() {
    if (elgg_is_logged_in()) {
        hj_gallery_setup_album_form();
        hj_gallery_setup_album_image_form();
        elgg_set_plugin_setting('hj:gallery:setup', true);
        return true;
    }
    return false;
}

function hj_gallery_setup_album_form() {
    $form = new hjForm();
    $form->title = 'hypeGallery:album';
    $form->label = 'Add New Album';
    $form->description = '';
    $form->subject_entity_subtype = 'hjalbum';
    $form->notify_admins = false;
    $form->add_to_river = true;
    $form->comments_on = true;
    $form->ajaxify = true;

    if ($form->save()) {
        $form->addField(array(
            'title' => 'Album Name',
            'name' => 'title',
            'mandatory' => true
        ));

        $form->addField(array(
            'title' => 'Album Description',
            'name' => 'description',
            'input_type' => 'longtext',
        ));

        $form->addField(array(
            'title' => 'Where',
            'name' => 'location',
            'input_type' => 'location',
        ));

        $form->addField(array(
            'title' => 'When',
            'input_type' => 'date',
            'name' => 'date',
        ));

//        $form->addField(array(
//            'title' => 'With whom',
//            'name' => 'friend_tags',
//            'input_type' => 'relationship_tags',
//            'options' => 'hj_gallery_prepare_relationship_tags();'
//        ));
        $form->addField(array(
            'title' => 'Tags',
            'name' => 'tags',
            'input_type' => 'tags'
        ));
        $form->addField(array(
            'title' => 'Copyright',
            'name' => 'copyright'
        ));
	$form->addField(array(
	    'title' => 'Who can add photos',
            'name' => 'permissions',
            'input_type' => 'dropdown',
            'options_values' => 'hj_gallery_prepare_permissions_array();'
	));
	$form->addField(array(
            'title' => 'Access Level',
            'input_type' => 'access',
            'name' => 'access_id'
        ));
        $form->addField(array(
            'name' => 'datatype',
            'input_type' => 'hidden',
            'value' => 'gallery'
        ));
        $form->addField(array(
            'name' => 'data_pattern',
            'input_type' => 'hidden',
            'value' => "hj_framework_get_data_pattern('object', 'hjalbum');"
        ));

        return true;
    }
    return false;
}

function hj_gallery_setup_album_image_form() {
    $form = new hjForm();
    $form->title = 'hypeGallery:album:image';
    $form->label = 'Add New Image';
    $form->description = '';
    $form->subject_entity_subtype = 'hjalbumimage';
    $form->notify_admins = false;
    $form->add_to_river = true;
    $form->comments_on = true;
    $form->ajaxify = true;

    if ($form->save()) {
        $form->addField(array(
            'title' => 'Upload Image',
            'name' => 'image',
            'input_type' => 'file'
        ));
        $form->addField(array(
            'title' => 'Title',
            'name' => 'title',
            'mandatory' => true
        ));
        $form->addField(array(
            'title' => 'Description',
            'name' => 'description',
            'input_type' => 'longtext',
        ));
        $form->addField(array(
            'title' => 'Where',
            'name' => 'location',
            'input_type' => 'location',
        ));
        $form->addField(array(
            'title' => 'When',
            'input_type' => 'date',
            'name' => 'date',
        ));
//        $form->addField(array(
//            'title' => 'With whom',
//            'name' => 'friend_tags',
//            'input_type' => 'relationship_tags',
//            'options' => 'hj_gallery_prepare_relationship_tags();'
//        ));
        $form->addField(array(
            'title' => 'Tags',
            'name' => 'tags',
            'input_type' => 'tags'
        ));
        $form->addField(array(
            'title' => 'Copyright',
            'name' => 'copyright'
        ));
        $form->addField(array(
            'title' => 'Access Level',
            'input_type' => 'access',
            'name' => 'access_id'
        ));

        return true;
    }
    return false;
}

/**
 * Register subtypes with stdClasses
 */
run_function_once('hj_gallery_add_subtypes');

function hj_gallery_add_subtypes() {
    add_subtype('object', 'hjalbum', 'hjAlbum');
    add_subtype('object', 'hjalbumimage', 'hjAlbumImage');
}