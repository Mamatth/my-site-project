<?php

/**
 * @file
 * Contains \DrupalProject\composer\ScriptHandler.
 */

namespace SiteGen\composer;

use Composer\Script\Event;
use Composer\Semver\Comparator;
use DrupalFinder\DrupalFinder;
use Symfony\Component\Filesystem\Filesystem;
use Webmozart\PathUtil\Path;

class ScriptHandler {

  public static function createRequiredFiles(Event $event) {
    $fs = new Filesystem();
    $drupalFinder = new DrupalFinder();
    $drupalFinder->locateRoot(getcwd());
    $drupalRoot = $drupalFinder->getDrupalRoot();
    $projectRoot = getcwd();

    $dirs = [
      'modules',
      'profiles',
      'themes',
    ];

    // Required for unit testing
    foreach ($dirs as $dir) {
      if (!$fs->exists($drupalRoot . '/' . $dir)) {
        $fs->mkdir($drupalRoot . '/' . $dir);
      }
    }

    // Create symbolic link
    if (!$fs->exists($drupalRoot . "/config-sync")) {
      $fs->symlink(Path::makeRelative($projectRoot . "/project/config-sync",
        $drupalRoot), $drupalRoot . "/config-sync");
    }
    if (!$fs->exists($drupalRoot . "/content-sync")) {
      $fs->symlink(Path::makeRelative($projectRoot . "/project/content-sync",
        $drupalRoot), $drupalRoot . "/content-sync");
    }


    // Copy project settings on default one
    $fs->copy($projectRoot . '/project/conf/settings.php',
      $drupalRoot . '/sites/default/default.settings.php', TRUE);
    $event->getIO()
          ->write("Created a sites/default/default.settings.php without project content");

    // Create global settings shared by all sites
    $fs->copy($projectRoot . '/project/conf/global.settings.php',
      $drupalRoot . '/sites/global.settings.php', TRUE);

    $fs->chmod($drupalRoot . '/sites/global.settings.php', 0644);
    $event->getIO()
          ->write("Created a sites/global.settings.php file with chmod 0644");

    // Prepare the settings file for installation
    $fs->copy($drupalRoot . '/sites/default/default.settings.php',
      $drupalRoot . '/sites/default/settings.php', TRUE);

    $fs->chmod($drupalRoot . '/sites/default/settings.php', 0644);
    $event->getIO()
          ->write("Created a sites/default/settings.php file with chmod 0644");

    // Create the files directory with chmod 0777
    if (!$fs->exists($drupalRoot . '/sites/default/files')) {
      $oldmask = umask(0);
      $fs->mkdir($drupalRoot . '/sites/default/files', 0777);
      umask($oldmask);
      $event->getIO()
            ->write("Created a sites/default/files directory with chmod 0777");
    }

    // Manage config sync dir by environment
    foreach ([
               'config-sync-qualif',
               'config-sync-prod',
             ] as $oneConfigSync) {

      if (!$fs->exists($drupalRoot . '/' . $oneConfigSync)) {
        $fs->mkdir($drupalRoot . '/' . $oneConfigSync);
      }

      $fs->mirror($projectRoot . '/project/config-sync',
        $drupalRoot . '/' . $oneConfigSync, NULL, ['delete' => TRUE]);
      $fs->mirror($projectRoot . '/project/' . $oneConfigSync,
        $drupalRoot . '/' . $oneConfigSync, NULL, ['override' => TRUE]);
    }
    // Mirrors files-sync folder (default images used in content) to sites/default/files folder in order to be used by gatsby-source-filesystem plugin
    $fs->mirror($projectRoot . '/project/content-sync/files/public',
      $drupalRoot . '/sites/default/files');
  }


  /**
   * Checks if the installed version of Composer is compatible.
   *
   * Composer 1.0.0 and higher consider a `composer install` without having a
   * lock file present as equal to `composer update`. We do not ship with a lock
   * file to avoid merge conflicts downstream, meaning that if a project is
   * installed with an older version of Composer the scaffolding of Drupal will
   * not be triggered. We check this here instead of in drupal-scaffold to be
   * able to give immediate feedback to the end user, rather than failing the
   * installation after going through the lengthy process of compiling and
   * downloading the Composer dependencies.
   *
   * @see https://github.com/composer/composer/pull/5035
   */
  public static function checkComposerVersion(Event $event) {
    $composer = $event->getComposer();
    $io = $event->getIO();

    $version = $composer::VERSION;

    // The dev-channel of composer uses the git revision as version number,
    // try to the branch alias instead.
    if (preg_match('/^[0-9a-f]{40}$/i', $version)) {
      $version = $composer::BRANCH_ALIAS_VERSION;
    }

    // If Composer is installed through git we have no easy way to determine if
    // it is new enough, just display a warning.
    if ($version === '@package_version@'
      || $version === '@package_branch_alias_version@'
    ) {
      $io->writeError('<warning>You are running a development version of Composer. If you experience problems, please update Composer to the latest stable version.</warning>');
    }
    elseif (Comparator::lessThan($version, '1.0.0')) {
      $io->writeError('<error>Drupal-project requires Composer version 1.0.0 or higher. Please update your Composer before continuing</error>.');
      exit(1);
    }
  }

}
