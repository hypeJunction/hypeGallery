.hj-gallery-page.elgg-module-aside .elgg-head {
    height:25px;
    line-height:25px;
}
.hj-gallery-page.elgg-module-aside .elgg-body {
    padding:5px 0;
}

.elgg-module-albums {
	width:93%;
	float:none;
	margin:0 auto;
	padding-bottom:10px;
}
.elgg-module-album
{
    padding:20px;
	margin:10px;
	background:#fff;
	border:1px solid #e8e8e8;
}

.elgg-module-image {
	padding:20px;
	margin:10px;
	background:#fff;
	border:1px solid #e8e8e8;
}

#hj-gallery-image-edit {
	margin:10px auto;
	padding:20px;
	width:800px;
}
.hj-gallery-album-images {
	border:0;
	margin:0;
}
.hj-gallery-album-images > li {
    margin:0;
	border:0;
	float:left;
}
li.hj-gallery-imageplaceholder {
    margin:20px 10px 20px 0;
}
.hj-gallery-addimage-placeholder {
    float:left;
    width:96px;
    height:96px;
    display:block;
    text-align:center;
    vertical-align:middle;
    border:5px dashed #ddd;
}
.hj-album-list,
.hj-album-gallery {
	border:0;
}

.hj-album-list > li,
.hj-album-gallery > li {
	border:0;
	padding:10px;
	border:1px solid #e8e8e8;
	margin:3px 0;
}

.hj-album-gallery > li {
	margin-right:10px;
}

.hj-album-list .elgg-image-block > .elgg-image {
	width:158px;
	padding:0 5px 10px;
	text-align:center;
	border:1px solid #e8e8e8;
	font-size:0.9em;
	background:#f4f4f4;
}

.hj-album-list img {
    margin:3px 0;
    border:1px solid #e8e8e8;
}
.hj-album-title {
    font-weight:bold;
    margin:1px 0 4px;
}
.hj-album-subtitle {
    font-size:11px;
}
.hj-album-description {
    font-size:11px;
    color:#666;
}

.hj-gallery-addimage-placeholder > span {
    font-size:15px;
    line-height:96px;
    text-decoration:none;
    color:#aaa;
    font-weight:bold;
}

.hj-gallery-addimage-placeholder > span:hover {
    text-decoration:none;
}

.elgg-menu-hjalbumimage {
	margin:10px;
}

.elgg-menu-hjalbumimage > li {
	padding:3px 0;
}

#current-hj-image {
	border-right:1px solid #ccc;
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
#hj-image-master {
	position:relative;
	width:100%;
	height:620px;
	overflow:hidden;
	margin:0;
	float:left;
	background:#000;
}
#hj-image-master-details {
	float:right;
	margin-right:10px;
	width:240px;
}
#hj-image-master .hj-file-icon-background {
	width:auto;
	position:relative;
}

#hj-image-master img {
	border:0;
	padding:0;
	position:absolute;
	left:0;
	right:0;
	margin:0 auto;
	width:100%;
}
#hj-gallery-tagger-form {
	background:#fff;
	position:absolute;
	z-index:9999;
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
	float:left;
	-moz-border-radius:8px;
	-webkit-border-radius:8px;
	border-radius:8px;
}

.hj-gallery-tags-list > li > a.hj-gallery-tags-item-title {
	padding-left:20px;
}

.hj-gallery-tags-list > li > a.hj-ajaxed-remove {
	position:absolute;
	left:3px;
}
/* Image Map CSS */

.hj-gallery-tags-map {
	border:0;
}
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
#hj-layout-main-column img.hj-gallery-album-cover-large {
	max-width:150px;
}

.hj-gallery-carousel-image-wrapper {
	text-align:center;
	background:#000;
	padding:5px;
}

img.hj-gallery-carousel-image {
	padding:0;
	border:0;
	width:94%;
}

.hj-gallery-image {
	margin-right:10px;
}