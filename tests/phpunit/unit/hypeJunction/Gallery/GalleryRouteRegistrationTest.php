<?php

declare(strict_types=1);

namespace hypeJunction\Gallery\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Regression guard for the Elgg 7 gallery routing gap.
 *
 * hypeGallery historically wired every front-end URL through a legacy page
 * handler (`const PAGEHANDLER = 'gallery'`). Page handlers were replaced by the
 * declarative `routes` key / RouteRegistrationService and no longer exist on
 * Elgg 7, so unless every linked `gallery/*` path is registered as a route the
 * whole plugin 404s: the dashboard, `gallery/group/{guid}` (still linked from
 * group profiles), and every album/image `gallery/view/{guid}` page.
 *
 * The plugin still ships `pages/gallery/**` scripts and still generates
 * `gallery/*` URLs from its entity classes and menu hooks, so it MUST carry the
 * matching route registration. These tests fail until it does.
 */
final class GalleryRouteRegistrationTest extends TestCase
{
    /**
     * Paths named by the migration gap that must resolve on Elgg 7.
     * Both are concretely linked in shipped code (entity getURL + group menu).
     */
    private const REQUIRED_LINKED_PATHS = [
        'gallery/view',  // hjAlbum::getURL / hjAlbumImage::getURL
        'gallery/group', // lib/hooks.php owner_block group menu href
    ];

    private static function pluginRoot(): string
    {
        // .../tests/phpunit/unit/hypeJunction/Gallery/ -> plugin root is 5 up
        // (tests, phpunit, unit, hypeJunction, Gallery).
        return \dirname(__DIR__, 5);
    }

    private static function manifestSource(): string
    {
        $manifest = self::pluginRoot() . '/elgg-plugin.php';
        return is_file($manifest) ? (string) file_get_contents($manifest) : '';
    }

    /** @return list<string> */
    private static function sourceFiles(): array
    {
        $out = [];
        foreach (['classes', 'lib'] as $sub) {
            $base = self::pluginRoot() . '/' . $sub;
            if (!is_dir($base)) {
                continue;
            }
            $it = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($base, \FilesystemIterator::SKIP_DOTS)
            );
            foreach ($it as $f) {
                $path = $f->getPathname();
                if (str_ends_with($path, '.php')) {
                    $out[] = $path;
                }
            }
        }
        return $out;
    }

    /**
     * Distinct `gallery/<segment>` prefixes the plugin actually links to,
     * scraped from elgg_normalize_url(...) calls and `href =>` menu items.
     * Action URLs (action/gallery/...) are excluded — those are the `actions`
     * key's job, not the router's.
     *
     * @return list<string>
     */
    private static function linkedGalleryPrefixes(): array
    {
        $prefixes = [];
        $re = '#(?:elgg_normalize_url\(\s*["\']|["\']href["\']\s*=>\s*["\'])(gallery/[a-z_]+)#';
        foreach (self::sourceFiles() as $file) {
            if (preg_match_all($re, (string) file_get_contents($file), $m)) {
                foreach ($m[1] as $prefix) {
                    $prefixes[$prefix] = true;
                }
            }
        }
        return array_keys($prefixes);
    }

    /**
     * All route `path` strings declared inside the manifest `routes` block.
     *
     * @return list<string>
     */
    private static function registeredRoutePaths(): array
    {
        $src = self::manifestSource();
        if ($src === '' || !preg_match("/['\"]routes['\"]\s*=>\s*\[(.*)\n\s*\],/sU", $src, $block)) {
            return [];
        }
        preg_match_all("/['\"]path['\"]\s*=>\s*['\"]([^'\"]+)['\"]/", $block[1], $m);
        return $m[1];
    }

    public function testManifestDeclaresRoutesKey(): void
    {
        $src = self::manifestSource();
        $this->assertNotSame('', $src, 'elgg-plugin.php is required on Elgg 7');

        $this->assertMatchesRegularExpression(
            "/['\"]routes['\"]\s*=>\s*\[/",
            $src,
            "elgg-plugin.php declares no 'routes' key. The legacy 'gallery' page "
            . "handler (const PAGEHANDLER) does not exist on Elgg 7, so every "
            . "gallery/* path 404s. Register the gallery routes via the 'routes' "
            . "key (or elgg_register_route in Bootstrap::boot())."
        );
    }

    public function testEveryLinkedGalleryPathHasRoute(): void
    {
        $linked = self::linkedGalleryPrefixes();
        // Guard: if the plugin ever stops linking gallery/* URLs this test is
        // meaningless — surface that rather than passing vacuously.
        $this->assertNotEmpty(
            $linked,
            'Expected the plugin to link gallery/* URLs from its entity classes / menu hooks'
        );

        $registered = self::registeredRoutePaths();

        $missing = [];
        foreach ($linked as $prefix) {
            $covered = false;
            foreach ($registered as $path) {
                if (str_contains(ltrim($path, '/'), $prefix)) {
                    $covered = true;
                    break;
                }
            }
            if (!$covered) {
                $missing[] = $prefix . '/* — linked in code but no matching route registered (404 on Elgg 7)';
            }
        }

        $this->assertSame(
            [],
            $missing,
            "Linked gallery paths without a registered Elgg 7 route:\n" . implode("\n", $missing)
        );
    }

    public function testGapNamedPathsAreRouted(): void
    {
        $registered = self::registeredRoutePaths();

        $missing = [];
        foreach (self::REQUIRED_LINKED_PATHS as $prefix) {
            $covered = false;
            foreach ($registered as $path) {
                if (str_contains(ltrim($path, '/'), $prefix)) {
                    $covered = true;
                    break;
                }
            }
            if (!$covered) {
                $missing[] = $prefix;
            }
        }

        $this->assertSame(
            [],
            $missing,
            "Gap-named gallery paths still unrouted on Elgg 7 (group profiles link "
            . "dead " . self::REQUIRED_LINKED_PATHS[1] . "/{guid}; albums resolve to "
            . self::REQUIRED_LINKED_PATHS[0] . "/{guid}):\n- " . implode("\n- ", $missing)
        );
    }

    public function testDashboardRouteRegistered(): void
    {
        // The dashboard is the plugin's landing listing (pages/gallery/dashboard/*).
        // It needs a route whose path is the bare gallery root or a dashboard
        // sub-listing (all/owner/friends/group/site/groups/favorites/container).
        $registered = self::registeredRoutePaths();

        $hasDashboard = false;
        foreach ($registered as $path) {
            $p = ltrim($path, '/');
            if (preg_match('#^gallery(/(all|owner|friends|group|groups|site|favorites|container)(/.*)?)?$#', $p)) {
                $hasDashboard = true;
                break;
            }
        }

        $this->assertTrue(
            $hasDashboard,
            'No gallery dashboard route registered — the gallery landing page 404s on Elgg 7. '
            . 'Register a route for the bare "gallery" root (or a gallery/{filter} dashboard listing).'
        );
    }
}
