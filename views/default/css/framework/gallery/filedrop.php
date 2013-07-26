<?php
$graphics_url = elgg_get_site_url() . 'mod/hypeGallery/graphics/';
?>
<?php if (FALSE) : ?>
	<style type="text/css">
	<?php endif; ?>

	.gallery-filedrop {
		position: relative;
		min-height: 100px;
		overflow: hidden;
		border:2px dashed #e0e0e0;
		vertical-align: middle;
		background:#fff;
		margin-bottom:20px;
	}

	.gallery-filedrop-buttonbank {
		text-align:right;
		display:none;
	}
	.gallery-filedrop-message{
		font-size: 15px;
		display: block;
		padding:40px 0;
		width:100%;
		text-align:center;
		color:#666;
	}

	.gallery-filedrop-preview {
		width:120px;
		height: 120px;
		display:inline-block;
		margin: 20px;
		position: relative;
		text-align: center;
		vertical-align:top;
	}

	.gallery-filedrop-preview img {
		max-width: 100px;
		max-height:100px;
		border:3px solid #fff;
		display: block;

		box-shadow:0 0 2px #000;
	}

	.gallery-filedrop-imageholder {
		display: inline-block;
		position:relative;
	}

	.gallery-filedrop-queue .elgg-state-uploaded,
	.gallery-filedrop-queue .elgg-state-failed {
		position: absolute;
		top:10px;
		right:10px;
		height:100%;
		width:100%;
		background: url('<?php echo $graphics_url ?>uploaded.png') no-repeat 50% 50% rgba(255,255,255,0.2);
		display: none;
		width:48px;
		height:48px;
	}

	.gallery-filedrop-queue .elgg-state-failed {
		background: url('<?php echo $graphics_url ?>failed.png') no-repeat 50% 50% rgba(255,255,255,0.2);
	}
	
	.gallery-filedrop-preview.elgg-state-complete .elgg-state-uploaded{
		display: block;
	}

	/*-------------------------
		Progress Bars
	--------------------------*/

	.gallery-filedrop-progressholder{
		position: absolute;
		background-color: #e8e8e8;
		height: 15px;
		width: 275px;
		bottom: 0;


		margin: 11px;
		border: 1px solid #fff;
		box-shadow: 0px 0px 4px #fff;
	}

	.gallery-filedrop-progress {
		background-color: blueviolet;
		position: absolute;
		height:100%;
		left:0;
		width:0;

		-moz-transition:0.25s;
		-webkit-transition:0.25s;
		-o-transition:0.25s;
		transition:0.25s;
	}

	.gallery-filedrop-preview.elgg-state-complete .gallery-filedrop-progress{
		width:100% !important;
	}

	.gallery-filedrop-queue > .gallery-media-summary {
		display: inline-block;
		width: 300px;
		vertical-align: top;
		position: relative;
	}
	.gallery-filedrop-queue > .gallery-media-summary .gallery-album-image-placeholder {
		position: relative;
		height: 200px;
		width: 300px;
		overflow: hidden;
		line-height: 200px;
		vertical-align: middle;
	}
	.gallery-filedrop-queue > .gallery-media-summary .gallery-album-image-placeholder img {
	}
	.gallery-filedrop-queue > .gallery-media-summary .gallery-album-image-placeholder .elgg-ajax-loader {
		position: absolute;
		z-index: 200;
		top: 50%;
		right: 50%;
		left: 50%;
	}
	.gallery-filedrop-queue {
		text-align: center;
	}

	.gallery-filedrop-queue .gallery-item-in-bulk {
		text-align: left;
		font-size: 0.8em;
		border: 1px solid #fff;
		margin-top: 3px;
		padding: 5px 10px;
		background: #eee;
	}
	.gallery-filedrop-queue .gallery-item-in-bulk .gallery-media-title {
		padding: 0;
	}
	.gallery-filedrop-queue .gallery-item-in-bulk .gallery-media-extras {
		margin-top: 5px;
		padding-top: 5px;
		border-top: 1px solid #fff;
	}