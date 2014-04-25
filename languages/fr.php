<?php

$french = array(

    /**
     *  Galerie UI elements
     */
    'gallery' => "Galerie",
    'gallery:menu:owner_block' => "Galerie",

	'item:object:hjalbum' => "Album",
	'items:object:hjalbum' => "Albums",
	'gallery:albums' => "Albums",
	
	'item:object:hjalbumimage' => "Média",
	'items:object:hjalbumimage' => "Média",

    'gallery:album:owner' => "Album de %s",
    'gallery:albums:owner' => "Albums de %s",
	'gallery:albums:friends' => "Albums d'amis",
	'gallery:albums:friends:owner' => "Albums des amis de %s",
	'gallery:album:author' => "par %s",
	'gallery:albums:all' => "Albums du site",
	'gallery:albums:group' => "Albums de %s",
	'gallery:albums:groups' => "Albums du groupe",
    'gallery:addnew' => "Créer un album",
    'gallery:addimage' => "Télécharger des fichiers",
    'gallery:noalbums' => "Il n'y a pas encore d'album",
	'gallery:allalbums' => "Tous les albums du site",
	'gallery:albums:mine' => "Mes albums",
	'gallery:album:edit' => "Modifier %s",
	'gallery:albums:favorites' => "Préférés",
	'gallery:albums:favorites:mine' => "Mes préférés",
	'gallery:albums:favorites:owner' => "Les préférées de %s",
	'gallery:edit:more' => 'Ajouter des infos',

	'gallery:albums:friends:none' => "Vous n'avez pas encore d'ami",

	'gallery:add' => "Créer un album",
	'gallery:edit:object:hjalbum' => "Modifier l'album",
	'gallery:edit:details' => "Ajouter d'autres détails",

	'gallery:create:album' => "Créer un album",
	'gallery:manage:album' => "Gérer l'album",
	'gallery:manage:instructions' => "Il s'agit d'un album collaboratif et vous pouvez y charger des images. Les images ci-dessous sont celles que vous avez chargées.",
	
    /**
     * Labels
     */
    'label:form:new:hypegallery:gallery:create' => "Créer une nouvelle galerie",
    'label:form:edit:hypegallery:gallery:create' => "Modifier votre galerie",
    'label:form:new:hypegallery:album' => "Ajouter un nouvel album",
    'label:form:edit:hypegallery:album' => "Modifier l'album",
    'label:form:new:hypegallery:album:image' => "Ajouter une image",
    'label:form:edit:hypegallery:album:image' => "Modifier l'image",

    'label:hjalbum:title' => "Nom de l'album",
    'label:hjalbum:description' => "Description",
    'label:hjalbum:location' => "Localisation de l'album",
    'label:hjalbum:date' => "Date de l'album",
    'label:hjalbum:friend_tags' => "Amis dans cet album",
    'framework:relationship_tags:notagged_in' => "Vous n'avez pas encore d'ami",
    'label:hjalbum:tags' => "Etiquettes",
    'label:hjalbum:copyright' => "Copyrights",
    'label:hjalbum:access_id' => "Visibilité",
	'label:hjalbum:upload' => "Charger des images",
	'label:hjalbum:category' => "Catégories",
	'label:hjalbum:permissions' => "Qui peut ajouter des photos à cet album?",
	'label:hjalbum:time_created' => "Date de création",
	'label:hjalbum:last_action' => "Dernière modification",
	'label:hjalbum:owner' => "Createur",

    'label:hjalbumimage:image' => "Charger des images",
    'label:hjalbumimage:title' => "Titre",
    'label:hjalbumimage:description' => "Description",
    'label:hjalbumimage:location' => "Localisation",
    'label:hjalbumimage:date' => "Date",
    'label:hjalbumimage:friend_tags' => "Amis sur cette photo",
    'label:hjalbumimage:tags' => "Etiquettes",
    'label:hjalbumimage:copyright' => "Copyrights",
    'label:hjalbumimage:access_id' => "Visibilité",
	'label:hjalbumimage:time_created' => "Chargé",
	'label:hjalbumimage:owner' => "Ajouté par",
	'label:hjalbumimage:category' => "Catégories",
	
	'permission:value:private' => "Juste moi",
	'permission:value:friends' => "Mes amis et moi",
	'permission:value:public' => "Tout le monde",
	'permission:value:group' => "Les membres du groupe",

	'gallery:image:makeavatar' => "En faire ma photo de profil",
	'gallery:image:makecover' => "En faire la couverture de l'album",
	'gallery:image:download' => "Télécharger",
	'gallery:image:cropper' => "Rogner les miniatures",
	'gallery:image:reorder' => "Glisser-déposer pour ordonner",
	'gallery:image:priority' => "No. d'ordre",
	'gallery:image:tag' => "Etiquettes",

	'gallery:tools:crop:success' => "Miniatures crées avec succès.",
	'gallery:tools:crop:error' => "Les moniatures n'ont pas pu être créées",
	'gallery:tools:crop' => "Créer une miniature",
	'gallery:tools:crop:preview' => "Nouvelles miniature",
	'gallery:tools:crop:current' => "Miniature courante",
	'gallery:tools:crop:instructions' => "<b>&larr;</b>Click and drag a square on the left to match how you want the thumb cropped. A preview will appear in the box below. It may take some time for the changes to propogate, until browser cache is cleared",
	'gallery:tools:crop:loading' => "Chargement de l'outil de coupe...",
	'gallery:tools:crop:ready' => "Outil de coupe",
	
	'gallery:byline' => "Publié par %s %s",
	'gallery:published' => "Publié le %s",

    /**
     * Actions
     */
    'gallery:save:success' => "Objet sauvegardé avec succès",
    'gallery:save:error' => "L'objet n'a pas pu être sauvegardé",
    'gallery:delete:success' => "Objet supprimé",
    'gallery:delete:error' => "L'objet n'a pas pu être supprimé",

	'gallery:upload:error:noalbum' => "L'album n'existe pas ou vous n'avez pas les privilèges d'y ajouter des images.",
	
	/**
     * River
     */
	'river:create:object:hjalbumimage' => "%s a ajouté une nouvelle image %s",
	'river:update:object:hjalbumimage' => "%s a mis à jour l'image %s",

	'river:create:object:hjalbum' => "%s a créé un nouvel album %s [%s images]",
	'river:update:object:hjalbum' => "%s a chargé %s images dans l'album %s [%s images]",
	'gallery:new' => "nouvelle(s)",

	'gallery:tools:cover:success' => "La nouvelle couverture de l'album a été placée",
	'gallery:tools:cover:error' => "Il y a eu un problème pour le changement de la couverture de l'album",

	'gallery:tools:tagger:start' => "Marquer",
	'gallery:tools:tagger:stop' => "Arrêter de marquer",
	'gallery:phototag:success' => "Etiquette ajoutée",
	'gallery:phototag:error' => "L'étiquette n'a pas peu être ajoutée",

	'gallery:menu:owner_block' => "Albums",

	'gallery:enableGalerie' => "Permettre les galeries de groupe",
	'gallery:group' => "Albums de groupe",

	'gallery:image:container' => " dans %s",

	'gallery:switch:photostream' => "Fil de photos",
	'gallery:switch:albums' => "Vues d'album",
	'gallery:switch:thumbs' => "Miniatures",
	'gallery:switch:details' => "Résumé",
	'gallery:switch:detail_full' => "Tout",
	'gallery:goto:full' => "Voir le profil de l'image",

	'gallery:list_type_toggle:table' => "Table",
	'gallery:list_type_toggle:Galerie' => "Galerie",
	'gallery:list_type_toggle:map' => "Plan",

	'gallery:upload:toalbum' => "Charger",
	'gallery:upload' => "Ajouter des images",

	'gallery:filter' => "Sélectionner des albums & des images",

	'gallery:upload:imagesuploaded' => "%s fichiers ont été chargé avec succès",
	'gallery:upload:error' => "Une erreur est survenue pendant le chargement de vos fichiers",
	'gallery:upload:success' => 'Votre fichier a été enregistré avec succès.',
	'gallery:upload:unsupportedtype' => "%s fichiers n'ont pas pu être chargé (mauvais type de fichier ou autre erreur)",
	'gallery:upload:pending' => "%s fichiers sont en attente d'approbation",
	'gallery:upload:pending:message' => "
		%s images  ont été chargés dans l'album %s et sont en attente d'approbation. <br />
		Vous pouvez les approuver en suivant ce lien: <br />%s
	",

	'gallery:nofriends' => "Vous n'avez pas encore d'ami",
	'gallery:nogroups' => "Vous n'appartenez à aucun groupe",

	'gallery:groupoption:enable' => "Permettre les albums de groupe",

	'gallery:approve' => "Approuver",
	'gallery:approve:error' => "Une erreur est survenue en tentant d'approuver cette image",
	'gallery:approve:success' => "Image approuvée avec succès",
	'gallery:upload:approved' => "Toutes les images ont été approuvées",
	'gallery:upload:approved:message' => "Une ou plusieurs images chargées dans l'album %s ont été approuvées",

	'gallery:image:thumb:reset' => "Refaire les miniatures",
	'gallery:thumb:reset:success' => "Miniatures refaites",
	'gallery:thumb:reset:error' => "Il y a eu un problème pendant qu'on refaisait les miniatures",

	'gallery:tagger:instructions' => "Pour créer une étiquette, sélectionner une zone sur la photo",

	'edit:plugin:hypegallery:params[album_river]' => "Ajouter les mises à jour aux albums au fil d'activité",
	'edit:plugin:hypegallery:hint:album_river' => "Enclencher cette option ajoutera des entrées dans le fil consécutivement aux ajouts d'images",

	'edit:plugin:hypegallery:params[favorites]' => "Mettre l'onglet Favoris sur le tableau de bord",
	'edit:plugin:hypegallery:hint:favorites' => "Enclencher cette option ajoutera un onglet Favoris pour les images que vous aimez",

	'edit:plugin:hypegallery:params[interface_location]' => "Enclencher l'interface de localisation",
	'edit:plugin:hypegallery:hint:interface_location' => "Enclencher cette option ajoutera les champs de localisation aux images, et une carte (si l'interface vers les cartes est enclenchée)",

	'edit:plugin:hypegallery:params[interface_calendar]' => "Enclencher les dates et l'heure",
	'edit:plugin:hypegallery:hint:interface_calendar' => "Enclencher cette option ajoutera le champ de date aux images, et un calendrier (si l'interface vers le calendrier est enclenchée)",

	'edit:plugin:hypegallery:params[copyrights]' => "Ajouter des informations sur les copyrights aux albums et images",
	'edit:plugin:hypegallery:hint:copyrights' => "Enclencher cette option ajoutera les champs pour les copyrights aux images",

	'edit:plugin:hypegallery:params[categories]' => "Enclencher les catégories",
	'edit:plugin:hypegallery:hint:categories' => "Enclencher cette option ajoutera les champs de catégories aux images",

	'edit:plugin:hypegallery:params[collaborative_albums]' => "Enclencher les albums collaboratifs",
	'edit:plugin:hypegallery:hint:collaborative_albums' => "Enclencher cette option permettra aux utilisateurs de créer des albums collaboratifs; les autres utilisateurs seront invités à ajouter des images aux albums collaboratifs qui seront approuvés par le créateur de l'album",

	'edit:plugin:hypegallery:params[group_albums]' => "Enclencher les albums de groupes",
	'edit:plugin:hypegallery:hint:group_albums' => "Enclencher cette option ajoutera la possibilité de créer des albums dans les groupes",

	'edit:plugin:hypegallery:params[avatars]' => "Les utilisateurs peuvent employer les images comme avatars",
	'edit:plugin:hypegallery:hint:avatars' => "Enclencher cette option permettra aux utilisateurs d'employer les images comme avatar",

	'edit:plugin:hypegallery:params[tagging]' => "Permettre les étiquettes sur des zones d'images",
	'edit:plugin:hypegallery:hint:tagging' => "Enclencher cette option permettra aux utilisateurs de placer des étiquettes sur des zones d'images",

	'edit:plugin:hypegallery:params[downloads]' => "Permettre les téléchargements",
	'edit:plugin:hypegallery:hint:downloads' => "Enclencher cette option permettra aux utilisateurs de télécharger les images sur leur ordinateur personnel",

	'edit:plugin:hypegallery:params[public_downloads]' => 'Permettre les téléchargements publics',
	'edit:plugin:hypegallery:hint:public_downloads' => 'Si les téléchargements sont autorisés, accepter cette option permettra à tout le monde de télécharger vos photos.',

	'edit:plugin:hypegallery:params[exif]' => "Enclencher EXIF",
	'edit:plugin:hypegallery:hint:exif' => "Enclencher cette option permettra l'analyse des informations EXIF des images le cas échéant",

	'album:untitled' => "Sans titre",
	'gallery:filedrop:instructions' => "Glissez et déposez vos fichiers sur cette zone ou %s de votre ordinateur",
	'gallery:filedrop:fallback' => "sélectionner",
	'gallery:filedrop:browsernotsupported' => "Votre navigateur ne permet pas d'utiliser l'option de glisser-déposer",
	'gallery:filedrop:filetoolarge' => "Une ou plusieurs images sont plus grandes que la taille maximum autorisée",
	'gallery:filedrop:filetypenotallowed' => "Une ou plusieurs images sont d'un type non permis",

	'gallery:slideshow' => "Démarrer le diaporama",
	
	'gallery:slideshow:loading' => "Chargement du diaporama...",
	'gallery:slideshow:pager' => "%s de %s",

	'gallery:list:empty' => "Il n'y a rien à montrer",
	'gallery:inthisphoto' => "Dans cette photo:",
	'gallery:image:tag:create' => 'Ajouter des étiquettes',
	'gallery:image:tag:instructions' => "Pour créer une nouvelle étiquette, définissez une zone sur l'image, remplissez le ou les champs Mots clés et Amis dans le formulaire, et presser Enregistrer.",
	'gallery:image:tag:keyword' => 'Mots clés',
	'gallery:image:tag:friend' => 'Amis',
	
	'gallery:inthisphoto:none' => 'Aucune étiquette sur cette photo',

	'gallery:user:tagged' => "Vous avez été identifié dans une photo",
	'gallery:user:tagged:message' => "Vous avez été identifié dans une photo. Vous pouvez voir cette photo et la supprimer ici: %s",
	'gallery:phototag:river' => "%s repéré %s dans %s",
	'hj:gallery:phototag:river' => "%s repéré %s dans %s", // legacy

	'gallery:exif' => "Entêtes EXIF",
	'gallery:exif:resolution' => "%s dpi",

	'exif.Model' => "Camera",
	'exif.LensInfo' => "Information sur l'objectif",
	'exif.LensModel' => "Modèle de l'objectif",
	'exif.LensSerialNumber' => "No de série de l'objectif",
	'exif.Copyright' => "Copyright",
	'exif.ImageDescription' => "Description",
	'exif.Software' => "Logiciel",
	'exif.ModifyDate' => "Date et heure (modification)",
	'exif.XResolution' => "Résolution X",
	'exif.YResolution' => "Résolution Y",
	'exif.ResolutionUnit' => "Unité de résolution",
	'exif.ResolutionUnit.1' => "Pas d'unité absolu de mesure",
	'exif.ResolutionUnit.2' => "Pouce",
	'exif.ResolutionUnit.3' => "Centimètre",

	'exif.ExposureTime' => "Exposition (s)",
	'exif.FNumber' => "Ouverture (F)",
	'exif.ApertureValue' => "Valeur d'ouverture",
	'exif.BrightnessValue' => "Luminosité",
	'exif.ExposureBiasValue' => "Biais d'exposition",
	'exif.MaxApertureValue' => "Ouverture maximum",
	'exif.SubjectDistance' => "Distance au sujet (m)",
	'exif.SubjectArea' => "Surface du sujet",
	'exif.SubjectLocation' => "Localisation du subjet",
	
	'exif.ExposureProgram' => "Programme d'exposition",
	'exif.ExposureProgram.0' => "Indéfini",
	'exif.ExposureProgram.1' => "Manuel",
	'exif.ExposureProgram.2' => "Normal",
	'exif.ExposureProgram.3' => "Priorité à l'ouverture",
	'exif.ExposureProgram.4' => "Priorité à la durée d'exposition",
	'exif.ExposureProgram.5' => "Programme créatif (biais vers la profondeur de champ)",
	'exif.ExposureProgram.6' => "Programme action (biais vers une durée d'exposition réduite)",
	'exif.ExposureProgram.7' => "Mode Portrait (plan rapproché avec décor arrière flou)",
	'exif.ExposureProgram.8' => "More Paysage (plan arrière mis au point)",

	'exif.ComponentsConfiguration' => "Configuration des composants",
	'exif.ComponentsConfiguration.0' => "N'existe pas",
	'exif.ComponentsConfiguration.1' => "Y",
	'exif.ComponentsConfiguration.2' => "Cb",
	'exif.ComponentsConfiguration.3' => "Cr",
	'exif.ComponentsConfiguration.4' => "R",
	'exif.ComponentsConfiguration.5' => "G",
	'exif.ComponentsConfiguration.6' => "B",
	'exif.ComponentsConfiguration.Other' => "Autre",

	'exif.MeteringMode' => "Mode de mesure",
	'exif.MeteringMode.0' => "Inconnu",
	'exif.MeteringMode.1' => "Moyenne",
	'exif.MeteringMode.2' => "Moyenne centrée",
	'exif.MeteringMode.3' => "Spot",
	'exif.MeteringMode.4' => "Spots multiples",
	'exif.MeteringMode.5' => "Gabarit",
	'exif.MeteringMode.6' => "Partielle",
	'exif.MeteringMode.255' => "Autre",

	'exif.LightSource' => "Source de lumière",
	'exif.LightSource.0' => "Inconnue",
	'exif.LightSource.1' => "Lumière du jour",
	'exif.LightSource.2' => "Fluorescent",
	'exif.LightSource.3' => "Tungsten (lampe à incandescence)",
	'exif.LightSource.4' => "Flash",
	'exif.LightSource.9' => "Beau temps",
	'exif.LightSource.10' => "Temps couvert",
	'exif.LightSource.11' => "Ombre",
	'exif.LightSource.12' => "Lumière du jour fluorescent (D 5700 - 7100K)",
	'exif.LightSource.13' => "Fluorescent Jour (N 4600 - 5400K)",
	'exif.LightSource.14' => "Fluorescent Froid (W 3900 - 4500K)",
	'exif.LightSource.15' => "Fluorescent Chaud (WW 3200 - 3700K)",
	'exif.LightSource.17' => "Lumière Standard A",
	'exif.LightSource.18' => "Lumière Standard B",
	'exif.LightSource.19' => "Lumière Standard C",
	'exif.LightSource.20' => "D55",
	'exif.LightSource.21' => "D65",
	'exif.LightSource.22' => "D75",
	'exif.LightSource.23' => "D50",
	'exif.LightSource.24' => "ISO studio tungsten",
	'exif.LightSource.255' => "Autre source de lumière",

	'exif.Flash' => "Flash",
	'exif.Flash.0' => "Non enclenché",
	'exif.Flash.1' => "Enclenché",
	'exif.Flash.5' => "Lumière de retour de flash non détectée",
	'exif.Flash.7' => "Lumière de retour de flash détectée",
	'exif.Flash.9' => "Flash déclenché, mode flash mode",
	'exif.Flash.13' => "Flash déclenché, mode flash obligatoire, lumière de retour non détectée",
	'exif.Flash.15' => "Flash déclenché, mode flash obligatoire, lumière de retour détectée",
	'exif.Flash.16' => "Flash non déclenché, mode flash obligatoire",
	'exif.Flash.24' => "Flash non déclenché, mode automatique",
	'exif.Flash.25' => "Flash déclenché, mode automatique",
	'exif.Flash.29' => "Flash déclenché, mode automatique, lumière de retour non détectée",
	'exif.Flash.31' => "Flash déclenché, mode automatique, lumière de retour détectée",
	'exif.Flash.32' => "Pas de fonction de flash",
	'exif.Flash.65' => "Flash déclenché, mode de réduction des yeux rouges",
	'exif.Flash.69' => "Flash déclenché, mode de réduction des yeux rouges, lumière de retour non détectée",
	'exif.Flash.71' => "Flash déclenché, mode de réduction des yeux rouges, lumière de retour détectée",
	'exif.Flash.73' => "Flash déclenché, mode flash obligatoire, mode de réduction des yeux rouges",
	'exif.Flash.77' => "Flash déclenché, mode flash obligatoire, mode de réduction des yeux rouges, lumière de retour non détectée",
	'exif.Flash.79' => "Flash déclenché, mode flash obligatoire, mode de réduction des yeux rouges, lumière de retour détectée",
	'exif.Flash.89' => "Flash déclenché, mode automatique, mode de réduction des yeux rouges",
	'exif.Flash.93' => "Flash déclenché, mode automatique, return light not detected, mode de réduction des yeux rouges",
	'exif.Flash.95' => "Flash déclenché, mode automatique, return light detected, mode de réduction des yeux rouges",

	'exif.FlashEnergy' => "Energie du flash",
	'exif.SpatialFrequencyResponse' => "Réponse en espace de fréquence",
	'exif.FocalPlaneXResolution' => "Plan focal X-Résolution",
	'exif.FocalPlaneYResolution' => "Plan focal Y-Résolution",
	
	'exif.FocalPlaneResolutionUnit' => "Unité de résolution du plan focal",
	'exif.FocalPlaneResolutionUnit.1' => "Pas d'unité de mesure",
	'exif.FocalPlaneResolutionUnit.2' => "Pouce",
	'exif.FocalPlaneResolutionUnit.3' => "Centimetre",
	
	'exif.ISO' => "Vitesse ISO",
	'exif.ISOSpeedRatings' => "Vitesse ISO",

	'exif.SensitivityType' => "Type de sensibilité",
	'exif.SpectralSensitivity' => "Sensibilité spectrale",
	'exif.RecommendedExposureIndex' => "Indice d'exposition recommandé",
	'exif.ExifVersion' => "Version de EXIF",
	'exif.FlashpixVersion' => "Version de Flashpix",

	'exif.DateTime' => "Date et heure",
	'exif.DateTimeOriginal' => "Date et heure (Original)",
	'exif.DateTimeDigitized' => "Date et heure (Digitalisé)",
	'exif.SubsecTime' => "Date et heure avec valeurs sous la seconde",
	'exif.SubsecTimeOriginal' => "Date et heure avec valeurs sous la seconde: Valeur originale",
	'exif.SubsecTimeDigitized' => "Date et heure avec valeurs sous la seconde: Valeur digitalisée",

	'exif.CompressedBitsPerPixel' => "Bit par pixel compressé",
	'exif.ShutterSpeedValue' => "Durée d'exposition",
	'exif.FocalLength' => "Distance focale",
	'exif.UserComment' => "Commentaire",
	'exif.ColorSpace' => "Espace colorimétrique",
	'exif.PixelXDimension' => "Pixel dimension X",
	'exif.PixelYDimension' => "Pixel dimension Y",
	'exif.ExposureIndex' => "Indice d'exposition",

	'exif.SensingMethod' => "Mesure",
	'exif.SensingMethod.1' => "Indéfini",
	'exif.SensingMethod.2' => "Un capteur de couleur",
	'exif.SensingMethod.3' => "Deux capteurs de couleur",
	'exif.SensingMethod.4' => "Trois capteurs de couleur",
	'exif.SensingMethod.5' => "Capteur couleur séquentiel",
	'exif.SensingMethod.7' => "Capteur tri-linéaire",
	'exif.SensingMethod.8' => "Capteur séquentiel linéaire",

	'exif.SceneType' => "Type de scène",

	'exif.CFAPattern' => "Mode CFA",
	'exif.CFAPattern.0' => "Rouge",
	'exif.CFAPattern.1' => "Vert",
	'exif.CFAPattern.2' => "Blue",
	'exif.CFAPattern.3' => "Cyan",
	'exif.CFAPattern.4' => "Magenta",
	'exif.CFAPattern.5' => "Jaune",
	'exif.CFAPattern.6' => "Blanc",

	'exif.CustomRendered' => "Rendu",
	'exif.CustomRendered.0' => "Normal",
	'exif.CustomRendered.1' => "Particulier",

	'exif.ExposureMode' => "Réglages d'exposition",
	'exif.ExposureMode.0' => "Automatique",
	'exif.ExposureMode.1' => "Manuelle",
	'exif.ExposureMode.2' => "Fourchette",

	'exif.WhiteBalance' => "Balance des blancs",
	'exif.WhiteBalance.0' => "Automatique",
	'exif.WhiteBalance.1' => "Manuelle",

	'exif.DigitalZoomRatio' => "Rapport de Zoom Digital",
	'exif.FocalLengthIn35mmFilm' => "Distance focale en équivalent 35mm",

	'exif.SceneCaptureType' => "Type de scène",
	'exif.SceneCaptureType.0' => "Standard",
	'exif.SceneCaptureType.1' => "Paysage",
	'exif.SceneCaptureType.2' => "Portrait",
	'exif.SceneCaptureType.3' => "Nuit",

	'exif.GainControl' => "Contrôle du gain",
	'exif.GainControl.0' => "Aucun",
	'exif.GainControl.1' => "Faible gain",
	'exif.GainControl.2' => "Gain important",
	'exif.GainControl.3' => "Faible diminution",
	'exif.GainControl.4' => "Diminution importante",

	'exif.Contrast' => "Contraste",
	'exif.Contrast.0' => "Normal",
	'exif.Contrast.1' => "Faible",
	'exif.Contrast.2' => "Elevé",

	'exif.Saturation' => "Saturation",
	'exif.Saturation.0' => "Normale",
	'exif.Saturation.1' => "Basse",
	'exif.Saturation.2' => "Haute",

	'exif.Sharpness' => "Netteté",
	'exif.Sharpness.0' => "Normal",
	'exif.Sharpness.1' => "Doux",
	'exif.Sharpness.2' => "Dur",

	'exif.DeviceSettingDescription' => "Description des paramètres de l'appareil",

	'exif.SubjectDistanceRange' => "Distance au sujet",
	'exif.SubjectDistanceRange.0' => "Inconnu",
	'exif.SubjectDistanceRange.1' => "Macro",
	'exif.SubjectDistanceRange.2' => "Vue de près",
	'exif.SubjectDistanceRange.3' => "Vue de loin",

	'exif.ImageUniqueID' => "ID Unique Image",
	
	'exif.GPSVersionID' => "GPS ID Version",
	'exif.GPSAltitude' => "Altitude",
	'exif.GPSAltitudeRef' => "Altitude Ref",
	'exif.GPSAltitudeRef.0' => "Au dessus du niveau de la mer",
	'exif.GPSAltitudeRef.1' => "Sous le niveau de la mer",
	'exif.GPSLatitude' => "Latitude",
	'exif.GPSLongitude' => "Longitude",
	

	// widgets
	'gallery:widget:photostream' => "Fil de photos",
	'gallery:widget:photostream:desc' => "Un fil des dernières photos",
	'gallery:widget:albums' => "Albums",
	'gallery:widget:albums:desc' => "Une liste des derniers albums",
	'gallery:widget:more' => "Voir tout",
	'gallery:widget:limit' => "Nombre d'images à montrer",
	'gallery:widget:none' => "Aucun élément à afficher",


	// downloads
	'gallery:download:error:disabled' => 'Les téléchargements ne sont pas autorisés. Veuillez contacter le gestionnaire du site.',
	'gallery:download:error:disabled_public' => 'Les téléchargements publics ne sont pas autorisés. Veuillez vous identifier.',
	
	// embeds
	'embed:albumimages' => 'Album photo',
	
);

add_translation("fr", $french);
