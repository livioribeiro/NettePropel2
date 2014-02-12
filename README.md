NettePropel2
============

Propel 2 integration with Nette Framework

### Configuration

In bootstrap.php:

```php
  use NettePropel2;
  ...
  $container = $configurator->createContainer();
  NettePropel2\Setup::setup($container)
```

You can use both Neon or PHP to configure the database (If both are present, the php will be preferred).

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

```yaml
  default:
    adapter: sqlite|pgsql|mysql|oracle|mssql
    host: host
    dbname: dbname
    user: user
    password: password
```

### Schema and shell

You schema.xml must be on app/schema directory.

To run propel commands (`model:build`, `migration:diff`, etc) use the `npropel` shell instead of `propel`. The `npropel` script will set the `--input-dir`, `--output-dir` and `--connection` parameters to your project.

```shell
  ln -s vendor/bin/npropel propel
```
