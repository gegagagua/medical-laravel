#!/usr/bin/env node
/**
 * Rollup 4+ loads a platform-specific optional package (e.g. @rollup/rollup-linux-x64-gnu).
 * npm sometimes skips optional deps (see npm/cli#4828); Plesk/deploy may ship incomplete node_modules.
 * This script installs the matching native package on Linux when it is missing.
 */
const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

if (process.platform !== 'linux') {
  process.exit(0);
}

const root = path.join(__dirname, '..');
const rollupJson = path.join(root, 'node_modules', 'rollup', 'package.json');

if (!fs.existsSync(rollupJson)) {
  process.exit(0);
}

const rollupVersion = JSON.parse(fs.readFileSync(rollupJson, 'utf8')).version;

function isMusl() {
  try {
    return !require('node:process').report.getReport().header.glibcVersionRuntime;
  } catch {
    return false;
  }
}

function rollupNativePackageName() {
  const musl = isMusl();
  const { arch } = process;
  if (arch === 'x64') {
    return musl ? '@rollup/rollup-linux-x64-musl' : '@rollup/rollup-linux-x64-gnu';
  }
  if (arch === 'arm64') {
    return musl ? '@rollup/rollup-linux-arm64-musl' : '@rollup/rollup-linux-arm64-gnu';
  }
  if (arch === 'arm') {
    return musl ? '@rollup/rollup-linux-arm-musleabihf' : '@rollup/rollup-linux-arm-gnueabihf';
  }

  return null;
}

const pkgName = rollupNativePackageName();
if (!pkgName) {
  process.exit(0);
}

const short = pkgName.split('/')[1];
const pkgJsonPath = path.join(root, 'node_modules', '@rollup', short, 'package.json');
if (fs.existsSync(pkgJsonPath)) {
  process.exit(0);
}

console.warn(`[ensure-rollup-native] Installing missing ${pkgName}@${rollupVersion} (Rollup ${rollupVersion})...`);

try {
  execSync(`npm install "${pkgName}@${rollupVersion}" --no-audit --no-fund --no-save`, {
    cwd: root,
    stdio: 'inherit',
    env: { ...process.env, npm_config_optional: 'true' },
  });
} catch (e) {
  console.error('[ensure-rollup-native] Failed:', e instanceof Error ? e.message : e);
  process.exit(1);
}
