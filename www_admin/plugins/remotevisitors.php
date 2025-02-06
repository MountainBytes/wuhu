<?php
/*
Plugin name: Remote visitors
Description: Option to mark visitors as remote
*/
if (!defined("ADMIN_DIR")) exit();

function remotevisitors_processfield( $data )
{
  $data["data"]["remote"] = (@$_POST["remote"] == "on") ? 1 : 0;
}
add_hook("register_processdata","remotevisitors_processfield");

function remotevisitors_users_addfield( &$data )
{
  if (!$data["user"])
  {
    return;
  }
  $remote = $data["user"]->remote;
?>
<hr/>
<form  method="post">
  <input type="hidden" name="id" value="<?=$_GET["id"]?>"/>
  Remote visitor: <input type="checkbox" name="remote"<?=($remote?' checked="checked"':'')?>/>
  <input type="submit" name="action" value="Set remote"/>
</form>
<?php
}
add_hook("admin_edituser_beforeactions","remotevisitors_users_addfield");

function remotevisitors_process_data()
{
  if (@$_POST["id"] && $_POST["action"]=="Set remote") 
  {
    $a = array();
    $a["remote"] = @$_POST["remote"] ? "1" : "0";
    SQLLib::UpdateRow("users",$a,"id = ".(int)$_POST["id"]);
    printf("<div class='success'>Remote status set.</div>\n");
  }
}
add_hook("admin_edituser_start","remotevisitors_process_data");



function remotevisitors_userlist_add_column($data)
{
  $text = "";
  if (@$data["user"] && $data["user"]->remote)
    $text = "Remote user";
  printf("  <td>%s</td>", $text);
}
add_hook("admin_users_userlist_row_end","remotevisitors_userlist_add_column");


function remotevisitors_profile_addfield( )
{
  $remote = get_current_user_data()->remote;
  if ($remote)
  {
?>
<div>
  <p>You are registered as a remote visitor. If this is not correct, please contact the organizers.</p>
</div>
<?php
  }
}
add_hook("profile_endform","remotevisitors_profile_addfield");

function remotevisitors_register_addfield( )
{
?>
<div>
  <label for="remote">Are you participating remotely?</label>
  <input id="remote" name="remote" type="checkbox"/>
</div>
<?php
}
add_hook("register_endform","remotevisitors_register_addfield");

function remotevisitors_activation()
{
  $r = SQLLib::selectRow("show columns from users where field = 'remote'");
  if (!$r)
  {
    SQLLib::Query("ALTER TABLE users ADD `remote` tinyint(4) NOT NULL DEFAULT '0';");
  }
}
add_activation_hook( __FILE__, "remotevisitors_activation" );

?>