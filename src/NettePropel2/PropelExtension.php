<?php

namespace NettePropel2;

use Nette;

/**
 * Compiler extension to setup Propel2
 *
 * @author Livio Ribeiro
 */
class PropelExtension extends Nette\DI\CompilerExtension
{
    public function afterCompile(Nette\PhpGenerator\ClassType $class) {
        $initialize = $class->methods['initialize'];
        $initialize->addBody('\NettePropel2\Setup::setup($this);');
    }
}
