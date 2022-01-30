<?php
 var_dump($_POST);

 //die();

 $Db = json_decode(file_get_contents('Db/data.json'), true);
 if(!$Db) $Db=[];
 $ix=$_POST["hVolgnummer"]*1;

 if($ix===-1) {
  // Toevoegen (append)
  $Rec=[
   "nr" => count($Db),
   "titel" => $_POST["txtTitel"],
   "verhaal"=> $_POST["txtOmschrijving"],
   "subtitel" => isset($_POST["sSoort"])?$_POST["sSoort"]:$_POST["sSoortFix"],
   "loc" => isset($_POST["sPlaats"])? $_POST["sPlaats"] : $_POST["sPlaatsFix"],
   "score" => $_POST["txtScore"],
   "isfilm" => $_POST["cFilm"]
  ];
  array_push($Db,$Rec);
  $ix=count($Db)-1;
  echo "PUSH";
 }
 else {
  // Updaten
  $ix=$_POST["hVolgnummer"]*1;
  $Rec=[
   "nr" => $ix,
   "titel" => $_POST["txtTitel"],
   "verhaal"=> $_POST["txtOmschrijving"],
   "subtitel" => isset($_POST["sSoort"])?$_POST["sSoort"]:$_POST["sSoortFix"],
   "loc" => isset($_POST["sPlaats"])? $_POST["sPlaats"] : $_POST["sPlaatsFix"],
   "score" => $_POST["txtScore"],
   "isfilm" => $_POST["cFilm"]
  ];
  $Db[$ix] = $Rec;
  echo "UPDATE";
 }
 // Save database
 file_put_contents("Db/data.json",json_encode($Db));
 if(isset($_FILES["fAfbeelding"])) {
  $FileLocatie = "Db/s$ix.jpg";
  var_dump($_FILES);
  echo "$FileLocatie==>".move_uploaded_file($_FILES["fAfbeelding"]["tmp_name"], $FileLocatie);

 }
 else echo "KUT";

 header('Location: index.php?lastid='.$ix);
 die();
?>


