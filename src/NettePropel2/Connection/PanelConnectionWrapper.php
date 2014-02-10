<?php

namespace NettePropel2\Connection;

use Propel\Runtime\Connection\ProfilerConnectionWrapper;

class PanelConnectionWrapper extends ProfilerConnectionWrapper {
    
    private $statement;
    
    /**
     * {@inheritDoc}
     */
    public function prepare($sql, $driver_options = array()) {
        $this->statement = parent::prepare($sql, $driver_options);
        return $this->statement;
    }

    /**
     * {@inheritDoc}
     */
    public function log($msg) {
        if ($this->statement == null) {
            return;
        }
        $rowCount = $this->statement->rowCount();
        
        $msg = "Affected Rows: $rowCount | $msg";
        
        $msg = parent::log($msg);
    }

}
