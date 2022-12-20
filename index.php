<?php
define('_RONIN', 1);
session_start();
include 'app/default.php';
include 'app/custom.php';
$usersData              = include 'app/users.php';
$roninApp               = include 'app/nav.php';
$roninApp['config']     = include 'app/config.php';
$roninApp['user']       = getUser($usersData);
$roninApp['url']        = roninParseUrl(RONIN_URL_CURRENT);
$roninApp['currentNav'] = currentNav();
if($roninApp['config']['debug']){
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}else{
    error_reporting(0);
    ini_set("display_errors", 0);
}
$systemPage  = in_array($roninApp['currentNav']['alias'], $roninApp['config']['systemPages'])? true: false;
$ipsGranted  = in_array($_SERVER['REMOTE_ADDR'], $roninApp['config']['maintenanceIP'])? true: false;
if($roninApp['config']['maintenance'] && (!$ipsGranted && !$systemPage)){
    $roninApp['currentNav']['tmpl'] = 'maintenance';
}elseif(!empty($roninApp['url']['vars']['tmpl'])){
    $roninApp['currentNav']['tmpl'] = $roninApp['url']['vars']['tmpl'];
}
if($roninApp['user']['level'] >= $roninApp['currentNav']['access']){
    include 'templates/'.$roninApp['currentNav']['tmpl'].'.php';
}else{
    $roninRetorno = base64_encode($roninApp['url']['current_url']);
    header('Location: '.RONIN_URL_BASE.'/acceso-usuarios?return='.$roninRetorno);
}
?>
