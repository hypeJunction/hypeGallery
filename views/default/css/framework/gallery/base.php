<?php if (FALSE) : ?>
	<style>
	<?php endif; ?>

	.hj-albumlist-thumbs > li,
	.hj-imagelist-thumbs > li {
		display: inline-block;
		width: 200px;
		margin: 5px;
		text-align: center;
		border: 1px solid #e8e8e8;
		vertical-align:top;
	}
	.hj-albumlist-summary > li {
		width:95%;
		padding:2%;
		display:block;
		margin:10px 0;
	}

	.hj-imagelist-summary > li {
		width:45%;
		padding:2%;
		display:inline-block;
		margin:10px 0;
		vertical-align:top;
	}
	.elgg-module-album > .elgg-head {
		padding: 10px 3px;
	}

	.elgg-module-album > .elgg-body {
	}

	.elgg-module-album-summary .elgg-image-block .elgg-image {
		width:200px;
		margin:0 20px 0 0;
		text-align:center;
	}

	.hj-imagelist-summary > li img {
		max-width:100%;
	}

	.hj-albumimage-icon {
		display:block;
	}
	
	#avatar-croppingtool {
		border-top: 1px solid #ccc;
	}
	#hj-image-cropper {
		float:left;
	}
	#hj-image-preview {
		float: left;
		position: relative;
		overflow: hidden;
		width: 100px;
		height: 100px;
	}
	#hj-image-master,
	.hj-gallery-tagger-wrapper {
		position:relative;
	}

	.hj-gallery-tag-save {
		background:#fff;
		position:absolute;
		z-index:999;
		border:1px solid #000;
		width:200px;
		padding:10px;
	}

	.hj-gallery-tags-list {
		margin:10px 5px;
	}

	.hj-gallery-tags-list > li {
		font-size:11px;
		font-weight:bold;
		padding:1px 7px;
		background:#f4f4f4;
		border:1px solid #e8e8e8;
		margin:4px;
		display:inline-block;
		width:20%;
	}

	/* Image Map CSS */
	.hj-gallery-tags-map li {
		margin:0;
		padding:0;
		list-style:none;
		border:0;
		margin:0;
	}
	.hj-gallery-tags-map li a {
		position:absolute;
		display:none;
		text-decoration:none;
		cursor:pointer;
		color:#000;
	}

	.hj-gallery-tags-map li a span {
		display:none;
		background:#fff;
		padding:5px;
		position:absolute;
		top:50px;
		left:2px;
		border:1px solid #000;
		z-index:1200;
	}
	.hj-gallery-tags-map li a:hj-gallery-tag-hover span,
	.hj-gallery-tags-map li a:hover span,
	.hj-gallery-tag-selected {
		display:block;
	}
	.hj-gallery-tags-map li a.hj-gallery-tag-hover,
	.hj-gallery-tags-map li a:hover {
		border:4px solid red;
		display:block;
	}