<?php
/*
Plugin name: Demoshow
Description: Don't use this.
*/


function demoshow_addmenu( $data )
{
  $data["links"]["pluginoptions.php?plugin=demoshow"] = "Demoshow";
}

add_hook("admin_menu","demoshow_addmenu");


function demoshow_activation()
{
}

add_activation_hook( __FILE__, "demoshow_activation" );
?>