<?php

$databases['default']['default']['database'] = getenv('POSTGRES_DB');

$settings['trusted_host_patterns'] = explode('§', getenv('DRUPAL_TRUSTED_HOST'));

// Below you have composer settings DO NOT REMOVE LAST LINE
