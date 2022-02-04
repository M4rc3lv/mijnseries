$(function(){
 function Nemo1() {  $.ajax({url:"command.php?command=nemo1"}); }
 function Nemo2() {  $.ajax({url:"command.php?command=nemo2"}); }
 function Nemo3() {  $.ajax({url:"command.php?command=series"}); }
 function Nemo4() {  $.ajax({url:"command.php?command=seriesbig"}); }
 $("#Nemowebsite").click(Nemo1);
 $("#Nemodownload").click(Nemo2);
 $("#Series").click(Nemo3);
 $("#SeriesBig").click(Nemo4);

 ReadOnly();

 $("#aNew").click(function(){
  $("#fAfbeelding").val("");
  $("#iPlaatje").attr("src","");
  $("#txtTitel").val("Nieuwe film/serie");
  $("#sSoort").val("Nederlandse comedy-serie");
  $("#sPlaats").val(1);
  $("txtScore").val(0);
  $("#txtOmschrijving").val("");
  CmdHandler();
  New();
 });

 $(".item").click(function(){
  let ix=$(this).attr("data-nr");
  $("#hVolgnummer").val(ix);
  $("#txtTitel").val($(this).children("p[data-titel]").html());
  $("#iPlaatje").attr("src","Db/s"+ix+".jpg");
  $("#sSoort").val($(this).children("p[data-soort]").text());
  $("#txtScore").val($(this).children("div[data-score]").html());
  $("#txtOmschrijving").val($(this).attr("data-verhaal"));
  $("#sPlaats").val($(this).attr("data-plaats"));
  if( $(this).attr("data-isfilm") )
   {$("#cFilm").prop("checked",true); }
  else
   $("#cFilm").prop("checked",false);//removeProp("checked").removeAttr("checked");
  ReadOnly();
  Edit();
 });

 $("#btnEdit").click(EditModus);

 function ReadOnly() {
  $("input").prop('readonly', true).css("border","none").css("outline","none!important");
  $("textarea").prop('readonly', true).css("border","none");
  $("select").prop('disabled', "disabled").css("outline","none").css("border","none").css("appearance","none");
  $("input[type=file]").hide();
  $("#bOpslaan").hide();
  $("input[type=checkbox]").prop('disabled', "disabled");
  $("#btnEdit").show();

  $(".filter").prop("readonly",false).prop("disabled",false);
 }

 function EditModus() {
  $("input").prop('readonly', false).css("border","solid 1px grey");
  $("textarea").prop('readonly', false).css("border","solid 1px grey");
  $("select").prop('disabled', false).css("appearance","auto").css("border","solid 1px grey");
  $("input[type=file]").show();
  $("#bOpslaan").show();
  $("input[type=checkbox]").prop('disabled', false);
  $("#btnEdit").hide();
 }

 function New() {
  $("#hVolgnummer").val(-1);
  $("#fAfbeelding").val("");
  $("#dlgEdit").show();
  EditModus();
 }
 function Edit() {
  $("#fAfbeelding").val("");
  $("#dlgEdit").show();
 }

 $("#fAfbeelding").change(CmdHandler);
 $("#sPlaats").change(CmdHandler);

 function CmdHandler() {
  $("#bOpslaan").show();
 }

 $("#bOpslaan").click(function() {
  // Save!
  $("#dlgEdit").hide();
 });

 $(document).keyup(function(ev) {
  if(ev.which==27) $("#dlgEdit").hide();
 });

 window.onclick = function(event) {
  if (event.target == $("#dlgEdit")[0]) {
   $("#dlgEdit").hide();
  }
 }

 // Scroll naar laatst nieuw toegevoegd element
 const params = new URLSearchParams(window.location.search);
 const lastid = params.get("lastid");
 if(lastid) {
  ScrollTo(lastid);
 }

 function ScrollTo(id) {
  $("div[data-nr="+(id)+"]")[0].scrollIntoView();
  $("div[data-nr="+(id)+"]").addClass("last");
 }

 function ScrollLast() {
  $("div[data-nr="+(N-1)+"]")[0].scrollIntoView();
  $("div[data-nr="+(N-1)+"]").addClass("last");
 }
 $("#laatste").click(ScrollLast);

 // Sorteren
 $("#sortaz").click(function(){
  document.cookie = "sort=naam; path=/; max-age=" + 30*24*60*60;
  location.href="index.php";
 });

 $("#sortscore").click(function(){
  document.cookie = "sort=score; path=/; max-age=" + 30*24*60*60;
  location.href="index.php";
 });

 $("#nosort").click(function() {
  document.cookie = "sort=nosort; path=/; max-age=" + 30*24*60*60;
  location.href="index.php";
 });

 // Scraper
 $("#txtOmschrijving").bind("paste", function(e){
  console.log("Paste-functie geactiveerd 1");
  var pastedData = e.originalEvent.clipboardData.getData('text');
  if(pastedData.startsWith("https://thetvdb.com/")) {
   console.log("Paste-functie geactiveerd 2 (thetvdb.com)");
   $.ajax({url:"scrape.php?url="+encodeURIComponent(pastedData)}).done(function(data){
    console.log(data);
    $("#txtOmschrijving").val(data.split("###")[0]);
    $("#txtScore").val(data.split("###")[1].replace(".",","));
    $("#txtTitel").val(data.split("###")[2].trim() + " ("+ data.split("###")[3].trim() + ")");
   });
  }
 });

 // Filters
 $(".filter").click(function(){
  DoFilter();// $(this).is(':checked') );
 });

 function DoFilter() {
  $(".lijst > .item").show();
  $(".lijst > .item").each(function(ix,val) {
   if( !$("#Ffilms")[0].checked && $(this).attr("data-isfilm")) $(this).hide();
   if( !$("#Fbuitenlands")[0].checked ) {
    let Soort=$("p[data-soort]",$(this)).text();
    if(Soort==="Comedy" || Soort==="Politieserie"  || Soort==="Spannend"  || Soort==="Drama")$(this).hide();
   }


  });
 }

 // Drag drop
 let drag = dragula([document.querySelector('#lijst1'),
  document.querySelector('#lijst2'),
  document.querySelector('#lijst3'),
  document.querySelector('#lijst4')
 ]);
 drag.on("drop",function(el, target, source, sibling){
  let idTarget=$(target).attr("id");
  let idSerie=$(el).attr("data-nr");
  let Verhaal =$(el).attr("data-verhaal");
  if(idTarget.startsWith("lijst")) {
   let NieuweLocatie=idTarget.substr(5);
   console.log("Verplaats "+idSerie+" naar "+idTarget);
   $(target).attr("data-loc",NieuweLocatie);

   // Zet alles in de dialoog en post het om te saven.
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

});
