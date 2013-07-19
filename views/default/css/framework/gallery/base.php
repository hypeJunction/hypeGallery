<?php if (FALSE) : ?><style type="text/css"><?php endif; ?>
<?php
$path = elgg_get_site_url() . 'mod/hypeGallery/graphics/';
?>

	.elgg-menu-photostream {
		text-align: right;
		margin: 15px 10px 0;
	}
	.elgg-menu-photostream > li.elgg-state-selected a {
		color: #fff;
		background: #666;
	}
	.elgg-menu-photostream > li a {
		color: #666;
		padding: 5px 10px;
	}

	.gallery-photostream {
		-moz-column-count: 2;
		-moz-column-gap: 10px;
		-webkit-column-count: 2;
		-webkit-column-gap: 10px;
		column-count: 2;
		column-gap: 10px;
	}
	.gallery-photostream > li {
		vertical-align:top;
	}
	.gallery-album-cover {
		position: relative;
		padding: 5px;
		border:1px solid #e8e8e8;
		margin: 10px;
		width:325px;
	}
	.gallery-album-cover-placeholder {
		background:#ccc url(<?php echo $path ?>placeholder.png) 50% 50% no-repeat;
		background-size:40%;
		opacity:0.6;
	}
	
	.gallery-album-author {
		position: absolute;
		top: 190px;
		right: 45px;
		border: 1px solid #fff;
		padding: 0px;
		background: #000;
		height: 30px;
		width: 30px;
	}
	.gallery-album-author img {
		width: 30px;
		height: 30px;
	}
	.gallery-album-title {
		padding:8px 0px 5px 10px;
		font-weight:bold;
		font-size:14px;
		max-width:225px;
		display:inline-block;
	}

	.gallery-album-count {
		position:absolute;
		top:190px;
		right:10px;
		background:#000;
		color:#fff;
		padding:5px 10px;
		font-weight:bold;
		border:1px solid #fff;

	}
	.gallery-album-info-link {
		display: inline-block;
		margin: 5px;
	}
	.gallery-media-summary {
		padding: 3px;
		border: 1px solid #e8e8e8;
		margin: 10px;
		width:325px;
	}
	.gallery-media-title {
		padding: 8px 0 5px 10px;
		font-weight: bold;
		font-size: 14px;
		display: inline-block;
		max-width: 185px;
	}
	.gallery-media-author {
		position: absolute;
		top: -15px;
		width: 40px;
		height: 40px;
		right: 5px;
		border: 1px solid #fff;
	}
	.gallery-media-meta {
		position: relative;
	}
	.gallery-media-info-link {
		display: inline-block;
		margin: 5px;
	}
	.gallery-media-album {
		position: absolute;
		top: -15px;
		border: 1px solid #fff;
		right: 50px;
	}

	.gallery-media-meta-details > li {
		display: block;
		padding: 3px 0 3px 10px;
	}
	.gallery-media-meta-details .elgg-menu-entity {
		float: none;
		margin: 0;
		padding: 0;
		height: auto;
	}
	.gallery-media-meta-details .elgg-menu-entity > li {
		margin: 1px;
		padding: 5px 10px;
		background: #f4f4f4;
	}
	.gallery-media-meta-details .elgg-menu-entity > li:hover {
		background: #f1f1f1;
	}
	.gallery-media-meta-details .elgg-menu-entity > li a {
		display: block;
	}
	.gallery-media-meta-details > li:last-child {
		margin-top: 10px;
		padding: 0;
	}


<?php if (FALSE) : ?></style><?php endif; ?>