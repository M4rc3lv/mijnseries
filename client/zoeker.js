$(function(){

 $("#z").keypress(function(e){
  if(e.which==13) {
   let z=$("#z").val();
   $("#tv").attr("src","https://thetvdb.com/search?query="+encodeURIComponent(z));
   $("#bol").attr("src","https://www.bol.com/nl/nl/s/?N=3133&searchtext="+encodeURIComponent(z));
   $("#imdb").attr("src","https://www.imdb.com/find?q="+encodeURIComponent(z));
   $("#mijn").attr("src","https://www.mijnserie.nl/zoeken/?search="+encodeURIComponent(z));
  }
 });

})
