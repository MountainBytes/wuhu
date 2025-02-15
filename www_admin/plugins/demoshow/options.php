<?php
if (!defined("ADMIN_DIR") || !defined("PLUGINOPTIONS"))
//if (!defined("ADMIN_DIR") )
  exit();

  if (@$_POST['demoshow_description']) {
    $out = array();
    $out["success"] = true;
    $out["result"] = array();
    $out["result"]["mode"] = "compodisplay";
    $out["result"]["eventname"] = @$_POST['eventname'];

    $lines = preg_split("/\r\n|\n|\r/", $_POST['demoshow_description']);
    
    foreach ($lines as $line)
    {
      $fields = preg_split("/:/", $line);
      if (!@$fields[0])
        $fields[0] = "\u{00A0}";
      $out["result"]["entries"][] = array(
        "number" => @$fields[0],
        "author" => @$fields[1],
        "title" => @$fields[2],
        "comment" => @$fields[3]
      );
    }
    file_put_contents("beamer.data",serialize($out));
  }

?>

<h2>Demoshow</h2>

Experimental!

<form action="pluginoptions.php?plugin=demoshow" method="POST">
    Event: <input type="text" name="eventname" value="<?=@$_POST['eventname']?>"><br>
    <textarea name="demoshow_description"><?=@$_POST['demoshow_description']?></textarea>
    <input type="submit" value="go">
</form>