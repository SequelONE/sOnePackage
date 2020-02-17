<?php

namespace sequelone\sOnePackage\Test;

use sequelone\sOnePackage\sOnePackage;

class Example extends sOnePackage {
    protected $namespace = 'example';
    public function __construct($instance, array $config = array())
    {
        parent::__construct($instance, $config);

        $this->setVersion(1, 2, 3, 'pl');
    }
}