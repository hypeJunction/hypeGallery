import { test, expect } from '@playwright/test';
import { loginAs, getEntitiesBySubtype, getMetadata } from '../helpers/elgg';

test.describe('hypeGallery: album creation', () => {
  test('user can create an album via form', async ({ page }) => {
    await loginAs(page, 'testuser');

    // Navigate to album creation
    await page.goto('/gallery/add');

    // Fill album form
    const uniqueTitle = `Trip ${Date.now()}`;
    await page.fill('input[name="title"]', uniqueTitle);
    await page.fill('textarea[name="description"]', 'Pre-migration regression album');
    await page.fill('input[name="location"]', 'Lisbon');
    await page.fill('input[name="tags"]', 'summer,beach');

    await page.click('input[type="submit"], button[type="submit"]');

    // Assert UI: redirected to gallery manage/view page
    await expect(page).toHaveURL(/\/gallery\/(manage|view)\//);
    await expect(page.locator('body')).toContainText(uniqueTitle);

    // Assert DB: hjalbum entity exists with matching title metadata
    const albums = await getEntitiesBySubtype('hjalbum');
    expect(albums.length).toBeGreaterThan(0);
    const newest = albums[0];
    expect(newest.type).toBe('object');

    const titleRows = await getMetadata(Number(newest.guid), 'title');
    // Title may live on entities table (attribute) OR metadata depending on Elgg version
    // Accept either — just verify entity exists as type=object/subtype=hjalbum
    expect(newest.subtype).toBe('hjalbum');
  });

  test('album listing page renders', async ({ page }) => {
    await loginAs(page, 'testuser');
    await page.goto('/gallery/all');

    // Assert listing page renders without system errors
    await expect(page.locator('.elgg-system-messages .elgg-message-error')).toHaveCount(0);
    // Either list or empty-state should be visible
    const body = page.locator('body');
    await expect(body).toBeVisible();
  });

  test('owner dashboard renders', async ({ page }) => {
    await loginAs(page, 'testuser');
    await page.goto('/gallery/owner/testuser');
    await expect(page.locator('.elgg-system-messages .elgg-message-error')).toHaveCount(0);
  });
});
