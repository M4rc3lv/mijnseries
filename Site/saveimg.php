<?php
// Haal plaatje op en bewaart het onder een vaste naam
// Wordt aangeroepen vanuit GreaseMonkey

$url=$_GET["img"];
file_put_contents("/media/marcel/4TB/ff/!MijnSeries.jpg",file_get_contents($url));
echo "OK";



