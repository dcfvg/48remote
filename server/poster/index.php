<?php

date_default_timezone_set('Europe/Paris');

$m_path = "../assets/00-import/*.*";
$files = glob($m_path);

function insec($t){
  $t = str_replace(" !", "&nbsp;!", $t);
  $t = str_replace(" ?", "&nbsp;?", $t);
  $t = str_replace(" ?", "&nbsp;?", $t);
  
  return $t;
  
}
function cutwords($text){
  
  $txts = explode(" ",$text);
  
  $lignes = 4;
  $w_ligne = round((count($txts)+1)/$lignes);
  
  foreach ($txts as $id => $t) {
    $s .= " ".$t;
    
    if($id % $w_ligne == $w_ligne-1) {
      $p .= '<span>'.$s.'</span>';
      $s ="";
    }
  }
  
  $p .= '<span>'.$s.'</span>';
  
  // echo "$text > $w_tot \n";
  
  return $p;
  
}
function cutwords2($text){
  
  $nb_chars = strlen($text);
  $nb_w = str_word_count($text);
  
  $nb_lines = 8;
  if($nb_w < 20 ) $nb_lines = 7;
  if($nb_w < 15 ) $nb_lines = 5;
  if($nb_w < 12 ) $nb_lines = 4;
  if($nb_w < 10 ) $nb_lines = 3;
  if($nb_w < 5 ) $nb_lines = 2;
  
  
  $charPerLine = ceil($nb_chars/$nb_lines);
  
  $text = wordwrap(insec($text), $charPerLine, "<!>");
  $lines = explode("<!>",$text);
  
  foreach ($lines as $id => $l) {
    $p .= "<span>$l</span>";
  }
  return $p;
}

foreach (array_reverse($files) as $id => $file) {
  
  $f = pathinfo($file);

    
  $datetime = DateTime::createFromFormat( 'Ymd_His', $f["filename"], new DateTimeZone('Europe/Paris'));
  $timestamp = $datetime->getTimestamp();
  $date = "48°54' :: ".date('H:i:s',$timestamp);
  
  switch (strtolower($f['extension'])) {
    case 'jpg':
      $list .= '<li class="image" style="background-image:url('.$file.')"> <sup>'.$date.'<sup></li>';
      break;
    case 'md':
      $list .= '<li class="md">
        <sup>'.$date.'</sup><p class="bigtext">'.cutwords2(file_get_contents($file)).'</p></li>';
      break;
  }
}

$nb_f = count($files);

$p = 100/5;

if($nb_f > 1) $p = 100/2;
if($nb_f > 4) $p = 100/4;
if($nb_f > 16) $p = 100/6;

?>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="css/screen.css">
    
    <style type="text/css" media="screen">
      table {
        width:<?php echo $p ?>%;
        height:<?php echo $p ?>%;
      }
    </style>
  </head>
  <body>
    <ol>
      <?php echo $list ?>
    </ol>
    

    
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/BigText/bigtext.js"></script>

    <script>
      $('.bigtext').bigtext({
          maxfontsize: 250 // default is 48 (in px)
      });
    </script>
  </body>
</html>