<html>
 <head>
  <link rel="stylesheet" href="../client/w3.css">
  <link rel="stylesheet" href="../client/series.css">
  <script src="../client/jquery-3.6.0.min.js"></script>
  <script src="../client/jquery.caret.js"></script>
  <script src="../client/series.js"></script>

  <link rel="stylesheet" href="../client/dragula.min.css">
  <script src="../client/dragula.min.js"></script>
  <title>Mijn Series</title>
 </head>
 <body>
  <?php $Db = json_decode(file_get_contents('Db/data.json'), true);

  function CompRating($a,$b) {
   if($a["score"]===$b["score"]) return 0;
   return ($a["score"] > $b["score"]) ? -1 : 1;
  }

  function CompNaam($a,$b) {
   $t1 = strtolower($a["titel"]);
   $t2 = strtolower($b["titel"]);
   if(startsWith($t1,"de ")) $t1=substr($t1,3);
   if(startsWith($t2,"de ")) $t2=substr($t2,3);
   if(startsWith($t1,"the ")) $t1=substr($t1,4);
   if(startsWith($t2,"the ")) $t2=substr($t2,4);
   if($t1===$t2) return 0;
   return ($t1 < $t2) ? -1 : 1;
  }

  function startsWith( $haystack, $needle ) {
   $length = strlen( $needle );
   return substr( $haystack, 0, $length ) === $needle;
  }

  if($_COOKIE["sort"]==="score")
   uasort($Db,"CompRating");
  else if($_COOKIE["sort"]==="naam")
   uasort($Db,"CompNaam");

  ?>
  <script>
   var N=<?php echo count($Db); ?>;
  </script>
  <div class="w3-container">
   <div class="main">

   <div class="w3-row"><h2 class="w3-third"><a href="index.php" class='no'>Mijn series</a></h2>
    <div class="w3-twothird">
     <div class="menu">
      <a href="#" id=sortscore title="Sorter op score"><img src="client/pix/sortster.png" class="w3-image" style='height:30px'/>&nbsp;Op score</a>
      <a href="#" id=sortaz title="Sorteer op titel"><img src="client/pix/sortaz.png" class="w3-image" style='height:30px'/>&nbsp;Op titel</a>
      <a href="#" id=nosort title="Sorteer op volgorde van binnenkomst"><img src="client/pix/nosort.png" class="w3-image" style='height:30px'/>&nbsp;Binnenkomst</a>
      <a href="#" id=laatste title="Scroll naar laatste"><img src="client/pix/last.png" class="w3-image" style='height:30px'/>&nbsp;Laatste</a>
      <a href="#" id="aNew" title='Nieuwe serie/film'><img src="client/pix/new.png" class="w3-image" style='height:30px'/>&nbsp;Nieuw</a>
     </div>

    </div>
   </div>

   <div class="filtermenu">
    <div><a href="zoeker.html" target=_blank>Zoeker</a></div>
    <div><input class='filter' id="Fbuitenlands" type="checkbox" checked>&nbsp;&nbsp;Buitenlands</div>
    <div><input class='filter' id="Fdrama" type="checkbox" checked>&nbsp;&nbsp;Drama</div>
    <div><input class='filter' id="Ffilms" type="checkbox" checked>&nbsp;&nbsp;Films</div>
    <div><input class='filter' id="Fhogescore" type="checkbox" checked>&nbsp;&nbsp;Hoge score</div>
    <div><input class='filter' id="Fnederlands" type="checkbox" checked>&nbsp;&nbsp;Nederlands</div>
    <div><input class='filter' id="Fseries" type="checkbox" checked>&nbsp;&nbsp;Series</div>
    <div><input class='filter' did="Fspannend" type="checkbox" checked>&nbsp;&nbsp;Spannend</div>
   </div>

   <div class="w3-row">

    <div class="w3-col kolom">
     <h3>Download-queue</h3>
     <div class="lijst" id="lijst1">
      <?php
       function Show($Locatie) {
        global $Db;

        $i=0;
        foreach ($Db as $R) {
         if($R["loc"]*1===$Locatie) {
          $Titel=$R["titel"];
          $Verhaal=str_replace("'","&#39;",$R["verhaal"]);
          $Subtitel=$R["subtitel"];
          $Score=$R["score"];
          $Plaats=$R["loc"];
          $Nr=$R["nr"];
          $IsFilm=$R["isfilm"];
          echo"<div class=item data-nr='$Nr' data-isfilm='$IsFilm' data-verhaal='$Verhaal' data-plaats='$Plaats'>
           <img src='Db/s$Nr.jpg' />
           <p data-titel>$Titel</p>
           <p data-soort class='klein'>$Subtitel</p>
           <div data-score class='nfw_score ".ScoreClass($Score)."'>$Score</div>
           <img class='isfilm ".($IsFilm?"":"hidden")."' src='client/pix/film.png'>
          </div>";
         }
         $i++;
        }
       }
       Show(1);

       function ScoreClass($s) {
        if($s>=8) return "nfw_circle_green";
        else if($s>=7.5) return "nfw_circle_yellow";
        else if($s>=5.0) return "nfw_circle_orange";
        else return "nfw_circle_red";
       }
      ?>

     </div>
    </div>

    <div class="w3-col kolom lefttab">
     <h3>Nieuw binnen/Te kijken</h3>
     <div class="lijst" id="lijst2">
      <?php Show(2); ?>
     </div>
    </div>

    <div class="w3-col kolom lefttab">
     <h3>Series</h3>
     <div class="lijst" id="lijst3">
      <?php Show(3); ?>
     </div>
    </div>

    <div class="w3-col kolom lefttab">
     <h3>Films</h3>
     <div class="lijst" id="lijst4">
      <?php Show(4); ?>
     </div>
    </div>

   </div><!-- Row -->
<!--
>=8 Groen
>=7.5 Geel
>=5.0 Oranje
<5 Rood

-->

  <div class="footer">
   <div id="Nemowebsite"><?php echo getcwd(); ?></div>
   <div id="Nemodownload">/home/marcel/DownloadPC</div>
   <div id="Series">Series</div>
   <div id="SeriesBig">Series Big</div>
   <a href="https://thetvdb.com" target="_blank">thetvdb.com</a>
   <a href="https://imdb.com" target="_blank">imdb.com</a>
  </div>
  </div><!-- Main -->

  <div id="dlgEdit" class="w3-modal"><form action="save.php" id="dlgForm" method="post" enctype="multipart/form-data">
   <div class="w3-modal-content">
    <div>
     <span onclick="document.getElementById('dlgEdit').style.display='none'"
     class="w3-button w3-display-topright">&times;</span>


      <div class="">
       <img id="iPlaatje" src="Db/s2.jpg" class="w3-image detail" />
       <a href="#" title="Bewerken" id='btnEdit'><img src="client/pix/edit.png" class="w3-image" style='width:40px' /></a>
       <input type="file" id="fAfbeelding" name="fAfbeelding" class="file">
      </div>

      </div>
      <div class="w3-padding">
       <input type='hidden' id="hVolgnummer" name="hVolgnummer" value="-1" >

      <input type='hidden' id="sSoortFix" name="sSoortFix" value="-1" >
      <input type='hidden' id="sPlaatsFix" name="sPlaatsFix" value="-1" >

       <input type='text' class="w3-input txt1" id="txtTitel" name="txtTitel" value="" >
       <select id="sSoort" name="sSoort">
        <option value="Nederlands">Nederlands</option>
        <option value="Nederlandse comedy-serie">Nederlandse comedy-serie</option>
        <option value="Nederlandse politieserie">Nederlandse politieserie</option>
        <option value="Comedy">Comedy</option>
        <option value="Politieserie">Politieserie</option>
        <option value="Spannend">Spannend</option>
        <option value="Drama">Drama</option>
       </select>&nbsp;&nbsp;&nbsp;

       <select id="sPlaats" name="sPlaats" style="margin-top:16px;">
        <option value="1">Download-queue</option>
        <option value="2">Nieuw binnen</option>
        <option value="3">Series</option>
        <option value="4">Films</option>
       </select>&nbsp;&nbsp;&nbsp;
       Score: <input type='text' class="w3-input txt" id="txtScore" name="txtScore" value="0.0" >
       <input id='cFilm' name='cFilm' type="checkbox" value="true"> Is film

       <textarea style="margin-top:16px;" id="txtOmschrijving" name="txtOmschrijving">
       </textarea>
      </div>


     <div class="w3-center"><input id="bOpslaan" class="w3-button w3-red" type=submit value="Opslaan"></div>
    </div>
   </div></form>
  </div><!--  Modal -->

  </div><!-- w3-container -->

<?php
die();

?>
 </body>
</html>
