<?php

require_once dirname(dirname(__FILE__)) . '/config.core.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('mgr');
$modx->getService('error','error.modError', '', '');
//$path = $modx->getOption('sonepackage.core_path', null, $modx->getOption('core_path') . 'components/sonepackage/');
//$sOnePackage = $modx->getService('sonepackage', 'sOnePackage', $path . 'model/sonepackage/');

require_once dirname(dirname(__FILE__)) . '/core/components/sonepackage/vendor/autoload.php';
