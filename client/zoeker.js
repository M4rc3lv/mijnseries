$(function(){

 $("#z").keypress(function(e){
  if(e.which==13) {
   let z=$("#z").val();
   $("#tv").attr("src","https://thetvdb.com/search?query="+encodeURIComponent(z));
   $("#imdb").attr("src","https://www.imdb.com/find?q="+encodeURIComponent(z));
  }
 });

})
