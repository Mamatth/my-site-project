# AtolCD : Template Drupal 8

## .env
You need to copy `.env.dist` content in `.env` file

You'll have to generate a value for `DRUPAL_HASH_SALT` which will be use in settings.php.
To do so, use the following command `openssl rand -base64 48`


## Import SQL dump
Just copy the needed SQL dump from `sql` to `sql/import`, which will be imported at docker stack creation.
* `0_fresh_installed_drupal.sql` => The freshest drupal database you could dream of (only user shortcuts were removed)
* `1_dummy_content_created.sql` => Database state with content, menu links and terms created

## Docker & Docker Compose
Install docker
Install docker-compose : https://docs.docker.com/compose/install/

To run the development stack : `docker-compose up -d`
To stop it : `docker-compose stop`

## Composer
You could use composer with docker without having to install it : https://redmine.atolcd.com/questions/173-utiliser-composer-sans-l-installer-avec-docker

You can now initiate all dependency : `./bin/composer install`
If you need new module or update existing on, edit composer.json and launch : `./bin/composer update`

At this point you should have a functional Drupal 8 site

## Connect to Docker database
Use the script sql2docker.
You can connect with drupal user : `./pg2docker connect`
You can connect with root user : `./pg2docker connect-root`
You can dump your drupal database : `./pg2docker dump`

## Default Admin user
User: `admin`
Password: `bw8l$D1x989nHTcQ`

To set password for any user, you can use `drupal user:password:reset` command

## Config-sync
To import all configurations files into the default site's database : `bin/drush cim`
On the opposite, if you want to export all configuration yaml files : `bin/drush cex`

### Multisite context
To launch drush commands on a specific site, `-l` argument may be useful : `bin/drush -l site_name cr`
You can also use Drupal Console to import one single configuration file : `bin/drupal config:import:single --directory="config-sync" --file="my_config_field.yml"`
