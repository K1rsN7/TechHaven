<?php
    require('./header.php');
    require('./db.php');
    $sql_brands=$link->query("SELECT * FROM brand");

    if(!isset($page) or $page=='content'){
        require('./content.php');
    }

    require('./footer.php');

?>