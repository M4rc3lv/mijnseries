<html lang="nl">
 <head>
  <link rel="stylesheet" href="client/w3.css">
  <link rel="stylesheet" href="client/series.css">
  <script src="client/jquery-3.6.0.min.js"></script>
  <script src="client/jquery.caret.js"></script>
  <script src="client/dragula.min.js"></script>
  <script src="client/series.js"></script>
  <title>Mijn Series</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
 </head>
 <body>
  <?php
  $Db = json_decode(file_get_contents('Db/data.json'), true);
  $LastViewed = $_COOKIE["last"]?  $_COOKIE["last"]*1 : 1;

  function CompRating($a,$b) {
   if($a["score"]===$b["score"]) return 0;
   return ($a["score"] > $b["score"]) ? -1 : 1;
  }

  function CompNieuw($a,$b) {
   if($a["nr"]===$b["nr"]) return 0;
   return ($a["nr"] > $b["nr"]) ? -1 : 1;
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

  if($_COOKIE["sort"]==="score") {
   uasort($Db,"CompRating");
  }
  else if($_COOKIE["sort"]==="naam") {
   uasort($Db,"CompNaam");
  }
  else if($_COOKIE["sort"]==="nieuw") {
   uasort($Db,"CompNieuw");
  }
  else {
  }
  ?>

  <div class="m">

   <div class="w3-row">
    <div class="w3-third">
     <div class="w3-container nopadL">
      <h2 class='rood'>Mijn Series</h2>
      <div>
       <div class='form'>
        <label>Zoek titel:</label>
        <input type='text' id='txtZoek' class='txt' placeholder="Filter titels" ><br>
        <label>Sorteren:</label>
        <select id="sSorteren" class='txt'><option value="naam">Alfabetisch</option><option value="nieuw">Nieuwste eerst</option><option value="nosort">Oudste eerst</option><option value="score">Hoogste score eerst</option></select><br>
        <label>Filter taal:</label>
        <select id='sFiltertaal' class='txt'><option value='1'>Toon alles</option><option value='NL'>Nederlands</option></select><br>
        <label>Genre:</label>
        <select id='sFiltergenre' class='txt'>
         <option value="1">Toon alles</option>
          <option value="Comedy">Comedy</option>
           <option value="Drama/Romantiek">Drama/Romantiek</option>
           <option value="Highschool/Jeugd">Highschool/Jeugd</option>
           <option value="Politie">Politie</option>
           <option value="Science fiction">Science fiction</option>
           <option value="Spannend/Thriller">Spannend/Thriller</option>
        </select><br>
       </div>
      </div>



     </div>
    </div>
    <div class="w3-twothird">
     <div class="hpimg">
      <h2 id='dTitel'></h2>
      <span id='dIx'></span>
      <span id='dScore' class='groen'></span>
      <span id='dJaar' class='geel'></span>
      <span id='dGenre' class='geel'></span>
      <span class='box nobor' id='spanNL'><img src='/pix/nl.jpg'></span>
      <span class='box nobor' id='spanBE'><img src='/pix/be.jpg'></span>
      <span class='box'><img src='pix/series.png' /><span id="AantalSeizoenen"></span></span>
      <span class='box' id="nlsubs">nlsubs</span>
      <span class='box rood' id="nosubs">no subs</span>
      <span class='box' id="isfilm"><img src='pix/movie.png' /></span>
      <div id='dTekst' class='tekst'></div>

      <!-- Buttons om te editen -->
     <div class='iconbar'>

      <button class='knopp' id='bEdit' title="Bewerken"><img src='pix/edit.png'></button>
      <button class='knopp' id='bNew' title="Nieuw"><img src='pix/new.png'></button>
     </div>

     </div>
    </div> <!--twothird-->
   </div><!-- row -->

   <div class="w3-row">

    <div class="w3-quarter"><div class='kolpadding'>
     <h3>Download-queue</h3>
     <div class="lijst" id="lijst1"><?php  Show(1); ?></div>
    </div></div>

    <div class="w3-quarter"><div class='kolpadding'>
     <h3>Te kijken</h3>
     <div class="lijst" id="lijst2"><?php  Show(2); ?></div>
    </div></div>

    <div class="w3-quarter"><div class='kolpadding'>
     <h3>Series</h3>
     <div class="lijst" id="lijst3"><?php  Show(3); ?></div>
    </div></div>

    <div class="w3-quarter"><div class='kolpadding'>
     <h3>Films</h3>
     <div class="lijst" id="lijst4"><?php  Show(4); ?></div>
    </div></div>

   </div><!-- row (onderste) -->
  </div><!-- m -->


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
          $IsFilm=$R["isfilm"]? true : false;

          $Subs=$R["subs"];
          $Lang=$R["lang"]; //if($Nr*1===9) {var_dump($R);die();}
          $Seizoenen=$R["seizoenen"];
          $Fysiek=$R["fysiek"];
          echo"<div class=item data-nr='$Nr' data-subs='$Subs' data-lang='$Lang' data-seizoenen='$Seizoenen' data-fysiek='$Fysiek' data-isfilm='$IsFilm' data-verhaal='$Verhaal' data-plaats='$Plaats'>
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


       function ScoreClass($s) {
        if($s>=8) return "nfw_circle_green";
        else if($s>=7.5) return "nfw_circle_yellow";
        else if($s>=5.0) return "nfw_circle_orange";
        else return "nfw_circle_red";
       }
      ?>

  <div id="dlgEdit" class="w3-modal">
   <form action="save.php" id="dlgForm" method="post" enctype="multipart/form-data">
    <div class="w3-modal-content">
     <span onclick="document.getElementById('dlgEdit').style.display='none'" class="w3-button w3-display-topright">&times;</span>
     <div class="w3-padding">
      <div class="w3-row">
       <div class="w3-col" style="width:656px" >
        <img id="iPlaatje" src="Db/s2.jpg" class="w3-image previewpic" />
        <div class='dialoogform leftdial'>
         <label>Taal:</label>
         <span class="formborder"><input type='radio' name='rLang' value="NL">&nbsp;NL</span>
         <span class="formborder"><input type='radio' name='rLang' value="DE">&nbsp;Duits</span>
         <span class="formborder"><input type='radio' name='rLang' value="BE">&nbsp;Vlaams</span>
         <span class="formborder"><input type='radio' name='rLang'  value="Anders"checked>&nbsp;Anders</span>
        <div>
        <div class='dialoogform leftdial'>
         <label>Type:</label>
         <span class="formborder"><input type='radio' name='rType' value="true">&nbsp;Film</span>
         <span class="formborder"><input type='radio' name='rType' value="false" checked>&nbsp;Serie
          met <input type=tekst class='serietxt' id="txtSeizoenen" name="txtSeizoenen" value='1'> seizoen(en)
         </span>
        </div>

        <div class='dialoogform leftdial'>
         <label>Ondertiteling:</label>
         <span class="formborder"><input type='radio' name='rSubs' value="nlsubs" checked>&nbsp;NL-subs</span>
         <span class="formborder"><input type='radio' name='rSubs' value="nosubs" >&nbsp;Geen subs</span>
        </div>


       </div>
       </div>

       </div><!-- Linker kolom -->

       <div class="w3-rest dialoogform">
        <label>Afbeelding 640x380:</label>
        <input type="file" id="fAfbeelding" name="fAfbeelding" class='txt'>

        <label>Titel:</label>
        <input id='txtTitel' name="txtTitel" type=text class="txt">

        <div class="w3-row">
         <div class="w3-third">
          <label>IMDB-Score:</label>
          <input type=text id="txtScore" name="txtScore" class="txt">
         </div>

         <div class="w3-twothird w3-padding-left">
          <label>Genre:</label>
          <select id="sSoort" name="sSoort" class='txt'>
           <option value="Comedy">Comedy</option>
           <option value="Drama/Romantiek">Drama/Romantiek</option>
           <option value="Highschool/Jeugd">Highschool/Jeugd</option>
           <option value="Politie">Politie</option>
           <option value="Science fiction">Science fiction</option>
           <option value="Spannend/Thriller">Spannend/Thriller</option>
          </select>
         </div>
        </div><!-- score & Genre -->

        <label>Verhaal:</label>
        <textarea id="txtOmschrijving" name="txtOmschrijving" class="txt"></textarea>

        <label>Locatie:</label>
        <input type='radio' name='locatie' value='1' checked>&nbsp;Download-queue<div class='MARGL16'></div>
        <input type='radio' name='locatie' value='2'>&nbsp;Nieuw binnen/te kijken<div class='MARGL16'></div>
        <input type='radio' name='locatie' value='3'>&nbsp;Series<div class='MARGL16'></div>
        <input type='radio' name='locatie' value='4'>&nbsp;Films<div class='MARGL16'></div>


       </div>

       <input type='hidden' id="hVolgnummer" name="hVolgnummer" value="-1" >
      <input type='hidden' id="sSoortFix" name="sSoortFix" value="-1" >
      <input type='hidden' id="sPlaatsFix" name="sPlaatsFix" value="-1" >
      <input type='hidden' id="sFysiekFix" name="sFysiekFix" value="-1" >
      </div>


      <div class='knoponder1'><button id="btnOK" class='w3-button knop w3-green'>OK</button></div>
      <div class='knoponder2'><button class='w3-button knop w3-green'>Annuleren</button> </div>


     </div>
    </div>
   </form>
  </div>

 </body>
</html>
