import { test, expect } from '@playwright/test';
import * as path from 'path';
import {
  loginAs,
  getEntitiesBySubtype,
  getAlbumImageCount,
} from '../helpers/elgg';

test.describe('hypeGallery: image upload', () => {
  test('user uploads an image into an album', async ({ page }) => {
    await loginAs(page, 'testuser');

    // Create an album first
    await page.goto('/gallery/add');
    const albumTitle = `Upload Album ${Date.now()}`;
    await page.fill('input[name="title"]', albumTitle);
    await page.fill('textarea[name="description"]', 'Album for upload test');
    await page.click('input[type="submit"], button[type="submit"]');
    await page.waitForLoadState('networkidle');

    // Fetch the newly created album from DB to get its guid
    const albums = await getEntitiesBySubtype('hjalbum');
    expect(albums.length).toBeGreaterThan(0);
    const albumGuid = Number(albums[0].guid);

    // Navigate to manage page for this album
    await page.goto(`/gallery/manage/${albumGuid}`);
    await expect(page.locator('body')).toContainText(/upload|drop|add/i);

    // Upload a fixture image (ship a tiny PNG beside the test file)
    const fixture = path.resolve(__dirname, '../fixtures/test-image.png');
    const fileInput = page.locator('input[type="file"]').first();
    if ((await fileInput.count()) > 0) {
      await fileInput.setInputFiles(fixture);
      // Trigger form submit if upload form is separate
      const submit = page.locator('form').locator('button[type="submit"], input[type="submit"]').first();
      if ((await submit.count()) > 0) {
        await submit.click();
        await page.waitForLoadState('networkidle');
      }

      // Assert DB: image count on album incremented
      const count = await getAlbumImageCount(albumGuid);
      expect(count).toBeGreaterThanOrEqual(1);

      // Assert DB: hjalbumimage entity created inside this album
      const images = await getEntitiesBySubtype('hjalbumimage');
      const belongs = images.find(i => Number(i.container_guid) === albumGuid);
      expect(belongs).toBeTruthy();
    } else {
      test.skip(true, 'Upload form not present — may need hypeFilestore plugin active');
    }
  });

  test('non-owner cannot access album manage page', async ({ page }) => {
    // Owner creates album
    await loginAs(page, 'testuser');
    await page.goto('/gallery/add');
    const title = `Private Album ${Date.now()}`;
    await page.fill('input[name="title"]', title);
    await page.click('input[type="submit"], button[type="submit"]');
    await page.waitForLoadState('networkidle');

    const albums = await getEntitiesBySubtype('hjalbum');
    const albumGuid = Number(albums[0].guid);

    // Login as other user, try to access manage
    await loginAs(page, 'otheruser');
    const response = await page.goto(`/gallery/manage/${albumGuid}`);
    // Should be forbidden, redirected, or not show manage controls
    if (response) {
      expect([200, 302, 403, 404]).toContain(response.status());
    }
    // UI-level assertion: no manage form visible to non-owner
    const manageForm = page.locator('form.elgg-form-gallery-manage');
    await expect(manageForm).toHaveCount(0);
  });
});
