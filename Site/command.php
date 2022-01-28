<?php
 $cmd=$_GET["command"];

 if($cmd==="nemo1") {
  $Pad = getcwd();
  exec("nemo '$Pad'");
 }
 else if($cmd==="nemo2") {
  $Pad = "/home/marcel/DownloadPC";
  exec("nemo '$Pad'");
 }
 else if($cmd==="seriesbig") {
  $Pad = "smb://192.168.0.95/big/Series";
  exec("nemo '$Pad'");
 }
 else if($cmd==="series") {
  $Pad = "smb://nas.local/root/Series";
  exec("nemo '$Pad'");
 }



?>
