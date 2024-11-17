<?php
    session_start();
    require('./db.php');

    $sql_category=$link->query("SELECT c.id_category, c.name_category, count(p.name_product) as count FROM product p LEFT JOIN category c ON p.id_category = c.id_category GROUP BY c.name_category ORDER BY count DESC");

    if(!isset($_SESSION['sql_zap_product'])){
        $_SESSION['sql_zap_product']="SELECT p.id_product, p.image, p.name_product, p.description, p.price, p.delivery_days, b.id_brand, b.name_brand, b.image_brand, c.id_category, c.name_category FROM product p LEFT JOIN category c ON p.id_category = c.id_category LEFT JOIN brand b ON p.id_brand = b.id_brand";
        $_SESSION['count_products_on_page'] = 6;
        $_SESSION['is_header'] = true;
    }

    function load_header(){
        if(isset($_SESSION['is_header']) && $_SESSION['is_header'] == true) {
            require("./header.php");
        }
    }

    function load_footer(){
        if(isset($_SESSION['is_header']) && $_SESSION['is_header'] == true) {
            require("./footer.php");
        }
    }

    function load_page($path): void {
        load_header();
        require($path);
        load_footer();
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

    function calculateAdminPaneDifference($link, $name_session_path, $sql_select_first, $sql_select_second) {
        // Получение значений первого запроса
        $sql_current_month = $link->query($sql_select_first);

        $current_month_data = $sql_current_month->fetch_assoc();
        $total_income_current = $current_month_data['total_current'] ?? 0;
        $_SESSION[$name_session_path] = $total_income_current;

        // Получение значений второго запроса
        $sql_previous_month = $link->query($sql_select_second);
        $previous_month_data = $sql_previous_month->fetch_assoc();
        $total_income_previous = $previous_month_data['total_previous'] ?? 0;

        // Вычисление процентного изменения
        if ($total_income_previous > 0) {
            $percentage_change = (($total_income_current - $total_income_previous) / $total_income_previous) * 100;
        } else {
            $percentage_change = $total_income_current > 0 ? 100 : 0; // Если в прошлом месяце не было роста, но в этом есть, считать рост на 100%
        }
        return $percentage_change;
    }

    function calculateAdminPanel($link){
        // 
        // Вычисления всех параметров для админ панели
        //
        // Получаем показатели дохода за месяц
        $_SESSION['percentage_change_monthly_profit'] = calculateAdminPaneDifference(
            $link, 
            'total_income_current', 

            "SELECT SUM(total) AS total_current
            FROM `order`
            WHERE MONTH(order_date) = MONTH(CURRENT_DATE)
            AND YEAR(order_date) = YEAR(CURRENT_DATE)
            AND status = 'completed'",

            "SELECT SUM(total) AS total_previous
            FROM `order`
            WHERE MONTH(order_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
            AND YEAR(order_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
            AND status = 'completed'"
        );
        // Получаем показатели заказов
        $_SESSION['percentage_change_all_order_profit'] = calculateAdminPaneDifference(
            $link, 
            'all_order',
            "SELECT COUNT(total) AS total_current
            FROM `order`
            WHERE MONTH(order_date) = MONTH(CURRENT_DATE)
            AND YEAR(order_date) = YEAR(CURRENT_DATE)
            AND status = 'completed'",

            "SELECT COUNT(total) AS total_previous
            FROM `order`
            WHERE MONTH(order_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
            AND YEAR(order_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
            AND status = 'completed'"
        );
        // Получаем показатели новых пользователей
        $_SESSION["percentage_change_new_user"] = calculateAdminPaneDifference(
            $link, 
            'new_user',
            "SELECT COUNT(*) AS total_current
            FROM `user`
            WHERE MONTH(created_at) = MONTH(CURRENT_DATE)
            AND YEAR(created_at) = YEAR(CURRENT_DATE)",

            "SELECT COUNT(*) AS total_previous
            FROM `user`
            WHERE MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
            AND YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)"
        );
        // Получаем показатели выручки за год
        $_SESSION['percentage_change_new_year'] = 
        calculateAdminPaneDifference(
            $link, 
            'new_year',
            "SELECT SUM(total) AS total_current
            FROM `order`
            WHERE YEAR(order_date) = YEAR(CURRENT_DATE)
            AND status = 'completed';",

            "SELECT SUM(total) AS total_previous
            FROM `order`
            WHERE YEAR(order_date) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)
            AND status = 'completed'"
        );
        $_SESSION['canva_profit_for_year'] = $link->query("SELECT 
                CASE MONTH(order_date)
                    WHEN 1 THEN 'Январь'
                    WHEN 2 THEN 'Февраль'
                    WHEN 3 THEN 'Март'
                    WHEN 4 THEN 'Апрель'
                    WHEN 5 THEN 'Май'
                    WHEN 6 THEN 'Июнь'
                    WHEN 7 THEN 'Июль'
                    WHEN 8 THEN 'Август'
                    WHEN 9 THEN 'Сентябрь'
                    WHEN 10 THEN 'Октябрь'
                    WHEN 11 THEN 'Ноябрь'
                    WHEN 12 THEN 'Декабрь'
                END AS month_name,
                SUM(total) AS total_income
            FROM `order`
            WHERE order_date >= DATE_SUB(CURRENT_DATE, INTERVAL 12 MONTH)
            AND status = 'completed'
            GROUP BY month_name
            ORDER BY MONTH(order_date);"
        );
        $_SESSION['category_this_month'] = $link->query("
               SELECT 
                    c.name_category,
                    COUNT(oi.id_product) AS total_items,
                    SUM(COUNT(oi.id_product)) OVER () AS total_sum,
                    CAST((COUNT(oi.id_product) * 100.0 / NULLIF(SUM(COUNT(oi.id_product)) OVER (), 0)) AS INT) AS percentage
                FROM 
                    category c
                LEFT JOIN 
                    product p ON c.id_category = p.id_category
                LEFT JOIN 
                    order_items oi ON p.id_product = oi.id_product
                LEFT JOIN 
                    `order` o ON oi.id_order = o.id_order AND o.order_date >= NOW() - INTERVAL 1 MONTH
                GROUP BY 
                    c.id_category;
        ");
        $_SESSION['last_orders'] = $link->query("
        SELECT 
            o.id_order,
            u.username AS client,
            o.total AS order_total,
            o.status,
            GROUP_CONCAT(CONCAT(p.name_product, ' (', oi.quantity, ')') ORDER BY oi.id_order_item SEPARATOR ', ') AS order_items
        FROM 
            `order` o
        JOIN 
            `user` u ON o.id_user = u.id_user
        JOIN 
            `order_items` oi ON o.id_order = oi.id_order
        JOIN 
            `product` p ON oi.id_product = p.id_product
        GROUP BY 
            o.id_order, u.username, o.total, o.status
        ORDER BY 
            o.order_date DESC
        LIMIT 500;
        ");
    }

    function getUserOrders($link){
        $order_query = "SELECT * FROM `order` WHERE `id_user` = ? ORDER BY `order_date` DESC";
        $order_stmt = $link->prepare($order_query);
        $order_stmt->bind_param("i", $_SESSION['user']['id_user']);
        $order_stmt->execute();
        $_SESSION['my_orders'] = $order_stmt->get_result();
    }

    $sql_text=$_SESSION['sql_zap_product'];
    $sql_product=$link->query($sql_text);
    $sql_product_count=$link->query("SELECT COUNT(*) FROM ($sql_text) AS subquery")->fetch_row()[0];
    $sql_brands=$link->query("SELECT subquery.id_brand, subquery.name_brand, subquery.image_brand, COUNT(subquery.id_product) as count FROM ($sql_text) AS subquery GROUP BY subquery.id_brand, subquery.name_brand, subquery.image_brand ORDER BY subquery.name_brand");
    $sql_delivery=$link->query("SELECT subquery.delivery_days, COUNT(subquery.id_product) as count FROM ($sql_text) AS subquery GROUP BY subquery.delivery_days");
    $sql_min_price=$link->query("SELECT MIN(subquery.price) FROM ($sql_text) AS subquery");
    $sql_max_price=$link->query("SELECT MAX(subquery.price) FROM ($sql_text) AS subquery");
    $total_pages = divideWithRemainder($sql_product_count, $_SESSION["count_products_on_page"]);

    $page=$_GET['page'];
    if(!isset($page) or $page=='content'){
        $_SESSION['is_header'] = true;
        load_page('./content.php');
    }
    elseif ($page=='contact'){
        $_SESSION['sql_review']=$link->query("SELECT r.rating, u.username, r.comment, u.image_user FROM review r LEFT JOIN `user` u ON u.id_user = r.id_user");
        $_SESSION['is_header'] = true;
        load_page('./contact.php');
    }
    elseif ($page=='shop'){
        unset($_SESSION['sql_zap_product']);
        $_SESSION['is_header'] = true;
        load_header();
        require('./shop.php');
        load_footer();
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
        $_SESSION['is_header'] = true;
        load_header();
        require('./shop.php');
        load_footer();
    } elseif ($page=='single-product'){
        $id=(int)$_GET['id_product'];
        $sql_text=$_SESSION['sql_zap_product'];
        $product_data = $link->query($sql_text . " WHERE p.id_product = " . $id);
        $_SESSION['product_id'] = $product_data->fetch_assoc();
        $_SESSION['is_header'] = true;
        if ($product_data->num_rows === 0) {
            // Если товар не найден, загружаем файл 404.php
            load_page('./404.php');
            exit(); // Завершаем выполнение скрипта
        }
        load_page('./single-product.php');
    } elseif ($page=='cart'){
        $_SESSION['all_products'] = $link->query("SELECT * from product");
        $_SESSION['is_header'] = true;
        load_page('./cart.php');
    } elseif ($page=='login'){
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role']=="customer") {
                getUserOrders($link);
                load_page('./cabinet_user.php');
            }else{
                $_SESSION['is_header'] = false;
                calculateAdminPanel($link);
                load_page('./cabinet_admin.php');
            }

        }else{
            $_SESSION['is_header'] = true;
            load_page('login.php');
        }
    }
    elseif ($page=='registration'){
        $_SESSION['is_header'] = true;
        load_page('registration.php');
    } elseif ($page=='user'){
        if (isset($_SESSION['user']) && $_SESSION['user']['role']=="customer"){
            $_SESSION['is_header'] = true;
            getUserOrders($link);
            load_page('./cabinet_user.php');
        } else{
            $_SESSION['is_header'] = true;
            load_page('./404.php');
        }
    } elseif ($page=='admin'){
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role']=="admin"){
                $_SESSION['is_header'] = false;
                calculateAdminPanel($link);
                load_page('./cabinet_admin.php');
            } else {
                $_SESSION['is_header'] = true;
                load_page('./404.php');
            }
        } else{
            $_SESSION['is_header'] = true;
            load_page('./404.php');
        }
    } elseif ($page=='success'){
        if (isset($_SESSION['id_order'])){
            $_SESSION['is_header'] = true;
            load_page('./success.php');
        } else{
            $_SESSION['is_header'] = true;
            load_page('./404.php');
        }
    } elseif ($page=='politic') {
        $_SESSION['is_header'] = true;
        load_page('./politic.php');
    } else{
        $load_page['is_header'] = true;
        load_page('./404.php');
    }


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