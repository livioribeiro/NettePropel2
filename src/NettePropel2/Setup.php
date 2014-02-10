<?php

namespace NettePropel2;

use Nette;
use Nette\Diagnostics\Debugger;
use Monolog\Logger;
use Propel\Runtime\Propel;
use Propel\Runtime\Connection\ConnectionManagerSingle;
Use NettePropel2\Diagnostics\PropelPanel;

/**
 * @author Livio Ribeiro <livioribeiro@outlook.com>
 */
class Setup extends Nette\Object {

    private static $propel;

    private static function getParam($paramName) {
        if (!array_key_exists($paramName, self::$propel)) {
            throw new \Exception("Missing \"$paramName\" in propel configuration");
        }
        return self::$propel[$paramName];
    }

    public static function setup(\Nette\DI\Container $container) {
        $parameters = $container->getParameters();

        if (!array_key_exists('propel', $parameters)) {
            throw new \Exception('Missing configuration for propel');
        }
        self::$propel = $parameters['propel'];

        $adapter = self::getParam('adapter');
        $dbname = self::getParam('dbname');

        if ($adapter == 'sqlite') {
            $config = ['dsn' => "$adapter: dbname=$dbname"];
        } else {
            $host = self::getParam('host');
            $user = self::getParam('user');
            $password = self::getParam('password');

            $config = [
                'dsn' => "$adapter: host=$host;dbname=$dbname;",
                'user' => $user,
                'password' => $password
            ];
        }

        if ($parameters['debugMode']) {
            $config['classname'] = 'NettePropel2\\Connection\\PanelConnectionWrapper';
        }

        try {
            $datasource = self::getParam('datasource');
        } catch (Exception $e) {
            $datasource = 'default';
        }

        $serviceContainer = Propel::getServiceContainer();
        $serviceContainer->setAdapterClass($datasource, $adapter);

        $manager = new ConnectionManagerSingle();
        $manager->setConfiguration($config);
        $serviceContainer->setConnectionManager($datasource, $manager);

        $panel = new PropelPanel();

        $logger = new Logger('defaultLogger');
        $logger->pushHandler($panel);
        $serviceContainer->setLogger('defaultLogger', $logger);

        Debugger::addPanel($panel);
    }

}
