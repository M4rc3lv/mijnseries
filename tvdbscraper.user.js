// ==UserScript==
// @name     TheTVDB Getter
// @version  1
// @include  https://thetvdb.com/series/*
// @include  https://thetvdb.com/movies/*
// @require  https://marcelv.net/client/jquery-3.5.1.min.js
// @resource selectimg http://localhost:5057/favicon.png
// @grant    GM.getValue
// @grant    GM.setValue
// @grant    GM.xmlHttpRequest
// @grant    GM.getResourceUrl
// ==/UserScript==

$(function(){
 TVDB();
   
 function TVDB() {
  var Titel = $("h1:first").text();
  var IMDBID = $("a:contains('IMDB')").attr("href");   
  if(IMDBID)console.log("IMDBID=",IMDBID); else console.log("Geen IMDB-link gevonden!");
   
  $("body").append("<audio id='MVDone' src='http://localhost:5057/client/done.ogg' type='audio/ogg' />");
   
   
  $(".change_translation_text:visible:first").append("<span id=MVCopy>Kopie</span><div>&nbsp;</div>");
  $("#MVCopy").css("border","solid 2px red").css("cursor","pointer").click(function(){
   let Tekst = $(".change_translation_text:visible:first p").text().trim();
   navigator.clipboard.writeText(Tekst);
   $('#MVDone')[0].play(); 
  });
   
  $("h1:first").after("<span id=MVCopy2>Kopie</span><div>&nbsp;</div>").css("margin-bottom","0");
  $("#MVCopy2").css("border","solid 2px red").css("cursor","pointer").click(function(){
   let Titel = $("h1:first").text().trim();
   // Plak het jaartal er achter
   let Jaar = $("strong:contains('First Aired')").next("span").text().trim().substr(-4);
   if(!Jaar) {
    Jaar = $("strong:contains('Released')").next("span").text().trim().substr(-4);
   }
   console.log("Jaar=",Jaar);
   let Tekst = Titel+" ("+Jaar+")";
   navigator.clipboard.writeText(Tekst);
   $('#MVDone')[0].play(); 
  });
   
  // Haal IMDB score op om te tonen zodat je die ook kunt kopieeren
  if(IMDBID) {
   let Imurl=$("a:contains('IMDB')").prop("href");
   GM.xmlHttpRequest({
    method:"GET",
    url:Imurl,
    onload:function(R) {
     $("span",R.responseText).each(function(){
      if($(this).prop("class").indexOf("RatingScore")>=0 && $("#MVScore").length==0) {      
       $("#MVCopy2").after("<div>&nbsp;</div>Score: <span id=MVScore>"+$(this).text().trim().replace(".",",")+"</span><span id=MVCopy3>Kopie</span>");
       $("#MVCopy3").css("border","solid 2px red").css("cursor","pointer").click(function(){
        navigator.clipboard.writeText($("#MVScore").text());
        $('#MVDone')[0].play();
       });
       return false;
      }
     });
    }
   });
  }
        
  (async function() {   console.log("yep");
   PlaatjeUrl = await GM.getResourceUrl("selectimg");  
   var IX=1;
   $("img").each(function(){ 
    $(this).after("<img class='pick' title='Download fan-art' style='cursor:pointer;width:32px;height:32px;position:absolute;z-index:100' id=MVI"+IX+">");
    let Pos=$(this).position();
    $("#MVI"+IX).css("left",(Pos.left+3)+"px");
    $("#MVI"+IX).css("top",(Pos.top+3)+"px");
    IX++;
   });
   $(".pick").prop("src",PlaatjeUrl);
    
   $(".pick").click(function(){
    let ImgUrl = $(this).prev().prop("src");
    GM.xmlHttpRequest({
     method:"GET",
     url: "http://localhost:5057/saveimg.php?img="+ImgUrl,
     onload: function(R) {
      $('#MVDone')[0].play();      
     }
    });
    return false;   
   });
    
  })();
   
  console.log("einde");     
 }
                       
 

  
})


