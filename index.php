<?php
    session_start();
    require('./header.php');
    require('./db.php');

    $sql_category=$link->query("SELECT c.id_category, c.name_category, count(p.name_product) as count FROM product p LEFT JOIN category c ON p.id_category = c.id_category GROUP BY c.name_category ORDER BY count DESC");

    if(!isset($_SESSION['sql_zap_product'])){
        $_SESSION['sql_zap_product']="SELECT p.id_product, p.image, p.name_product, p.description, p.price, p.delivery_days, b.id_brand, b.name_brand, b.image_brand FROM product p LEFT JOIN category c ON p.id_category = c.id_category LEFT JOIN brand b ON p.id_brand = b.id_brand ";
        $_SESSION['count_products_on_page'] = 6;
    }

    function divideWithRemainder($a, $b) {
    
        // Целочисленное деление
        $result = intdiv($a, $b);
    
        // Проверка остатка от деления
        if ($a % $b !== 0) {
            $result += 1; 
        }
    
        return $result;
    }


    $sql_text=$_SESSION['sql_zap_product'];
    $sql_product=$link->query($sql_text);
    $sql_product_count=(int)$link->query("SELECT COUNT(*) FROM ($sql_text) AS subquery");
    $sql_brands=$link->query("SELECT subquery.id_brand, subquery.name_brand, subquery.image_brand, COUNT(subquery.id_product) as count FROM ($sql_text) AS subquery GROUP BY subquery.id_brand, subquery.name_brand, subquery.image_brand");
    $sql_delivery=$link->query("SELECT subquery.delivery_days, COUNT(subquery.id_product) as count FROM ($sql_text) AS subquery GROUP BY subquery.delivery_days");
    $sql_min_price=$link->query("SELECT MIN(subquery.price) FROM ($sql_text) AS subquery");
    $sql_max_price=$link->query("SELECT MAX(subquery.price) FROM ($sql_text) AS subquery");
    $total_pages = divideWithRemainder($sql_product_count, $_SESSION["count_products_on_page"]);

    $page=$_GET['page'];
    if(!isset($page) or $page=='content'){
        require('./content.php');
    }
    elseif ($page=='contact'){
        require('./contact.php');
    }
    elseif ($page=='shop'){
        unset($_SESSION['sql_zap_product']);
        require('./shop.php');
    }
    elseif ($page=='sort'){
        if ($_GET['count_products_on_page']) $_SESSION["count_products_on_page"] = $_GET['count_products_on_page'];
        $_SESSION['count_products_on_page'];
        $id_sort = $_GET['id_sort'];
        $id_cat = $_GET['id_cat'];
        $request = $_SESSION['sql_zap_product'];
        if ($id_cat) $request .= " WHERE c.id_category = '$id_cat'"; 
        switch ($id_sort){
            case '1':
                $request .= " ORDER BY p.name_product ASC";
                break;
            case '2':
                $request .= "  ORDER BY p.name_product DESC";
                break;
            case '3':
                $request .= " ORDER BY p.price ASC";
                break;
            case '4':
                $request .=  " ORDER BY p.price DESC";
                break;
        }
        $sql_product=$link->query($request);
        $sql_product_count=(int)$link->query("SELECT COUNT(*) FROM ($request) AS subquery");
        $sql_brands = $link->query("SELECT subquery.id_brand, subquery.name_brand, subquery.image_brand, COUNT(subquery.id_product) as count FROM ($request) AS subquery GROUP BY subquery.id_brand, subquery.name_brand, subquery.image_brand");
        $sql_delivery=$link->query("SELECT subquery.delivery_days, COUNT(subquery.id_product) as count FROM ($request) AS subquery GROUP BY subquery.delivery_days");
        $total_pages = divideWithRemainder($sql_product_count, $_SESSION["count_products_on_page"]);
        require('./shop.php');
    }
    if ($sql_product) {
        $products_to_show = array_slice($sql_product->fetch_all(MYSQLI_ASSOC), 0, $_SESSION['count_products']);
    }

    require('./footer.php');

?>