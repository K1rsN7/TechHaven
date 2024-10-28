<?php
    require('./header.php');
    require('./db.php');
    $sql_brands=$link->query("SELECT * FROM brand ORDER BY name_brand");

    $page=$_GET['page'];
    if(!isset($page) or $page=='content'){
        require('./content.php');
    }
    elseif ($page=='contact'){
        require('./contact.php');
    }

    require('./footer.php');

?>