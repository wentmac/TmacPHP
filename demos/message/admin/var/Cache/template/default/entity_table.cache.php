<?php if (!class_exists('template', false)) die('Access Denied');
0
|| self::check('default\entity_table.tpl', 'D:\Web\Witkey\wwwroot\tuhao\trunk\admin\application\View\default\entity_table.tpl', 1461670424)
;?>


<title><?php echo $table;?><<?php echo $table_name;?>>住哪网酒店数据库结构说明[保密资料]</title>
<style>
*{font-size:12px; line-height:150%; font-family:Arial, Helvetica, sans-serif;}
.tables { border-top:1px #CCC solid;border-left:1px #CCC solid;}
.tables tr th{ background-color:#f1f1f1}
.tables tr td,.tables tr th{ border-bottom:1px #CCC solid;border-right:1px #CCC solid;padding:4px 6px; height:20px; overflow:hidden; }
.tables tr th{ background-color:#f1f1f1; }
</style>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC" class="tables">
  <tr>
    <td height="83" colspan="4" align="center" bgcolor="#FFFFFF"><h2 style="font-size:22px; padding-top:15px; padding-bottom:5px;">表：<?php echo $table;?><<?php echo $table_name;?>>数据结构说明 [保密资料]</h2></td>
  </tr>
  <tr>
    <th width="110" align="left"><strong>表名</strong></th>
    <th width="100" align="left"><strong>类型</strong></th>
    <th width="100" align="left"><strong>长度</strong></th>
    <th align="left"><strong>字段说明</strong></th>
  </tr>
<?php if(is_array($rs)) foreach($rs AS $k => $v) { ?>
  <tr>
    <td width="110" align="left" bgcolor="#FFFFFF"><strong><?php echo $v['field'];?></strong></td>
    <td width="100" bgcolor="#FFFFFF"><?php echo $v['name'];?></td>
    <td bgcolor="#FFFFFF"><?php echo $v['max_length'];?></td>
    <td bgcolor="#FFFFFF"><?php echo $v['description'];?></td>
  </tr>
<?php } ?>
  
</table>
