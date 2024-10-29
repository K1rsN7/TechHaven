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
    $sql_product_count=$link->query("SELECT COUNT(*) FROM ($sql_text) AS subquery")->fetch_row()[0];
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
        if (isset($_GET['count_products_on_page'])) {
            $_SESSION["count_products_on_page"] = $_GET['count_products_on_page'];
        }
        
        $id_sort = $_GET['id_sort'] ?? null; // Используем оператор объединения с null для безопасного получения значения
        $id_cat = $_GET['id_cat'] ?? null; // То же самое здесь
        $request = $_SESSION['sql_zap_product'];
        
        // Проверка на наличие категории
        if (!empty($id_cat)) {
            $request .= " WHERE c.id_category = '$id_cat'";
        }
        $sql_brands = $link->query("SELECT subquery.id_brand, subquery.name_brand, subquery.image_brand, COUNT(subquery.id_product) as count FROM ($request) AS subquery GROUP BY subquery.id_brand, subquery.name_brand, subquery.image_brand ORDER BY subquery.name_brand");
        $sql_delivery=$link->query("SELECT subquery.delivery_days, COUNT(subquery.id_product) as count FROM ($request) AS subquery GROUP BY subquery.delivery_days ORDER BY subquery.delivery_days");
        
        if (isset($_GET['d_c_d']) && is_array($_GET['d_c_d'])) {
            $selected_delivery_days = $_GET['d_c_d'];
            $values = implode(', ', array_map('intval', $selected_delivery_days)); // Приводим к целым числам
            
            // Если уже есть условие WHERE, добавляем AND, иначе - WHERE
            if (!empty($id_cat)) {
                $request .= " AND p.delivery_days IN ($values)";
            } else {
                $request .= " WHERE p.delivery_days IN ($values)";
            }
        }
        if (isset($_GET['b_ids']) && is_array($_GET['b_ids'])) {
            $selected_brands = array_filter($_GET['b_ids'], 'is_numeric'); // Фильтруем только числовые значения
            
            if (!empty($selected_brands)) {
                $brand_values = implode(',', array_map('intval', $selected_brands)); // Приводим к целым числам
                
                // Если уже есть условие WHERE, добавляем AND, иначе - WHERE
                if (!empty($id_cat) || !empty($selected_delivery_days)) {
                    $request .= " AND p.id_brand IN ($brand_values)";
                } else {
                    $request .= " WHERE p.id_brand IN ($brand_values)";
                }
            }
        }
        // Обработка выбранных дней доставки
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
        $sql_product_count=(int)$link->query("SELECT COUNT(*) FROM ($request) AS subquery")->fetch_row()[0];
        $total_pages = (int)divideWithRemainder($sql_product_count, $_SESSION["count_products_on_page"]);
        require('./shop.php');
    }
    if ($sql_product) {
        $products_to_show = array_slice($sql_product->fetch_all(MYSQLI_ASSOC), 0, $_SESSION['count_products']);
    }

    require('./footer.php');


    function buildUrl() {
        // Собираем параметры для URL
        $params = [
            'page' => 'sort', // Указываем страницу
            'count_products_on_page' => $_SESSION["count_products_on_page"], // Количество продуктов на странице
        ];

        if (isset($_GET['id_cat']) && $_GET['id_cat'] !== '') {
            $params['id_cat'] = $_GET['id_cat'];
        }

        if (isset($_GET['id_sort']) && $_GET['id_sort'] !== '') {
            $params['id_sort'] = $_GET['id_sort'];
        }

        if (isset($_GET['d_c_d'])) {
            foreach ($_GET['d_c_d'] as $day) {
                $params['d_c_d'][] = $day; // Добавляем массив значений
            }
        }

        if (isset($_GET['b_ids'])) {
            foreach ($_GET['b_ids'] as $brand) {
                $params['b_ids'][] = $brand; // Добавляем массив значений
            }
        }

        $query_string = http_build_query($params);
        $href = "index.php?&" . $query_string;
    
        // Формируем строку запроса
        return $href;
    }
    
?>