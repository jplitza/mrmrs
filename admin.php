<?php
require_once('includes/smarty/Smarty.class.php');
require_once('includes/mysql.class.php');
date_default_timezone_set('Europe/Berlin');
error_reporting(E_ALL);
mb_internal_encoding('UTF-8');
$t = new Smarty;
$t->assign('acp', true);
$prefix = 'mrmrs_';

if(empty($_GET['page']))
  $t->display('admin_overview.tpl');
elseif(is_file('templates/'.basename($_GET['page']).'.tpl') && is_file('acp_pages/'.basename($_GET['page']).'.php'))
{
  require('acp_pages/'.basename($_GET['page']).'.php');
  $t->display(basename($_GET['page']).'.tpl');
}
?>
