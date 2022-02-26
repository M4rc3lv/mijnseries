$(function(){

 $("#btnEdit").click(function(){
  $("#dlgEdit").show();
 });

//
 //$(".hpimg").css("background-image","linear-gradient(to right, rgba(0,0,0,1), rgba(0,0,0,0)),url('../Db/s66.jpg')");
 //$(".tekst").html("Vlaams. Tuur, Babs, Arne en Kato kennen elkaar niet en toch belandt in hun brievenbus een brief die de vier jongelui zal samenbrengen voor een groot avontuur. De brief nodigt hen uit voor een kamp in het openluchtmuseum en komt van Cara. Zij is een wat excentrieke kruidendame die grote waterkrachten bezit.");

 $(".item").click(function(){
  doClick($(this),false);
 });

 function doClick(elem,inview) {
  let ix=$(elem).attr("data-nr");
  // UI

  if(inview) $(elem)[0].scrollIntoView();
  $(".item").removeClass("last");
  $(elem).addClass("last");

  var TitelEnJaar=$(elem).children("p[data-titel]").text();
  var Titel = TitelEnJaar.split(" (")[0];
  var Jaar = TitelEnJaar.split(" (")[1].substr(0,4);
  $("#dTitel").text(Titel);
  $("#dScore").html($(elem).children("div[data-score]").text());
  $("#dJaar").text(Jaar);
  //$("#dImage").attr("src","Db/s"+ix+".jpg");
  $(".hpimg").css("background-image","linear-gradient(to right, rgba(0,0,0,1), rgba(0,0,0,0)),url('../Db/s"+ix+".jpg')");
  $("#dGenre").text($(elem).children("p[data-soort]").text());
  $("#dTekst").html($(elem).attr("data-verhaal"));
  $("#dIx").text(ix);
  //alert($(this).attr("data-lang"));
  if($(elem).attr("data-lang")==="NL") $("#spanNL").show(); else $("#spanNL").hide();
  if($(elem).attr("data-lang")==="BE") $("#spanBE").show(); else $("#spanBE").hide();
  if($(elem).attr("data-lang")==="DE") $("#spanDE").show(); else $("#spanDE").hide();
  $("#nlsubs").hide();
  $("#nosubs").hide();
  if($(elem).attr("data-subs")==="nlsubs") $("#nlsubs").show(); else $("#nosubs").show();
  $("#AantalSeizoenen").text($(elem).attr("data-seizoenen"));
  if($(elem).attr("data-isfilm"))$("#isfilm").show();else $("#isfilm").hide();

  document.cookie = "lastix="+ix+"; path=/; max-age=" + 30*24*60*60;

  // Eeen paar dingen zijn niet zichtbaar: die zet ik in de dialoog elke keer
  $("input:radio[name=locatie]").val([$(elem).attr("data-plaats")]);
  $("#txtOmschrijving").val($(elem).attr("data-verhaal"));
  $("input:radio[name=rLang]").val([$(elem).attr("data-lang")]);
  $("input:radio[name=rSubs]").val([$(elem).attr("data-subs")]);
  $("input:radio[name=rType]").val([$(elem).attr("data-isfilm")? 'true':'false']); // true of false

  //$("#sFysiek").val([$(elem).attr("data-fysiek")]);
  $("#sSoort").val([$("#dGenre").text()]);
 }

 // Zoek item met lastix en laad die in de GUI
 var lastix=getCookie("lastix");
 doClick($(".item[data-nr="+lastix+"]"),true);

 $(".item").dblclick(function(){
  $("#bEdit").click();
 });

 $("#bNew").click(New);
 function New() {
  $("#hVolgnummer").val(-1);
  $("#fAfbeelding").val("");
  $("#iPlaatje").attr("src","");
  $("input:radio[name=rLang]").val(["NL"]);
  $("input:radio[name=rType]").val(["true"]);
  $("#txtSeizoenen").val("");
  $("input:radio[name=rSubs]").val(["nlsubs"]);
  $("#txtScore").val("");
  $("#txtTitel").val("");
  $("#txtOmschrijving").val("");
  $("#sSoort").val("");
  $("#dlgEdit").show();
 }

 $("#bEdit").click(function(){
  let ix=$("#dIx").text();
  $("#hVolgnummer").val(ix);
  //alert(ix);
  //Gebeurt hierboven al $("#locatie").val($(this).attr("data-plaats"));
  $("#iPlaatje").attr("src","Db/s"+ix+".jpg");
  $("#txtTitel").val($("#dTitel").text()+" ("+$("#dJaar").text()+")");
  $("#txtScore").val($("#dScore").text());
  $("#dlgEdit").show();
 });

 $("#btnOK").click(function(){
  document.cookie = "lastix="+$("#dIx").text()+"; path=/; max-age=" + 30*24*60*60;
  $("form").submit();
 });

 $(document).keyup(function(ev) {
  if(ev.which==27) $("#dlgEdit").hide();
 });

 // Zoeken (filteren op titel)
 $("#txtZoek").keyup(function(){
  let Zoek=$(this).val().toLowerCase();
  $(".lijst > .item").show();
  if(Zoek.length==0) return;
  console.log("Zoek=",Zoek);
  $(".lijst > .item").each(function(ix,val) {
   let titel=$("p[data-titel]",this).text().toLowerCase();
   console.log(titel);
   if(titel.indexOf(Zoek)<0) $(this).hide();
  });
 });

 // Filter op taal
 $("#sFiltertaal").change(function(){
  $("#sFiltergenre").val("1");
  let val=$(this).val();
  $(".lijst > .item").show();
  if(val==="NL") {
   $(".lijst > .item").each(function(ix,val) {
    let lang=$(this).attr("data-lang");
    if(lang!=="BE" && lang!=="NL")
     $(this).hide();
   });
  }
 });

 // Filter op genre
 $("#sFiltergenre").change(function(){
  $("#sFiltertaal").val("1");
  let val=$(this).val();
  $(".lijst > .item").show();
  if(val==="1") return;
  $(".lijst > .item").each(function(ix,v) {
   let genre=$("p[data-soort]",$(this)).text();
   console.log(genre+"L"+val);
   if(genre!==val)
    $(this).hide();
  });

 });

  // Drag drop
 let drag = dragula([document.querySelector('#lijst1'),
  document.querySelector('#lijst2'),
  document.querySelector('#lijst3'),
  document.querySelector('#lijst4')
 ]);
 drag.on("drop",function(el, target, source, sibling){alert("TO DO!");return;
  let idTarget=$(target).attr("id");
  let idSerie=$(el).attr("data-nr");
  let Verhaal =$(el).attr("data-verhaal");
  if(idTarget.startsWith("lijst")) {
   let NieuweLocatie=idTarget.substr(5);
   console.log("Verplaats "+idSerie+" naar "+idTarget);
   $(target).attr("data-loc",NieuweLocatie);

   // Zet alles in de dialoog en post het om te saven.
// TO DO!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
   $("#hVolgnummer").val(idSerie);
   $("#txtTitel").val( $('p[data-titel]',el).html() );
   $("#txtOmschrijving").val( Verhaal );
   $("#sSoort").val( $('p[data-soort]',el).html() );
   $("#sSoortFix").val( $('p[data-soort]',el).html() );
   $("#sPlaats").val( NieuweLocatie );
   $("#sPlaatsFix").val( NieuweLocatie );
   $("#txtScore").val( $('div[data-score]',el).html() );
   $("#cFilm").val( !$("img.isfilm",el).hasClass("hidden") );
   $("#dlgForm").submit();
  }
 });

 // Sorteren
 $("#sSorteren").val( getCookie("sort") );
 $("#sSorteren").change(function(){
  document.cookie = "sort="+$(this).val()+"; path=/; max-age=" + 30*24*60*60;
  location.href="index.php";
 });

 function getCookie(cName) {
  const name = cName + "=";
  const cDecoded = decodeURIComponent(document.cookie); //to be careful
  const cArr = cDecoded.split('; ');
  let res;
  cArr.forEach(val => {
    if (val.indexOf(name) === 0) res = val.substring(name.length);
  })
  return res
 }

})
