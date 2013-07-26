<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>

	elgg.provide('framework');
	elgg.provide('framework.gallery');
	elgg.provide('framework.gallery.leaflet');

	framework.gallery.leaflet.tilelayer_uri = '<?php echo HYPEGALLERY_TILELAYER_URI ?>';
	framework.gallery.leaflet.tilelayer_attrib = '<?php echo HYPEGALLERY_TILELAYER_ATTR ?>';

	framework.gallery.leaflet.init = function() {

		$('.leaflet-map-media')
		.each(function() {
			var lat = parseFloat($(this).data('lat'));
			var lng = parseFloat($(this).data('long'));

			var latlng = new L.LatLng(lat, lng);
			var map = L.map($(this).attr('id')).setView(latlng, 13);
			L.tileLayer(framework.gallery.leaflet.tilelayer_uri, {
				attribution: framework.gallery.leaflet.tilelayer_attrib,
				maxZoom: 18
			}).addTo(map);
			var marker = L.marker(latlng).addTo(map);
		});

		$('.leaflet-map-media-objects')
		.each(function() {

			var bounds = [];
			var markers = [];

			var $map = $(this).find('.leaflet-map').eq(0);
			var $media = $(this).find('.media-objects').children('li');

			$media.each(function() {
				var $elem = $(this);

				var latlng = new L.LatLng($elem.data('lat'), $elem.data('long'));
				bounds.push(latlng);

				var markerIcon = L.AwesomeMarkers.icon({
					icon: 'photo',
					color: 'blue'
				})
				
				var marker = L.marker(latlng);
				markers.push(marker);
			})


			var map = L.map($map.attr('id')).fitBounds(bounds);

			L.tileLayer(framework.gallery.leaflet.tilelayer_uri, {
				attribution: framework.gallery.leaflet.tilelayer_attrib
			}).addTo(map);

			L.layerGroup(markers).addTo(map);
		})
	
	}

	elgg.register_hook_handler('init', 'system', framework.gallery.leaflet.init);

<?php if (FALSE) : ?></script><?php
endif;
?>
