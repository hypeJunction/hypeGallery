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
    border-top:2px solid #e8e8e8;
	border-bottom:2px solid #e8e8e8;
    padding:10px;
}

.elgg-module-image {
	border-bottom:2px solid #e8e8e8;
	padding:10px;
}

#hj-gallery-image-edit {
	margin:10px auto;
	padding:20px;
	width:800px;
}
.hj-gallery-album-images > li {
    margin:15px 2px;
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
.hj-album-list > li {
    margin:5px;
    text-align:center;
    font-size:11px;
    padding:7px 15px;
    width:105px;
	height:200px;
	overflow:hidden;
    vertical-align:top;
    cursor:pointer;
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

.hj-album-list {
    text-align:center;
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
}

#hj-gallery-tagger-form {
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