<?php

$date = isset($_POST['date'])?$_POST['date']:Null;
echo $date;
if ($date != Null) {
    $listeRV = $Application->listeHeureRV($date);
}
echo "toto";
afficher($listeRV);
