NettePropel2
=================

Propel 2 integration with Nette Framework

In bootstrap.php:

```php
  use NettePropel2;
  
  $container = $configurator->createContainer();
  
  NettePropel2\Setup::setup($container)
```

You can use both Neon or PHP to configure the database

In propel.local.php:

```php
  $database = [
      'default' => [
          'adapter'     => 'sqlite|pgsql|mysql|oracle|mssql',
          'host'        => 'host',
          'dbname'      => 'dbname',
          'user'        => 'user',
          'password'    => 'password'
      ]
  ];
```

Or propel.local.neon:

```
  default:
    adapter: sqlite|pgsql|mysql|oracle|mssql
    host: host
    dbname: dbname
    user: user
    password: password
```