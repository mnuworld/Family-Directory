<?php
define("SMARTY_DIR","tpl/smarty/");
require_once("php/Smarty.class.php");

$smarty = new Smarty();

// Initialize the template engine
tpl_init("tpl/","tpl/tpl_c","tpl/cache","tpl/configs");

function tpl_init($tpl_dir,$tpl_c_dir,$cache,$configs)
{
    global $smarty;
    $smarty->template_dir = $tpl_dir;
    $smarty->compile_dir = $tpl_c_dir;
    $smarty->cache_dir = $cache;
    $smarty->config_dir = $configs;
}

function tpl_set($tpl_var,$var_val)
{
    global $smarty;
    $smarty->assign($tpl_var,$var_val);
}

function tpl_display($tpl_path)
{
    global $smarty;
    $smarty->display($tpl_path);
}
?>