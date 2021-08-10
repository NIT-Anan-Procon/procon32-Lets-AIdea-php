<?php

require_once('./library.php');

$userID = "kubota";
$explanation = "これはテストです";
$pictureURL = "url";
$library = new library();
$user = 'kinoshita';
$library -> UploadLibrary($userID, $explanation, $pictureURL);
$result = $library -> ListLibrary();
//var_dump($result);
for($i = 0; $i < count($result); $i++){
    for($j = 0; $j <= 3; $j++){
        echo $result["$i"]["$j"];
        echo "&nbsp&nbsp&nbsp&nbsp";
    }
    echo '<br>';
}
?>