# hypeGallery — Plugin Architecture (Elgg 5.x)

## Summary

Image gallery plugin for Elgg. Provides photo albums (`hjAlbum`) containing images (`hjAlbumImage`) with upload, cropping, EXIF extraction, tagging, and permission controls.

## Entity Types

| Type | Subtype | Class | Description |
|------|---------|-------|-------------|
| object | hjalbum | `hypeJunction\Gallery\hjAlbum` | Photo album container |
| object | hjalbumimage | `hypeJunction\Gallery\hjAlbumImage` | Individual photo, extends `ElggFile` |

### `hjAlbum`
- `countImages()` — count contained images
- `getURL(): string` — view URL
- `getActionURL(string $action): string` — edit/delete/manage URLs
- `getIconURL(array|string $params = []): string` — delegates to cover image or first image
- `getContainedFiles(array $options = [])` — fetch contained images

### `hjAlbumImage`
- Extends `ElggFile` — stores raw image on filestore
- `getURL(): string` — view URL
- `getActionURL(string $action): string` — edit/delete/download URLs
- `getIconURL(array|string $params = []): string` — gallery icon URL (`gallery/icon/<guid>/<size>`)
- `getExif()` — read EXIF metadata from file
- `delete(bool $follow_symlinks = true): bool` — also cleans up thumbnail files on delete

## Registered Events (Elgg 5.x)

All handlers registered imperatively in `Bootstrap::boot()` → `lib/start.php` → `init()`:

| Event | Type | Handler |
|-------|------|---------|
| `init` | `system` | `\hypeJunction\Gallery\init` |
| `init` | `system` | `\hypeJunction\Gallery\init_groups` |
| `create` | `object` | `\hypeJunction\Gallery\apply_exif_tags` |
| `permissions_check` | `object` | `\hypeJunction\Gallery\permissions_check` |
| `container_permissions_check` | `object` | `\hypeJunction\Gallery\container_permissions_check` |
| `get_sql` | `access` | `\hypeJunction\Gallery\filter_access_sql` |
| `register` | `menu:entity` | `\hypeJunction\Gallery\entity_menu_setup` |
| `register` | `menu:manage_album_image` | `\hypeJunction\Gallery\manage_album_image_menu_setup` |
| `register` | `menu:owner_block` | `\hypeJunction\Gallery\owner_block_menu_setup` |
| `entity:icon:sizes` | `object` | `\hypeJunction\Gallery\entity_icon_sizes` |

## Routes (Page Handlers)

Registered declaratively in `elgg-plugin.php` via `'routes'` key (if present) or via `elgg_register_route()`. Page handler prefix: `gallery`.

Key URL patterns:
- `gallery/dashboard/site` — site-level gallery
- `gallery/dashboard/owner/<username>` — user gallery
- `gallery/view/<guid>/<slug>` — view album or image
- `gallery/edit/<guid>` — edit album
- `gallery/manage/<guid>` — manage album images
- `gallery/upload/<guid>` — upload to album
- `gallery/icon/<guid>/<size>` — icon redirect
- `gallery/download/<guid>` — image download
- `gallery/thumb/<guid>` — crop/thumbnail editor

## Actions

| Action | Access |
|--------|--------|
| `edit/object/hjalbum` | logged_in |
| `edit/object/hjalbumimage` | logged_in |
| `gallery/delete/object` | logged_in |
| `gallery/order/images` | logged_in |
| `gallery/upload` | logged_in |
| `gallery/upload/filedrop` | logged_in |
| `gallery/upload/handle` | logged_in |
| `gallery/upload/describe` | logged_in |
| `gallery/approve/image` | logged_in |
| `gallery/makeavatar` | logged_in |
| `gallery/makecover` | logged_in |
| `gallery/phototag` | logged_in |
| `gallery/thumb` | logged_in |
| `gallery/thumb_reset` | logged_in |

## Widgets

| Widget | Contexts |
|--------|---------|
| `photostream` | profile, dashboard, groups |
| `albums` | profile, dashboard, groups |

## Dependencies

| Plugin | Reason |
|--------|--------|
| `hypefilestore` | File storage layer |

## Capabilities

- `searchable`: hjalbum, hjalbumimage

## Data Migration

- `EncodeRiverMetadataAsJson` (version 2026041200) — converts serialized river metadata to JSON in `elgg_metadata` table

## Directory Structure

```
hypegallery/
├── actions/          # Action handlers (upload, edit, delete, approve, etc.)
├── classes/
│   └── hypeJunction/Gallery/
│       ├── Bootstrap.php          # PluginBootstrap — boots lib/start.php
│       ├── hjAlbum.php            # Album entity
│       ├── hjAlbumImage.php       # Image entity (extends ElggFile)
│       └── Upgrades/
│           └── EncodeRiverMetadataAsJson.php
├── docker/           # Per-plugin Elgg 5.x test stack (PHP 8.2, MySQL 8.0)
├── languages/        # i18n strings (en, fr, nl)
├── lib/
│   ├── events.php    # Event callbacks (EXIF tags on create, pagesetup)
│   ├── functions.php # Helper functions (upload, icon generation, EXIF parsing)
│   ├── hooks.php     # Event handler functions (menu setup, permissions, icon sizes)
│   ├── settings.php  # Plugin constants and gallery config
│   └── start.php     # Constants, require_once lib files, event registration
├── pages/            # Page handler PHP files
├── sass/             # Source CSS (compiled to views/default/css/)
├── tests/
│   └── phpunit/integration/   # Integration tests (20 tests)
└── views/default/    # Templates and CSS/JS views
```

## Migration Notes (4.x → 5.x)

- Hooks merged into events: `elgg_register_plugin_hook_handler()` → `elgg_register_event_handler()`, all `\Elgg\Hook` → `\Elgg\Event`
- `ElggFile::detectMimeType()` (removed 4.x) → `mime_content_type()`
- `elgg_trigger_plugin_hook()` → `elgg_trigger_event_results()`
- Embed menu item removed (embed plugin removed in Elgg 5.x)
- `EncodeRiverMetadataAsJson`: `implements Batch` → `extends AsynchronousUpgrade`
- `hjAlbum/hjAlbumImage::getURL($action)` split into `getURL(): string` + `getActionURL(string $action): string` to satisfy PHP 8.2 LSP (parent signature `getURL(): string`)
- `getIconURL($size)` updated to `getIconURL(array|string $params = []): string` to match parent
- `hjAlbumImage::delete()` signature updated to match `ElggFile::delete(bool $follow_symlinks = true): bool`
- Docker stack updated: PHP 7.4 → PHP 8.2, MySQL 5.7 → MySQL 8.0, `ELGG_SITE_URL=http://elgg/`
- Test session calls: `elgg_get_session()->setLoggedInUser()` → `_elgg_services()->session_manager->setLoggedInUser()`
