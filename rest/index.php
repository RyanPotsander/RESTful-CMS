<?php
$base_path= '/home/blackhawkbowl/public_html/cms/';
require_once 'config.core.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
//$modx = new modX();
//$modx->initialize('web');
$modx = modX::getInstance('rest');
$modx->addPackage('modResource', $base_path . 'core/model/modx/modresource.class.php');
//$modx->getService('modresource', 'modResource', MODX_CORE_PATH . 'model/modx/modresource.class.php' );


/* now load the REST service */
$rest = $modx->getService('rest','rest.modRestService','',array(
    'basePath' => dirname(__FILE__).'/Controllers/',
    'controllerClassSeparator' => '',
    'controllerClassPrefix' => 'MyController',
    'xmlRootNode' => 'response'
));
$rest->prepare();
if (!$rest->checkPermissions()) {
    $rest->sendUnauthorized(true);
}
$rest->process();