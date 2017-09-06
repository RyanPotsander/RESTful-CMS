<?php /* Smarty version 3.1.27, created on 2017-09-06 03:14:57
         compiled from "/Applications/MAMP/htdocs/decoupled_cms/manager/templates/default/welcome.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:32208970159af4c11225773_88446981%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '46a660d0383fff69736b71c602df4e9c9e5a4500' => 
    array (
      0 => '/Applications/MAMP/htdocs/decoupled_cms/manager/templates/default/welcome.tpl',
      1 => 1492694476,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '32208970159af4c11225773_88446981',
  'variables' => 
  array (
    'dashboard' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_59af4c112278c6_01408173',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_59af4c112278c6_01408173')) {
function content_59af4c112278c6_01408173 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '32208970159af4c11225773_88446981';
?>
<div id="modx-panel-welcome-div"></div>

<div id="modx-dashboard" class="dashboard">
<?php echo $_smarty_tpl->tpl_vars['dashboard']->value;?>

</div><?php }
}
?>