<html>
 <head>
  <link rel="stylesheet" href="client/w3.css">
  <script src="client/jquery-3.6.0.min.js"></script>
  <script src="client/zoeker.js"></script>
  <style>
   body,div,iframe {
    background:black;
    color:white;
   }
   input {background:#3d3c3a;color:white}
  </style>
 </head>
 <body>
 <?php
/*
 Algoritme: 
 - Scrape thetvdb.com en neem eerste titel.
 - Als die geen IMDBlink heeft dan scrape je ook IMDB en toon je twee titels.
 - Toon thetvdb.com en IMDB frames. En klik dan de GM scrape button in dat frame
            
*/

?>

  <script>
   function O(URL,naam) {
    console.log("URL=",URL);
    var W = window.open(URL, naam);
   }
  </script>
  <div class="w3-padding">
   <input id="z" type="text" class="w3-input" placeholder="Zoeken...">
  </div>
  <div class="w3-rowpadding">
   <div style="width:470px" class="w3-col">
    <button onclick="O( $('#tv').prop('src') ,'TVDB')">Nieuw venster</button>
   </div>
   <div style="width:470px" class="w3-col">
    <button onclick="O( $('#bol').prop('src') ,'TVDB')">Nieuw venster</button>
   </div>
   <div style="width:460px" class="w3-col">
    <button onclick="O( $('#imdb').prop('src') ,'TVDB')">Nieuw venster</button>
   </div>
   <div style="width:440px" class="w3-col">
    <button onclick="O( $('#mijn').prop('src') ,'TVDB')">Nieuw venster</button>
   </div>
  </div>
  <div class="w3-row">
   <iframe id="tv" width=450 height=800 src="" class="w3-quarter"></iframe>
   <iframe id="bol" width=450 height=800 src="" class="w3-quarter"></iframe>
   <iframe id="imdb" width=450 height=800 src="" class="w3-quarter"></iframe>
   <iframe id="mijn" width=450 height=800 src="" class="w3-quarter"></iframe>
  </div>
 </body>
</html>




