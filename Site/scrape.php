<?php
 require 'vendor/autoload.php';
 $httpClient = new \GuzzleHttp\Client();
 $response = $httpClient->get($_GET["url"]);//'https://thetvdb.com/series/the-rookie');
 $htmlString = (string) $response->getBody();
 //add this line to suppress any warnings
 libxml_use_internal_errors(true);
 $doc = new DOMDocument();
 $doc->loadHTML($htmlString);
 $xpath = new DOMXPath($doc);

 // /html/body/div[4]/div[2]/div[1]/div[2]/ul/li[13]/span[4]/a

 $Verhaal = $xpath->evaluate('//body//div[@data-language="nld"]/p');
 $extractedTitles = [];
 foreach ($Verhaal as $title) {
  $extractedTitles[] = $title->textContent.PHP_EOL;
  echo $title->textContent.PHP_EOL;
 }

 echo "###";

 $Links = $xpath->evaluate('//body//a');
 foreach($Links as $L) {
  if($L->textContent==="IMDB")
   $IMDBLink=$L->attributes["href"]->value;
 }

 // Scrape titel
 $Titel = $xpath->evaluate('//body//h1[1]')[0]->textContent;

 // Jaar
 $Jaar = trim($xpath->evaluate('/html/body/div[4]/div[2]/div[1]/div[2]/ul/li[3]/span')[0]->textContent);
 $Jaar=substr($Jaar,-4);

 if($IMDBLink) {

  $response = $httpClient->get($IMDBLink);
  $htmlString = (string) $response->getBody();
  //add this line to suppress any warnings
  libxml_use_internal_errors(true);
  $doc = new DOMDocument();
  $doc->loadHTML($htmlString);
  $xpath = new DOMXPath($doc);

  $Score = $xpath->evaluate('/html/body/div[2]/main/div/section[1]/section/div[3]/section/section/div[1]/div[2]/div/div[1]/a/div/div/div[2]/div[1]/span[1]');
  echo $Score [0]->textContent;

  // Beter Jaar (van IMDB
  $Jaar = trim($xpath->evaluate("/html/body/div[2]/main/div/section[1]/section/div[3]/section/section/div[1]/div[1]/div[2]/ul/li[2]/a")[0]->textContent);
  $Jaar=substr($Jaar,0,4);
 }

 echo "###$Titel";

 echo "###$Jaar";

?>


