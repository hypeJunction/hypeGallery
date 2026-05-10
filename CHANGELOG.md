## [5.0.0] - 2026-04-27

### Breaking Changes

- Requires Elgg 5.x and PHP 8.2+
- Removed embed menu integration (embed plugin removed in Elgg 5.x)
- `hjAlbum::getURL($action)` split into `getURL()` (view) and `getActionURL(string $action)`
- `hjAlbumImage::getURL($action)` split into `getURL()` (view) and `getActionURL(string $action)`
- `getIconURL()` signature updated to `getIconURL(array|string $params = []): string`

### Migration

- Hooks merged into events system: all `elgg_register_plugin_hook_handler()` calls updated
- All `\Elgg\Hook` type hints replaced with `\Elgg\Event`
- `EncodeRiverMetadataAsJson` upgrade class updated to extend `AsynchronousUpgrade`
- `ElggFile::detectMimeType()` replaced with `mime_content_type()`
- Docker test stack updated to PHP 8.2 and MySQL 8.0

---

<a name="3.2.1"></a>
## [3.2.1](https://github.com/hypeJunction/hypeGallery/compare/3.2.0...v3.2.1) (2016-01-06)


### Bug Fixes

* **activate:** fix autoloader path ([d1ec47d](https://github.com/hypeJunction/hypeGallery/commit/d1ec47d))
* **cover:** album cover now fits the container ([dbe2faf](https://github.com/hypeJunction/hypeGallery/commit/dbe2faf)), closes [#58](https://github.com/hypeJunction/hypeGallery/issues/58)
* **css:** fix menu display ([d38a49b](https://github.com/hypeJunction/hypeGallery/commit/d38a49b))
* **slideshow:** fix z-index and dialog css ([22fb7ee](https://github.com/hypeJunction/hypeGallery/commit/22fb7ee)), closes [#59](https://github.com/hypeJunction/hypeGallery/issues/59)
* **thumbs:** thumbnail config is now respected on initial upload ([fb63d3d](https://github.com/hypeJunction/hypeGallery/commit/fb63d3d))



<a name="3.2.0"></a>
# [3.2.0](https://github.com/hypeJunction/hypeGallery/compare/3.1.0...v3.2.0) (2016-01-06)


### Bug Fixes

* **composer:** use default composer settings ([ebd3fa0](https://github.com/hypeJunction/hypeGallery/commit/ebd3fa0))
* **grunt:** make manifest version autoupdateable ([41c4368](https://github.com/hypeJunction/hypeGallery/commit/41c4368))
* **widgets:** photostream widget now works in group context ([9a1cf40](https://github.com/hypeJunction/hypeGallery/commit/9a1cf40))
* **widgets:** widget now works in group context ([039678f](https://github.com/hypeJunction/hypeGallery/commit/039678f))

### Features

* **grunt:** automate releases ([68e1098](https://github.com/hypeJunction/hypeGallery/commit/68e1098))



