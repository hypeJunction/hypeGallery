<?php

$english = array(

    /**
     *  Gallery UI elements
     */
    'gallery' => 'Gallery',
    'hj:gallery:menu:owner_block' => 'Gallery',

	'item:object:hjalbum' => 'Album',
	'items:object:hjalbum' => 'Albums',

	'item:object:hjalbumimage' => 'Album Image',
	'items:object:hjalbumimage' => 'Album Images',

    'hj:gallery:album:owner' => "%s's Album",
    'hj:gallery:albums:owner' => "%s's Albums",
	'hj:gallery:albums:friends' => "Friends' Albums",
	'hj:gallery:album:author' => 'by %s',
	'hj:gallery:albums:all' => 'All Site Albums',
	'hj:gallery:albums:group' => '%s\'s Albums',
    'hj:gallery:addnew' => 'Create Album',
    'hj:gallery:addimage' => 'Add Photo',
    'hj:gallery:noalbums' => 'There are no albums yet',
	'hj:gallery:allalbums' => 'All Site Albums',
	'hj:gallery:myalbums' => 'My Albums',
	'hj:gallery:album:edit' => 'Edit %s',
	'hj:gallery:album:photos' => '%s photos',

	'hj:gallery:albums:friends:none' => 'You do not have any friends yet',
	'hj:gallery:image:author' => 'Added by %s',

	'gallery:add' => 'Create new album',

    /**
     *  Widgets
     */
    'hj:gallery:widget' => 'Add Gallery Section',
    'hj:gallery:widgetdescription' => 'Gallery Section Widget',
    'hj:gallery:widget:title' => 'Section Title',

    'hj:gallery:widget:type' => 'Section Type',
    /**
     * Labels
     *
     */
    'hj:label:form:new:hypeGallery:gallery:create' => 'Create New Gallery',
    'hj:label:form:edit:hypeGallery:gallery:create' => 'Edit Your Gallery',
    'hj:label:form:new:hypeGallery:album' => 'Add New Album',
    'hj:label:form:edit:hypeGallery:album' => 'Edit Album',
    'hj:label:form:new:hypeGallery:album:image' => 'Add Image',
    'hj:label:form:edit:hypeGallery:album:image' => 'Edit Image',

    'hj:label:hjalbum:title' => 'Album Name',
    'hj:label:hjalbum:description' => 'Description',
    'hj:label:hjalbum:location' => 'Album Location',
    'hj:label:hjalbum:date' => 'Album Date',
    'hj:label:hjalbum:friend_tags' => 'Friends in this album',
    'hj:framework:relationship_tags:notagged_in' => 'You do not yet have any friends',
    'hj:label:hjalbum:tags' => 'Word Tags',
    'hj:label:hjalbum:copyright' => 'Copyright Notices',
    'hj:label:hjalbum:access_id' => 'Visibility',

    'hj:label:hjalbumimage:image' => 'Upload Image',
    'hj:label:hjalbumimage:title' => 'Title',
    'hj:label:hjalbumimage:description' => 'Description',
    'hj:label:hjalbumimage:location' => 'Location',
    'hj:label:hjalbumimage:date' => 'Date',
    'hj:label:hjalbumimage:friend_tags' => 'Friends in this photo',
    'hj:label:hjalbumimage:tags' => 'Word Tags',
    'hj:label:hjalbumimage:copyright' => 'Copyright Notices',
    'hj:label:hjalbumimage:access_id' => 'Visibility',
	'hj:label:hjalbum:permissions' => 'Who can add photos to this album?',

	'permission:value:private' => 'Just me',
	'permission:value:friends' => 'Me and my friends',
	'permission:value:public' => 'Everyone',

	'hj:album:image:makeavatar' => 'Make Profile Picture',
	'hj:album:image:makecover' => 'Make Album Cover',
	'hj:album:image:editthumb' => 'Edit Thumbnail',
	'hj:album:image:tag' => 'Tag',

	'hj:album:editandupload' => 'Edit/Batch Upload',
	
	'hj:gallery:thumb:crop:success' => 'Thumbnail was successfully created',
	'hj:album:image:thumb:create' => 'Create a thumbnail',
	'hj:album:image:preview' => 'Preview',
	
	/**
     *  Modules
     */
    'hj:gallery:hjalbum' => 'Albums',
    'hj:hjgallery:hjalbum' => 'Albums',



    /**
     * Actions
     */
    'hj:gallery:save:success' => 'Gallery was successfully saved',
    'hj:gallery:save:error' => 'Gallery could not be saved',
    'hj:gallery:delete:success' => 'Gallery was successfully deleted',
    'hj:gallery:delete:error' => 'Gallery could not be deleted',
    'hj:gallery:setup:success' => 'Your gallery was successfully setup. You can now start populating it with information.',
    'hj:gallery:setup:error' => 'There was a problem setting up your gallery. Please contact the administrator.',
    'hj:gallery:nogallery' => 'This user doesn\'t have a gallery yet. Check back later',
    /**
     * River
     */
    'river:create:object:hjgallery' => '%s created a new gallery %s',
    'river:update:object:hjgallery' => '%s updated their gallery %s',

	'river:create:object:hjalbumimage' => '%s uploaded a new image | %s',
	'river:update:object:hjalbumimage' => '%s updated their image | %s',

	'river:create:object:hjalbum' => '%s created a new album | %s',
	'river:update:object:hjalbum' => '%s updated their album | %s',

	'hj:album:cover:success' => 'New album cover was successfully set',
	'hj:album:cover:error' => 'There was a problem with setting an album cover',

	'hj:album:image:startagging' => 'Start Tagging',
	'hj:album:image:stoptagging' => 'Stop Tagging',
	'hj:album:image:tag:create' => 'Add Tag',
	'hj:gallery:phototag:success' => 'Tag added successfully',
	'hj:gallery:phototag:error' => 'Tag could not be added',

	'hj:gallery:menu:owner_block' => 'Albums',

	'hj:gallery:enablegallery' => 'Enable group gallery',
	'gallery:group' => 'Group Albums',

	'hj:gallery:image:container' => 'in %s',
	
);

add_translation("en", $english);
?>