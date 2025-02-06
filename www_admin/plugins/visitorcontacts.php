<?php
/*
Plugin name: Visitor contacts
Description: Additional fields on the user records for email and Discord contact
*/
if (!defined("ADMIN_DIR")) exit();

function visitorcontacts_processfield( $data )
{
    $data["data"]["email"] = @$_POST["email"];
    $data["data"]["discord"] = @$_POST["discord"];
}
add_hook("register_processdata","visitorcontacts_processfield");
add_hook("profile_processdata","visitorcontacts_processfield");

function visitorcontacts_profile_addfield( )
{
    if (is_user_logged_in())
      $user = get_current_user_data();
?>
<div>
  <label for="email">Email: (optional)</label>
  <input id="email" name="email" type="email" value="<?=_html(@$user->email)?>"/>
</div>
<div>
  <label for="discord">Discord: (optional)</label>
  <input id="discord" name="discord" type="text" value="<?=_html(@$user->discord)?>"/>
</div>
<?php
}
add_hook("profile_endform","visitorcontacts_profile_addfield");

function visitorcontacts_register_addfield( )
{
?>
<div>
  <label for="email">Email: (optional)</label>
  <input id="email" name="email" type="email" value="<?=_html(@$_POST["email"])?>"/>
</div>
<div>
  <label for="discord">Discord: (optional)</label>
  <input id="discord" name="discord" type="text" value="<?=_html(@$_POST["discord"])?>"/>
</div>
<?php
}
add_hook("register_endform","visitorcontacts_register_addfield");

function visitorcontacts_edituser_add_fields( $data )
{
  if ( @$data["user"] )
  {
    $user = $data["user"];
    printf("  <li><b>Email:</b> %s</li>\n",_html($user->email));
    printf("  <li><b>Discord:</b> %s</li>\n",_html($user->discord));
  }
}
add_hook("admin_edituser_user_fields_end","visitorcontacts_edituser_add_fields");


function visitorcontacts_activation()
{
    $r = SQLLib::selectRow("show columns from users where field = 'email'");
    if (!$r)
    {
      SQLLib::Query("ALTER TABLE users ADD `email` text;");
    }
    $r = SQLLib::selectRow("show columns from users where field = 'discord'");
    if (!$r)
    {
      SQLLib::Query("ALTER TABLE users ADD `discord` text;");
    }
}
add_activation_hook( __FILE__, "visitorcontacts_activation" );

?>