<?php
// Project configuration

global $content_directories;
$content_directories['sync'] = 'content-sync';

$settings['hash_salt'] = getenv('DRUPAL_HASH_SALT');

$settings['file_temp_path'] = getenv('TEMP');

$settings['update_free_access'] = getenv('ENVIRONMENT') === 'dev';

$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';

$settings['file_scan_ignore_directories'] = [
    'node_modules',
    'bower_components',
];

$settings['trusted_host_patterns'] = explode('§', getenv('DRUPAL_TRUSTED_HOST'));

$settings['entity_update_batch_size'] = 50;

$settings['entity_update_backup'] = true;

$databases['default']['default'] = [
    'username' => getenv('POSTGRES_USER'),
    'password' => getenv('POSTGRES_PASSWORD'),
    'prefix' => '',
    'host' => getenv('POSTGRES_HOST'),
    'port' => getenv('POSTGRES_PORT'),
    'namespace' => 'Drupal\\Core\\Database\\Driver\\pgsql',
    'driver' => 'pgsql',
];


$settings['disable_check_client_ip_socle'] = getenv('DISABLE_IP_CHECK_SOCLE') === 'true';

switch (getenv('ENVIRONMENT')) {
  case 'qualif':
    $settings['config_sync_directory'] = 'config-sync-qualif';
    break;
  case 'prod':
    $settings['config_sync_directory'] = 'config-sync-prod';
    break;
  default:
    $settings['config_sync_directory'] = 'config-sync';
}

// Below you have composer settings DO NOT REMOVE LAST LINE
