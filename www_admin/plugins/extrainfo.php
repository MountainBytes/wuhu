<?php
/*
Plugin name: Extra info on compo slides
Description: Adds some extra info to compo slides (platform, size, etc.)
*/

function extrainfo_process_entry( $data )
{
  if (is_admin_page())
  {
    if ($data["entry"]->extrainfo)
    {
      $data["slide"]["extrainfo"] = str_replace(" ", "\n", $data["entry"]->extrainfo);
    }
  }
}
add_hook("admin_beamer_compodisplay_postprocess_entry","extrainfo_process_entry");

function extrainfo_updatedb( $data )
{
  if (is_admin_page())
    $data["sqlData"]["extrainfo"] = @$_POST["extrainfo"];
}
add_hook("admin_common_handleupload_beforedb","extrainfo_updatedb");

function extrainfo_editform( $data )
{
  if (!@$data["entry"]) return;
?>
<tr>
  <td>Extra info: (use short tags, delimit with Space)<br/><small>(this will be shown on the compo slide)</small></td>
  <td><input id="extrainfo" name="extrainfo" type="text" value="<?=_html($data["entry"]->extrainfo)?>" class="inputfield"/></td>
</tr>
<?php
}
add_hook("admin_editentry_editform","extrainfo_editform");

function extrainfo_activation()
{
  $r = SQLLib::selectRow("show columns from compoentries where field = 'extrainfo'");
  if (!$r)
  {
    SQLLib::Query("ALTER TABLE compoentries ADD `extrainfo` TEXT collate utf8_unicode_ci;");
  }
}
add_activation_hook( __FILE__, "extrainfo_activation" );

?>
