<?php

return array(
    // Module classes
    'classes' => array(
		'Modules\Gallery\Components\GalleryComponent',
		'Modules\Gallery\Controllers\GalleryAlbums',
		'Modules\Gallery\Controllers\GalleryAlbumItems',
    ),
    // Management links
    'managementLinks' => array(
        A::t('gallery', 'Gallery Albums') => 'galleryAlbums/manage',
        //A::t('gallery', 'Gallery Album Items') => 'galleryAlbumItems/manage'
    ),    
);
