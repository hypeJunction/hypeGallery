<?php

$spanish = array(

    /**
     *  Gallery UI elements
     */
    'gallery' => 'Gallery',
    'gallery:menu:owner_block' => 'Fotos',

	'item:object:hjalbum' => 'Album',
	'items:object:hjalbum' => 'Albums',
	'gallery:albums' => 'Albums',
	
	'item:object:hjalbumimage' => 'Media',
	'items:object:hjalbumimage' => 'Media',

    'gallery:album:owner' => "%s's Album",
    'gallery:albums:owner' => "%s's Albums",
	'gallery:albums:friends' => "Amigos' Albums",
	'gallery:albums:friends:owner' => "%s\s Amigos' Albums",
	'gallery:album:author' => 'by %s',
	'gallery:albums:all' => 'Todos los Albums',
	'gallery:albums:group' => '%s\'s Albums',
	'gallery:albums:groups' => 'Albums de grupo',
    'gallery:addnew' => 'Crear Album',
    'gallery:addimage' => 'Subir archivos',
    'gallery:noalbums' => 'No hay albums creados',
	'gallery:allalbums' => 'Todos los Albums',
	'gallery:albums:mine' => 'Mis Albums',
	'gallery:album:edit' => 'Editar %s',
	'gallery:albums:favorites' => 'Favoritos',
	'gallery:albums:favorites:mine' => 'Mis Favoritos',
	'gallery:albums:favorites:owner' => '%s\'s Favoritos',
	'gallery:edit:more' => 'Agregar info',

	'gallery:albums:friends:none' => 'No tienes amigos aun',

	'gallery:add' => 'Crear un album',
	'gallery:edit:object:hjalbum' => 'Editar album',
	'gallery:edit:details' => 'Agregar otros detalles',

	'gallery:create:album' => 'Crear un album',
	'gallery:manage:album' => 'Administrar Album',
	'gallery:manage:instructions' => 'Este es un álbum de colaboración y se le permite subir imágenes al mismo. A continuación sólo verás los archivos subidos por ti',
	
    /**
     * Labels
     */
    'label:form:new:hypeGallery:gallery:create' => 'Crear una nueva Galeria',
    'label:form:edit:hypeGallery:gallery:create' => 'Editar tu Galeria',
    'label:form:new:hypeGallery:album' => 'Agregar Album',
    'label:form:edit:hypeGallery:album' => 'Editar Album',
    'label:form:new:hypeGallery:album:image' => 'Agregar Imagen',
    'label:form:edit:hypeGallery:album:image' => 'Editar Imagen',

    'label:hjalbum:title' => 'Nombre del Album',
    'label:hjalbum:description' => 'Descripcion',
    'label:hjalbum:location' => 'Ubicacion del Album',
    'label:hjalbum:date' => 'Fecha del Album',
    'label:hjalbum:friend_tags' => 'Amigos en este album',
    'framework:relationship_tags:notagged_in' => 'Aun no tienes amigos',
    'label:hjalbum:tags' => 'Etiquetas',
    'label:hjalbum:copyright' => 'Derechos de autor',
    'label:hjalbum:access_id' => 'Visibilidad',
	'label:hjalbum:upload' => 'Subir Imagenes',
	'label:hjalbum:category' => 'Categorias',
	'label:hjalbum:permission' => 'Quienes pueden agregar fotos a tu album?',
	'label:hjalbum:time_created' => 'Fecha de Creacion',
	'label:hjalbum:last_action' => 'Ultima subida',
	'label:hjalbum:owner' => 'Creador',

    'label:hjalbumimage:image' => 'Subir Imagen',
    'label:hjalbumimage:title' => 'Titulo',
    'label:hjalbumimage:description' => 'Descripcion',
    'label:hjalbumimage:location' => 'Lugar',
    'label:hjalbumimage:date' => 'Fecha',
    'label:hjalbumimage:friend_tags' => 'Amigos en esta foto',
    'label:hjalbumimage:tags' => 'Tags',
    'label:hjalbumimage:copyright' => 'Derechos de autor',
    'label:hjalbumimage:access_id' => 'Visibilidad',
	'label:hjalbumimage:time_created' => 'Subida',
	'label:hjalbumimage:owner' => 'Agregada por',
	'label:hjalbumimage:category' => 'Categorias',
	
	'permission:value:private' => 'Solo yo',
	'permission:value:friends' => 'Yo y mis amigos',
	'permission:value:public' => 'Todo el mundo',
	'permission:value:group' => 'Miembros del grupo',

	'gallery:image:makeavatar' => 'Hacer foto de perfil',
	'gallery:image:makecover' => 'Hacer caratula de album',
	'gallery:image:download' => 'Descargar',
	'gallery:image:cropper' => 'Recortar nuevas miniaturas',
	'gallery:image:reorder' => 'Arrastrar y soltar para reordenar',
	'gallery:image:priority' => 'Ordering position',
	'gallery:image:tag' => 'Tag',

	'gallery:tools:crop:success' => 'las miniaturas han sido creadas',
	'gallery:tools:crop:error' => 'Miniaturas no fueron creadas',
	'gallery:tools:crop' => 'Crear una miniatura',
	'gallery:tools:crop:preview' => 'Nueva miniatura',
	'gallery:tools:crop:current' => ' Miniatura actual',
	'gallery:tools:crop:instructions' => '<b>&larr;</b>Haga clic y arrastre un cuadrado a la izquierda para que coincida con la forma en que desea que la imagen sea recortada. Una vista previa aparecerá en el cuadro de abajo. Puede tomar algún tiempo para que los cambios en la propagación del vector, hasta que la caché del navegador está desactivada',
	'gallery:tools:crop:loading' => 'Cargando herramienta de recorte...',
	'gallery:tools:crop:ready' => 'Herramienta de corte',
	
	'gallery:byline' => 'Publicado por %s %s',
	'gallery:published' => 'Publicado en %s',

    /**
     * Actions
     */
    'gallery:save:success' => 'El artículo fue guardado con éxito',
    'gallery:save:error' => 'El artículo NO fue guardado',
    'gallery:delete:success' => 'El artículo fue BORRADO con éxito',
    'gallery:delete:error' => 'El artículo NO fue borrado',

	'gallery:upload:error:noalbum' => 'El album no existe o no tienes los permisos para agregar archivos en el',
	
	/**
     * River
     */
	'river:create:object:hjalbumimage' => '%s ha subido una nueva imagen %s',
	'river:update:object:hjalbumimage' => '%s actualizado su imagen %s',

	'river:create:object:hjalbum' => '%s creado un nuevo album %s [%s images]',
	'river:update:object:hjalbum' => '%s subido %s imagenes en un album %s [%s images]',
	'gallery:new' => 'nuevo',

	'gallery:tools:cover:success' => 'Una nueva caratula fue establecida',
	'gallery:tools:cover:error' => 'Hubo un problema para establecer la caratula del album',

	'gallery:tools:tagger:start' => 'Iniciar etiquetado',
	'gallery:tools:tagger:stop' => 'Detener etiquetado',
	'gallery:phototag:success' => 'Etiqueta agregada con exito',
	'gallery:phototag:error' => 'la etiqueta no fue agregada',

	'gallery:menu:owner_block' => 'Albums',

	'gallery:enablegallery' => 'Activar galeria de grupo',
	'gallery:group' => 'Albums del grupo',

	'gallery:image:container' => ' en %s',

	'gallery:switch:photostream' => 'Galerias de fotos',
	'gallery:switch:albums' => 'Vista de Album',
	'gallery:switch:thumbs' => 'Miniaturas',
	'gallery:switch:details' => 'Summary',
	'gallery:switch:detail_full' => 'Full',
	'gallery:goto:full' => 'Ver imagen de perfil',

	'gallery:list_type_toggle:table' => 'Table',
	'gallery:list_type_toggle:gallery' => 'Galeria',
	'gallery:list_type_toggle:map' => 'Mapa',

	'gallery:upload:toalbum' => 'Subir',
	'gallery:upload' => 'Agregar imagenes',

	'gallery:filter' => 'Filtrar Albums & Imagenes',

	'gallery:upload:imagesuploaded' => '%s archivos subidos con exito',
	'gallery:upload:error' => 'Ocurrio un error mientras subiamos tu archivo',
	'gallery:upload:success' => 'Archivo subido con exito',
	'gallery:upload:unsupportedtype' => '%s Los archivos no se subieron (Tipo incompatible u otro inconveniente)',
	'gallery:upload:pending' => '%s Archivos pendientes de aprobacion',
	'gallery:upload:pending:message' => '
		%s imagenes fueron subidas a tu album %s y estan pendientes de aprobacion. <br />
		Tu puedes aprobarlo siguiendo este link: <br />%s
	',

	'gallery:nofriends' => 'No tienes amigos',
	'gallery:nogroups' => 'No eres miembro de ningun grupo',

	'gallery:groupoption:enable' => 'desactivar albums de grupo',

	'gallery:approve' => 'Aprobado',
	'gallery:approve:error' => 'Ocurrio un error al intentar aprobar la imagen',
	'gallery:approve:success' => 'Imagen aprobada',
	'gallery:upload:approved' => 'Imagenes han sido aprobadas',
	'gallery:upload:approved:message' => 'Una o mas imagenes que subiste a %s han sido aprobadas',

	'gallery:image:thumb:reset' => 'Reiniciar miniaturas',
	'gallery:thumb:reset:success' => 'las miniaturas fueron reiniciadas con exito',
	'gallery:thumb:reset:error' => 'Ha ocurrido un error reiniciando las imagenes',

	'gallery:tagger:instructions' => 'Para crear una etiqueta, seleccione un área de la foto',

	'edit:plugin:hypegallery:params[album_river]' => 'Agregar actualizaciones de los álbumes a tu actividad',
	'edit:plugin:hypegallery:hint:album_river' => 'Al habilitar esta opción, se agregará entradas sobre las imágenes subidas recientemente a la actividad',

	'edit:plugin:hypegallery:params[favorites]' => 'Activar pestaña de favoritos en el salpicadero',
	'edit:plugin:hypegallery:hint:favorites' => 'Al habilitar esta opción, se agregará una ficha de favoritos y mostrara una lista de imágenes que han gustado',

	'edit:plugin:hypegallery:params[interface_location]' => 'Activar interfaz de ubicacion',
	'edit:plugin:hypegallery:hint:interface_location' => 'Al habilitar esta opción, se agregará un campo de ubicación requerido para las imágenes, y añade una vista de mapa (se debe habilitar el mapa)',

	'edit:plugin:hypegallery:params[interface_calendar]' => 'Habilitar fechas para álbumes e imágenes',
	'edit:plugin:hypegallery:hint:interface_calendar' => 'Al habilitar esta opción, se agregará un campo de fecha requerida a las imágenes, y añade una vista de calendario (se debe habilitar la interfaz de calendario)',

	'edit:plugin:hypegallery:params[copyrights]' => 'Agregar informacion de derechos de autor a los albums e imagenes',
	'edit:plugin:hypegallery:hint:copyrights' => 'Al habilitar esta opción, se agregará un campo requerido copyright a las imágenes',

	'edit:plugin:hypegallery:params[categories]' => 'Activar categorias',
	'edit:plugin:hypegallery:hint:categories' => '
Al habilitar esta opción, se agregará un campo de categorías a las imágenes',

	'edit:plugin:hypegallery:params[collaborative_albums]' => 'Activar albums de colaboracion',
	'edit:plugin:hypegallery:hint:collaborative_albums' => 'Al habilitar esta opción, se permite a los usuarios crear álbumes de colaboración; otros usuarios se les dará la oportunidad de añadir imágenes a los álbumes de colaboración, pero deberan esperar la aprobacion del creador del album',

	'edit:plugin:hypegallery:params[group_albums]' => 'Habilitar albums de grupo',
	'edit:plugin:hypegallery:hint:group_albums' => 'Al habilitar esta opción, se agregará la interfaz galería para grupos',

	'edit:plugin:hypegallery:params[avatars]' => 'Los usuarios pueden utilizar las imágenes subidas como avatares',
	'edit:plugin:hypegallery:hint:avatars' => 'Al habilitar esta opción permitirá a los usuarios utilizar las imágenes subidas como sus avatares',

	'edit:plugin:hypegallery:params[tagging]' => 'Habilitar área de imagen etiquetado',
	'edit:plugin:hypegallery:hint:tagging' => 'Al habilitar esta opción permitirá a los usuarios añadir etiquetas de área de imagen de las imágenes',

	'edit:plugin:hypegallery:params[downloads]' => 'Activar descargas',
	'edit:plugin:hypegallery:hint:downloads' => 'Al habilitar esta opción permitirá a los usuarios descargar imágenes',

	'edit:plugin:hypegallery:params[public_downloads]' => 'Activar descargas publicas',
	'edit:plugin:hypegallery:hint:public_downloads' => 'Al activar esta opción permitirá a los usuarios registrados a descargar imágenes (sólo si las descargas están habilitadas)',

	'edit:plugin:hypegallery:params[exif]' => 'habilitar EXIF',
	'edit:plugin:hypegallery:hint:exif' => 'Enabling EXIF tag parsing for qualifying images',

	'album:untitled' => 'Sin titulo',
	'gallery:filedrop:instructions' => 'Arrastrar y soltar archivos en esta area o %s desde su computadora',
	'gallery:filedrop:fallback' => 'seleccionar',
	'gallery:filedrop:browsernotsupported' => 'Tu navegador no acepta la funcion arrastrar y soltar',
	'gallery:filedrop:filetoolarge' => 'uno o mas de sus archivos excede el limite de tamaño permitido',
	'gallery:filedrop:filetypenotallowed' => 'Uno o más archivos no tienen un tipo de archivo permitido',

	'gallery:slideshow' => 'Iniciar Slideshow',
	
	'gallery:slideshow:loading' => 'cargando Slideshow...',
	'gallery:slideshow:pager' => '%s de %s',

	'gallery:list:empty' => 'no hay nada que mostrar',
	'gallery:inthisphoto' => 'en esta foto',
	'gallery:image:tag:create' => 'Agregar etiquetas',
	'gallery:image:tag:instructions' => 'Para crear una nueva etiqueta, seleccione un área de la foto, rellene el siguiente formulario y haga clic en Guardar',
	'gallery:image:tag:keyword' => 'Claves',
	'gallery:image:tag:friend' => 'Amigos',
	
	'gallery:inthisphoto:none' => 'Esta foto no tiene etiquetas',

	'gallery:user:tagged' => 'Has sido etiquetado en una foto',
	'gallery:user:tagged:message' => 'Te han etiquetado en una foto. puedes verla o borrarla aqui: %s',
	'gallery:phototag:river' => '%s etiquetado %s en %s',
	'hj:gallery:phototag:river' => '%s etiquetado %s en %s', // legacy

	'gallery:exif' => 'EXIF Cabeceras de imagen',
	'gallery:exif:resolution' => '%s dpi',

	'exif.Model' => 'Camara',
	'exif.LensInfo' => 'Info del lente',
	'exif.LensModel' => 'Modelo del lente',
	'exif.LensSerialNumber' => 'Numero Serial del lente',
	'exif.Copyright' => 'Copyright',
	'exif.ImageDescription' => 'Descripcion',
	'exif.Software' => 'Software',
	'exif.ModifyDate' => 'fecha y hora (Modificado)',
	'exif.XResolution' => 'X-Resolucion',
	'exif.YResolution' => 'Y-Resolucion',
	'exif.ResolutionUnit' => 'Unidad de Resolucion',
	'exif.ResolutionUnit.1' => 'No absolute unit of measurement',
	'exif.ResolutionUnit.2' => 'Pulgada',
	'exif.ResolutionUnit.3' => 'Centimetro',

	'exif.ExposureTime' => 'Exposicion (es)',
	'exif.FNumber' => 'F-Numero',
	'exif.ApertureValue' => 'Valor de Apertura',
	'exif.BrightnessValue' => 'Brillo',
	'exif.ExposureBiasValue' => 'Exposure Bias',
	'exif.MaxApertureValue' => 'Max Aperture Value',
	'exif.SubjectDistance' => 'Subject Distance (m)',
	'exif.SubjectArea' => 'Subject Area',
	'exif.SubjectLocation' => 'Subject Location',
	
	'exif.ExposureProgram' => 'Exposure Program',
	'exif.ExposureProgram.0' => 'Not defined',
	'exif.ExposureProgram.1' => 'Manual',
	'exif.ExposureProgram.2' => 'Normal program',
	'exif.ExposureProgram.3' => 'Aperture priority',
	'exif.ExposureProgram.4' => 'Shutter priority',
	'exif.ExposureProgram.5' => 'Creative program (biased toward depth of field)',
	'exif.ExposureProgram.6' => 'Action program (biased toward fast shutter speed)',
	'exif.ExposureProgram.7' => 'Portrait mode (for closeup photos with the background out of focus)',
	'exif.ExposureProgram.8' => 'Landscape mode (for landscape photos with the background in focus)',

	'exif.ComponentsConfiguration' => 'Components Configuration',
	'exif.ComponentsConfiguration.0' => 'No existe',
	'exif.ComponentsConfiguration.1' => 'Y',
	'exif.ComponentsConfiguration.2' => 'Cb',
	'exif.ComponentsConfiguration.3' => 'Cr',
	'exif.ComponentsConfiguration.4' => 'R',
	'exif.ComponentsConfiguration.5' => 'G',
	'exif.ComponentsConfiguration.6' => 'B',
	'exif.ComponentsConfiguration.Other' => 'Otro',

	'exif.MeteringMode' => 'Metering Mode',
	'exif.MeteringMode.0' => 'Desconocido',
	'exif.MeteringMode.1' => 'Average',
	'exif.MeteringMode.2' => 'CenterWeightedAverage',
	'exif.MeteringMode.3' => 'Spot',
	'exif.MeteringMode.4' => 'MultiSpot',
	'exif.MeteringMode.5' => 'Pattern',
	'exif.MeteringMode.6' => 'Parcial',
	'exif.MeteringMode.255' => 'Otro',

	'exif.LightSource' => 'Light Source',
	'exif.LightSource.0' => 'desconocido',
	'exif.LightSource.1' => 'Luz diurna',
	'exif.LightSource.2' => 'Fluorescente',
	'exif.LightSource.3' => 'Tungsteno (Luz incandescente)',
	'exif.LightSource.4' => 'Flash',
	'exif.LightSource.9' => 'Fine weather',
	'exif.LightSource.10' => 'Cloudy weather',
	'exif.LightSource.11' => 'Shade',
	'exif.LightSource.12' => 'Daylight fluorescent (D 5700 - 7100K)',
	'exif.LightSource.13' => 'Day white fluorescent (N 4600 - 5400K)',
	'exif.LightSource.14' => 'Cool white fluorescent (W 3900 - 4500K)',
	'exif.LightSource.15' => 'White fluorescent (WW 3200 - 3700K)',
	'exif.LightSource.17' => 'Standard light A',
	'exif.LightSource.18' => 'Standard light B',
	'exif.LightSource.19' => 'Standard light C',
	'exif.LightSource.20' => 'D55',
	'exif.LightSource.21' => 'D65',
	'exif.LightSource.22' => 'D75',
	'exif.LightSource.23' => 'D50',
	'exif.LightSource.24' => 'ISO studio tungsten',
	'exif.LightSource.255' => 'Other light source',

	'exif.Flash' => 'Flash',
	'exif.Flash.0' => 'Flash did not fire',
	'exif.Flash.1' => 'Flash fired',
	'exif.Flash.5' => 'Strobe return light not detected',
	'exif.Flash.7' => 'Strobe return light detected',
	'exif.Flash.9' => 'Flash fired, compulsory flash mode',
	'exif.Flash.13' => 'Flash fired, compulsory flash mode, return light not detected',
	'exif.Flash.15' => 'Flash fired, compulsory flash mode, return light detected',
	'exif.Flash.16' => 'Flash did not fire, compulsory flash mode',
	'exif.Flash.24' => 'Flash did not fire, auto mode',
	'exif.Flash.25' => 'Flash fired, auto mode',
	'exif.Flash.29' => 'Flash fired, auto mode, return light not detected',
	'exif.Flash.31' => 'Flash fired, auto mode, return light detected',
	'exif.Flash.32' => 'No flash function',
	'exif.Flash.65' => 'Flash fired, red-eye reduction mode',
	'exif.Flash.69' => 'Flash fired, red-eye reduction mode, return light not detected',
	'exif.Flash.71' => 'Flash fired, red-eye reduction mode, return light detected',
	'exif.Flash.73' => 'Flash fired, compulsory flash mode, red-eye reduction mode',
	'exif.Flash.77' => 'Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected',
	'exif.Flash.79' => 'Flash fired, compulsory flash mode, red-eye reduction mode, return light detected',
	'exif.Flash.89' => 'Flash fired, auto mode, red-eye reduction mode',
	'exif.Flash.93' => 'Flash fired, auto mode, return light not detected, red-eye reduction mode',
	'exif.Flash.95' => 'Flash fired, auto mode, return light detected, red-eye reduction mode',

	'exif.FlashEnergy' => 'Flash Energy',
	'exif.SpatialFrequencyResponse' => 'Spatial Frequency Response',
	'exif.FocalPlaneXResolution' => 'Focal Panel X-Resolution',
	'exif.FocalPlaneYResolution' => 'Focal Panel Y-Resolution',
	
	'exif.FocalPlaneResolutionUnit' => 'Focal Panel Resolution Unit',
	'exif.FocalPlaneResolutionUnit.1' => 'No absolute unit of measurement',
	'exif.FocalPlaneResolutionUnit.2' => 'Inch',
	'exif.FocalPlaneResolutionUnit.3' => 'Centimeter',
	
	'exif.ISO' => 'ISO Speed',
	'exif.ISOSpeedRatings' => 'ISO Speed',

	'exif.SensitivityType' => 'Sensitivity Type',
	'exif.SpectralSensitivity' => 'Spectral Sensitivity',
	'exif.RecommendedExposureIndex' => 'Recommended Exposure Index',
	'exif.ExifVersion' => 'EXIF version',
	'exif.FlashpixVersion' => 'Flashpix version',

	'exif.DateTime' => 'Date and Time',
	'exif.DateTimeOriginal' => 'Date and Time (Original)',
	'exif.DateTimeDigitized' => 'Date and Time (Digitized)',
	'exif.SubsecTime' => 'Sub Sec Time of Date and Time value',
	'exif.SubsecTimeOriginal' => 'Sub Sec Time of Date and Time (Original) value',
	'exif.SubsecTimeDigitized' => 'Sub Sec Time of Date and Time )Digitized) value',

	'exif.CompressedBitsPerPixel' => 'Compressed Bits per Pixel',
	'exif.ShutterSpeedValue' => 'Shutter Speed Value',
	'exif.FocalLength' => 'Focal Length',
	'exif.UserComment' => 'Comment',
	'exif.ColorSpace' => 'Color Space',
	'exif.PixelXDimension' => 'Pixel X-Dimension',
	'exif.PixelYDimension' => 'Pixel Y-Dimension',
	'exif.ExposureIndex' => 'Exposure Index',

	'exif.SensingMethod' => 'Sensing Method',
	'exif.SensingMethod.1' => 'Not defined',
	'exif.SensingMethod.2' => 'One-chip color area sensor',
	'exif.SensingMethod.3' => 'Two-chip color area sensor',
	'exif.SensingMethod.4' => 'Three-chip color area sensor',
	'exif.SensingMethod.5' => 'Color sequential area sensor',
	'exif.SensingMethod.7' => 'Trilinear sensor',
	'exif.SensingMethod.8' => 'Color sequential linear sensor',

	'exif.SceneType' => 'Scene Type',

	'exif.CFAPattern' => 'CFA Pattern',
	'exif.CFAPattern.0' => 'Red',
	'exif.CFAPattern.1' => 'Green',
	'exif.CFAPattern.2' => 'Blue',
	'exif.CFAPattern.3' => 'Cyan',
	'exif.CFAPattern.4' => 'Magenta',
	'exif.CFAPattern.5' => 'Yellow',
	'exif.CFAPattern.6' => 'White',

	'exif.CustomRendered' => 'Custom Rendered',
	'exif.CustomRendered.0' => 'Normal process',
	'exif.CustomRendered.1' => 'Custom process',

	'exif.ExposureMode' => 'Exposure Mode',
	'exif.ExposureMode.0' => 'Auto exposure',
	'exif.ExposureMode.1' => 'Manual exposure',
	'exif.ExposureMode.2' => 'Auto bracket',

	'exif.WhiteBalance' => 'White Balance',
	'exif.WhiteBalance.0' => 'Auto white balance',
	'exif.WhiteBalance.1' => 'Manual white balance',

	'exif.DigitalZoomRatio' => 'Digital Zoom Ratio',
	'exif.FocalLengthIn35mmFilm' => 'Focal Length in 35mm film equiv',

	'exif.SceneCaptureType' => 'Scene Capture Type',
	'exif.SceneCaptureType.0' => 'Standard',
	'exif.SceneCaptureType.1' => 'Landscape',
	'exif.SceneCaptureType.2' => 'Portrait',
	'exif.SceneCaptureType.3' => 'Night Scene',

	'exif.GainControl' => 'Gain Control',
	'exif.GainControl.0' => 'None',
	'exif.GainControl.1' => 'Low gain up',
	'exif.GainControl.2' => 'High gain up',
	'exif.GainControl.3' => 'Low gain down',
	'exif.GainControl.4' => 'High gain down',

	'exif.Contrast' => 'Contrast',
	'exif.Contrast.0' => 'Normal',
	'exif.Contrast.1' => 'Soft',
	'exif.Contrast.2' => 'Hard',

	'exif.Saturation' => 'Saturation',
	'exif.Saturation.0' => 'Normal',
	'exif.Saturation.1' => 'Low saturation',
	'exif.Saturation.2' => 'High saturation',

	'exif.Sharpness' => 'Sharpness',
	'exif.Sharpness.0' => 'Normal',
	'exif.Sharpness.1' => 'Soft',
	'exif.Sharpness.2' => 'Hard',

	'exif.DeviceSettingDescription' => 'Device Setting Description',

	'exif.SubjectDistanceRange' => 'Subject Distance Range',
	'exif.SubjectDistanceRange.0' => 'Unknown',
	'exif.SubjectDistanceRange.1' => 'Macro',
	'exif.SubjectDistanceRange.2' => 'Close view',
	'exif.SubjectDistanceRange.3' => 'Distant view',

	'exif.ImageUniqueID' => 'Unique Image ID',
	
	'exif.GPSVersionID' => 'GPS Version ID',
	'exif.GPSAltitude' => 'Altitude',
	'exif.GPSAltitudeRef' => 'Altitude Ref',
	'exif.GPSAltitudeRef.0' => 'Above sea level',
	'exif.GPSAltitudeRef.1' => 'Below sea level',
	'exif.GPSLatitude' => 'Latitude',
	'exif.GPSLongitude' => 'Longitude',
	

	// widgets
	'gallery:widget:photostream' => 'Secuencia de fotos',
	'gallery:widget:photostream:desc' => 'Secuencia de las ultimas fotos',
	'gallery:widget:albums' => 'Albums',
	'gallery:widget:albums:desc' => 'Una lista de los ultimos albums',
	'gallery:widget:more' => 'Ver todo',
	'gallery:widget:limit' => 'Numero de imagenes a mostrar',
	'gallery:widget:none' => 'Nada que mostrar',


	// downloads
	'gallery:download:error:disabled' => 'las descargas fueron desactivadas por el administrador del sitio',
	'gallery:download:error:disabled_public' => 'Las descargas publicas fueron desactivadas por el administrador del sitio. Por favor ingrese',
	
	// embeds
	'embed:albumimages' => 'Imagenes del Album',
	
);

add_translation("es", $spanish);
