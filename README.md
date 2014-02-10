NettePropel2
=================

Propel 2 integration with Nette Framework

In bootstrap.php

```php
  use NettePropel2;
  
  $container = $configurator->createContainer();
  
  NettePropel2\Setup::setup($container)
```

In config.local.neon

```yaml
  parameters:
    propel:
        adapter: sqlite|pgsql|mysql|oracle|mssql
        datasource: default # attribute "name" from <database> in schema.xml
        host: host_name
        dbname: db_name
        user: user
        password: password
```
