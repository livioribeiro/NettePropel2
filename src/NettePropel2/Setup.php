<?php

namespace NettePropel2;

use Nette,
    Nette\Diagnostics\Debugger,
    Nette\Utils\Neon;
use Monolog\Logger;
use Propel\Runtime\Propel,
    Propel\Runtime\Connection\ConnectionManagerSingle;
Use NettePropel2\Diagnostics\PropelPanel;

/**
 * @author Livio Ribeiro <livioribeiro@outlook.com>
 */
class Setup extends Nette\Object {

    private static $db;
    private static $datasource;

    private static function getParam($paramName) {
        if (!array_key_exists($paramName, self::$db)) {
            throw new \Exception("Missing \"$paramName\" in propel configuration");
        }
        return self::$db[$paramName];
    }

    private static function parseConfigFile($appDir) {
        if (is_file($filename = "$appDir/config/propel.local.php")) {
            require $filename;
        } elseif (is_file($filename = "$appDir/config/propel.local.neon")) {
            $database = Neon::decode(file_get_contents($filename));
        }
        
        try {
            $datasource = array_keys($database)[0];
        } catch (\Exception $e) {
            throw new \Exception('Wrong configuration for propel');
        }

        if (!is_array($database[$datasource])) {
            throw new \Exception('Wrong configuration for propel');
        }

        self::$db = $database[$datasource];
        self::$datasource = $datasource;
    }
    
    private static function getConfig($debugMode) {
        $adapter = self::getParam('adapter');
        $dbname = self::getParam('dbname');

        if ($adapter == 'sqlite') {
            $config = ['dsn' => "$adapter: dbname=$dbname"];
        } else {
            $host = self::getParam('host');
            $user = self::getParam('user');
            $password = self::getParam('password');

            $config = [
                'dsn' => "$adapter:host=$host;dbname=$dbname",
                'user' => $user,
                'password' => $password
            ];
        }
        
        if ($debugMode || Nette\Configurator::detectDebugMode()) {
            $config['classname'] = 'NettePropel2\\Connection\\PanelConnectionWrapper';
        }
        
        return $config;
    }
    
    public static function getConnectionForCli($appDir) {
        self::parseConfigFile($appDir);
        
        $config = self::getConfig(true);
        
        return self::$datasource . "=$config[dsn];user=$config[user];password=$config[password]";
    }

    public static function setup(\Nette\DI\Container $container) {
        self::parseConfigFile($container->parameters['appDir']);
        
        $serviceContainer = Propel::getServiceContainer();
        $serviceContainer->setAdapterClass(self::$datasource, self::$db['adapter']);

        $manager = new ConnectionManagerSingle();
        $manager->setConfiguration(self::getConfig($container));
        $serviceContainer->setConnectionManager(self::$datasource, $manager);

        $panel = new PropelPanel();

        $logger = new Logger('defaultLogger');
        $logger->pushHandler($panel);
        $serviceContainer->setLogger('defaultLogger', $logger);

        Debugger::addPanel($panel);
    }

}
