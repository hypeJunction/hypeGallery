<?php

$english = array(

    /**
     *  Gallery UI elements
     */
    'gallery' => 'Gallery',
    'hj:gallery:menu:owner_block' => 'Gallery',

	'item:object:hjalbum' => 'Album',
	'items:object:hjalbum' => 'Albums',
	'hj:gallery:albums' => 'Albums',
	
	'item:object:hjalbumimage' => 'Album Image',
	'items:object:hjalbumimage' => 'Album Images',

    'hj:gallery:album:owner' => "%s's Album",
    'hj:gallery:albums:owner' => "%s's Albums",
	'hj:gallery:albums:friends' => "Friends' Albums",
	'hj:gallery:albums:friends:owner' => "%s\s Friends' Albums",
	'hj:gallery:album:author' => 'by %s',
	'hj:gallery:albums:all' => 'Site Albums',
	'hj:gallery:albums:group' => '%s\'s Albums',
	'hj:gallery:albums:groups' => 'Group Albums',
    'hj:gallery:addnew' => 'Create Album',
    'hj:gallery:addimage' => 'Add Photo',
    'hj:gallery:noalbums' => 'There are no albums yet',
	'hj:gallery:allalbums' => 'All Site Albums',
	'hj:gallery:albums:mine' => 'My Albums',
	'hj:gallery:album:edit' => 'Edit %s',
	'hj:gallery:album:photos' => '%s photos',
	'hj:gallery:albums:favorites' => 'Favorites',
	'hj:gallery:albums:favorites:mine' => 'My Favorites',
	'hj:gallery:albums:favorites:owner' => '%s\'s Favorites',

	'hj:gallery:albums:friends:none' => 'You do not have any friends yet',
	'hj:gallery:image:author' => 'Added by %s',

	'gallery:add' => 'Create new album',

	'hj:gallery:create:album' => 'Create an album',
	'hj:gallery:manage:album' => 'Manage Album',
	'hj:gallery:manage:instructions' => 'Permissions of this album allow you to upload images. Below you will only see images uploaded by you',
	
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
    'hj:label:hjalbum:tags' => 'Tags',
    'hj:label:hjalbum:copyright' => 'Copyright Notices',
    'hj:label:hjalbum:access_id' => 'Visibility',
	'hj:label:hjalbum:upload' => 'Upload Images',
	'hj:label:hjalbum:category' => 'Categories',
	'hj:label:hjalbum:permissions' => 'Who can add photos to this album?',
	'hj:label:hjalbum:time_created' => 'Date Created',
	'hj:label:hjalbum:last_action' => 'Last Updated',
	'hj:label:hjalbum:owner' => 'Creator',

    'hj:label:hjalbumimage:image' => 'Upload Image',
    'hj:label:hjalbumimage:title' => 'Title',
    'hj:label:hjalbumimage:description' => 'Description',
    'hj:label:hjalbumimage:location' => 'Location',
    'hj:label:hjalbumimage:date' => 'Date',
    'hj:label:hjalbumimage:friend_tags' => 'Friends in this photo',
    'hj:label:hjalbumimage:tags' => 'Tags',
    'hj:label:hjalbumimage:copyright' => 'Copyright Notices',
    'hj:label:hjalbumimage:access_id' => 'Visibility',
	'hj:label:hjalbumimage:time_created' => 'Uploaded',
	'hj:label:hjalbumimage:owner' => 'Added by',
	'hj:label:hjalbumimage:category' => 'Categories',
	
	'permission:value:private' => 'Just me',
	'permission:value:friends' => 'Me and my friends',
	'permission:value:public' => 'Everyone',
	'permission:value:group' => 'Group Members',

	'hj:album:image:makeavatar' => 'Make Profile Picture',
	'hj:album:image:makecover' => 'Make Album Cover',
	'hj:album:image:download' => 'Download',
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

	'river:create:object:hjalbum' => '%s created a new album | %s (%s images)',
	'river:update:object:hjalbum' => '%s uploaded %s images to an album | %s (%s images)',

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

	'hj:gallery:image:container' => ' in %s',

	'hj:gallery:switch:photostream' => 'Photostream',
	'hj:gallery:switch:albums' => 'Album View',
	'hj:gallery:switch:thumbs' => 'Thumbnails',
	'hj:gallery:switch:details' => 'Summary',
	'hj:gallery:switch:detail_full' => 'Full',
	'hj:gallery:goto:full' => 'View Image Profile',

	'hj:gallery:list_type_toggle:table' => 'Table',
	'hj:gallery:list_type_toggle:gallery' => 'Gallery',
	'hj:gallery:list_type_toggle:map' => 'Map',

	'hj:gallery:upload:toalbum' => 'Upload new images to %s',
	'hj:gallery:upload' => 'Add images',

	'hj:gallery:filter' => 'Filter Albums & Images',

	'hj:gallery:upload:imagesuploaded' => '%s images were successfully uploaded',
	'hj:gallery:upload:error' => 'An error occurred while uploading your images',
	'hj:gallery:upload:unsupportedtype' => '%s could not be uploaded due to unsupported type',
	'hj:gallery:upload:pending' => '%s images are pending approval',
	'hj:gallery:upload:pending:message' => '
		%s images were uploaded to your album %s and are pending approval. <br />
		You can approve them in by following this link: <br />%s
	',

	'hj:gallery:nofriends' => 'You do not have any friends yet',
	'hj:gallery:nogroups' => 'You do not belong to any groups yet',

	'hj:gallery:groupoption:enable' => 'Enable group albums',

	'hj:gallery:approve' => 'Approve',
	'hj:gallery:approve:error' => 'An error occurred while try to approve this image',
	'hj:gallery:approve:success' => 'Image successfully approved',
	'hj:gallery:upload:approved' => 'Images have been approved',
	'hj:gallery:upload:approved:message' => 'One or more images you uploaded to %s have been approved',

	'hj:album:image:thumb:reset' => 'Reset Thumbnails',
	'hj:gallery:thumb:reset:success' => 'Thumbnail was successfully reset',
	'hj:gallery:thumb:reset:error' => 'There was an error resetting the thumbnail',

	'hj:gallery:tagger:instructions' => 'To create a tag, select an area on the photo',

	'edit:plugin:hypegallery:params[album_river]' => 'Add album updates to the activity stream',
	'edit:plugin:hypegallery:hint:album_river' => 'Enabling this option will add entries about newly uploaded images to the activity stream',

	'edit:plugin:hypegallery:params[favorites]' => 'Enable favorites tab on the dashboard',
	'edit:plugin:hypegallery:hint:favorites' => 'Enabling this option will add a favorites tab and display a list of liked images',

	'edit:plugin:hypegallery:params[interface_location]' => 'Enable location interface',
	'edit:plugin:hypegallery:hint:interface_location' => 'Enabling this option will add a required location field to images, and add a map view (should the map interface be enabled)',

	'edit:plugin:hypegallery:params[interface_calendar]' => 'Enable dates for albums and images',
	'edit:plugin:hypegallery:hint:interface_calendar' => 'Enabling this option will add a required date field to images, and add a calendar view (should the calendar interface be enabled)',

	'edit:plugin:hypegallery:params[copyrights]' => 'Add copyright information to albums and images',
	'edit:plugin:hypegallery:hint:copyrights' => 'Enabling this option will add a required copyright field to images',

	'edit:plugin:hypegallery:params[categories]' => 'Enable categories',
	'edit:plugin:hypegallery:hint:categories' => 'Enablighs this option will add a categories field to images',

	'edit:plugin:hypegallery:params[collaborative_albums]' => 'Enable collaborative albums',
	'edit:plugin:hypegallery:hint:collaborative_albums' => 'Enabling this option will allows users to create collaborative albums; other users will be given an opportunity to add images to collaborative albums, pending original creator\'s approval',

	'edit:plugin:hypegallery:params[group_albums]' => 'Enable group albums',
	'edit:plugin:hypegallery:hint:group_albums' => 'Enabling this option will add gallery interface to groups',

	'edit:plugin:hypegallery:params[avatars]' => 'Users can use uploaded images as avatars',
	'edit:plugin:hypegallery:hint:avatars' => 'Enabling this option will allow users to use uploaded images as their avatars',

	'edit:plugin:hypegallery:params[tagging]' => 'Enable image area tagging',
	'edit:plugin:hypegallery:hint:tagging' => 'Enabligh this option will allow users to add image area tags to images',

	'edit:plugin:hypegallery:params[downloads]' => 'Enable downloads',
	'edit:plugin:hypegallery:hint:downloads' => 'Enabling this option will allow users to download images',
	
);

add_translation("en", $english);
?>