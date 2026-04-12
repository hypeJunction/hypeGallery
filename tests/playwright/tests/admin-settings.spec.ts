import { test, expect } from '@playwright/test';
import { loginAs } from '../helpers/elgg';

test.describe('hypeGallery: admin settings', () => {
  test('admin plugin settings page renders', async ({ page }) => {
    await loginAs(page, 'admin');
    const resp = await page.goto('/admin/plugin_settings/hypeGallery');

    expect([200, 302]).toContain(resp?.status() ?? 0);
    await expect(page.locator('.elgg-system-messages .elgg-message-error')).toHaveCount(0);
  });
});
