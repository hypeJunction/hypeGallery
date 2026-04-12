import { Page } from '@playwright/test';
import mysql from 'mysql2/promise';

const DB_CONFIG = {
  host: process.env.ELGG_DB_HOST || 'db',
  port: Number(process.env.ELGG_DB_PORT || 3306),
  user: process.env.ELGG_DB_USER || 'elgg',
  password: process.env.ELGG_DB_PASS || 'elgg',
  database: process.env.ELGG_DB_NAME || 'elgg',
};

export async function loginAs(
  page: Page,
  username: string,
  password: string = 'testpass123'
) {
  await page.goto('/login');
  await page.fill('input[name="username"]', username);
  await page.fill('input[name="password"]', password);
  await page.click('button[type="submit"]');
  await page.waitForLoadState('networkidle');
}

export async function queryDb(sql: string, params: any[] = []): Promise<any[]> {
  const conn = await mysql.createConnection(DB_CONFIG);
  const [rows] = await conn.execute(sql, params);
  await conn.end();
  return rows as any[];
}

export async function getEntitiesBySubtype(
  subtype: string,
  ownerGuid?: number
): Promise<any[]> {
  let sql = 'SELECT * FROM elgg_entities WHERE subtype = ?';
  const params: any[] = [subtype];
  if (ownerGuid) {
    sql += ' AND owner_guid = ?';
    params.push(ownerGuid);
  }
  sql += ' ORDER BY guid DESC';
  return queryDb(sql, params);
}

export async function getMetadata(entityGuid: number, name: string): Promise<any[]> {
  return queryDb(
    'SELECT * FROM elgg_metadata WHERE entity_guid = ? AND name = ?',
    [entityGuid, name]
  );
}

export async function getAlbumImageCount(albumGuid: number): Promise<number> {
  const rows = await queryDb(
    `SELECT COUNT(*) AS c FROM elgg_entities
     WHERE subtype = 'hjalbumimage' AND container_guid = ?`,
    [albumGuid]
  );
  return Number((rows[0] as any).c);
}

export async function cleanupGalleryEntities(ownerGuid: number): Promise<void> {
  await queryDb(
    `DELETE FROM elgg_entities WHERE owner_guid = ?
     AND subtype IN ('hjalbum', 'hjalbumimage')`,
    [ownerGuid]
  );
}
