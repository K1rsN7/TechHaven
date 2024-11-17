
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="./img/login.jpg" rel="icon">
  <title>TechHaven - Панель администратора</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-light accordion toggled" id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
          <img src="./img/logo.webp">
        </div>
        <div class="sidebar-brand-text mx-3">TechHaven</div>
      </a>
      <hr class="sidebar-divider my-0">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Аналитика</span></a>
      </li>
    </ul>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
          <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <ul class="navbar-nav ml-auto">
            
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <img class="img-profile rounded-circle" src="img/man.png" style="max-width: 60px">
                <span class="ml-2 d-none d-lg-inline text-white small"><?php echo $_SESSION['user']['username']?></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="index.php">
                  <i class="fas fa-shopping-cart fa-sm fa-fw mr-2 text-gray-400"></i>
                  Магазин
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="events_user/logout.php">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Выйти из аккаунта
                </a>
              </div>
            </li>
          </ul>
        </nav>

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Аналитика</h1>
          </div>
          

          <div class="row mb-3">
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Доход за месяц</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $_SESSION['total_income_current'];?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <span class="<?php echo ($_SESSION['percentage_change_monthly_profit'] > 0) ? 'text-success' : 'text-danger'; ?>" mr-2">
                        <i class="fa <?php echo ($_SESSION['percentage_change_monthly_profit'] > 0) ? 'fa-arrow-up' : 'fa-arrow-down'; ?>"></i> 
                        <?php echo abs(round($_SESSION['percentage_change_monthly_profit'], 2))?> %
                        </span>
                        <span>Относительно прошлого месяца</span>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-primary"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Количество заказов</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $_SESSION['all_order']?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                      <span class="<?php echo ($_SESSION['percentage_change_all_order_profit'] > 0) ? 'text-success' : 'text-danger'; ?>" mr-2">
                        <i class="fa <?php echo ($_SESSION['percentage_change_all_order_profit'] > 0) ? 'fa-arrow-up' : 'fa-arrow-down'; ?>"></i> 
                        <?php echo abs(round($_SESSION['percentage_change_all_order_profit'], 2))?> %
                        </span>
                        <span>Относительно прошлого года</span>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-shopping-cart fa-2x text-success"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- New User Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Зарегистрированных пользователей</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $_SESSION['new_user']?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <span class="<?php echo ($_SESSION['percentage_change_new_user'] > 0) ? 'text-success' : 'text-danger'; ?>" mr-2">
                        <i class="fa <?php echo ($_SESSION['percentage_change_new_user'] > 0) ? 'fa-arrow-up' : 'fa-arrow-down'; ?>"></i> 
                        <?php echo abs(round($_SESSION['percentage_change_new_user'], 2))?> %
                        </span>
                        <span>Относительно прошлого месяца</span>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Годовой доход</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $_SESSION['new_year']?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <span class="<?php echo ($_SESSION['percentage_change_new_year'] > 0) ? 'text-success' : 'text-danger'; ?>" mr-2">
                        <i class="fa <?php echo ($_SESSION['percentage_change_new_year'] > 0) ? 'fa-arrow-up' : 'fa-arrow-down'; ?>"></i> 
                        <?php echo abs(round($_SESSION['percentage_change_new_year'], 2))?> %
                        </span>
                        <span>Относительно прошлого года</span>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-ruble-sign fa-2x text-warning"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Ежемесячный отчёт</h6>
                </div>
                <div class="card-body">
                  <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                  </div>
                </div>
              </div>
            </div>
            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
              <div class="card mb-4">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">Продано товаров из категории за месяц</h6>
                  </div>
                  <div class="card-body">
                      <?php
                      // Fetching data for the current month from the session variable
                      if (isset($_SESSION['category_this_month']) && $_SESSION['category_this_month']) {
                          while ($row = $_SESSION['category_this_month']->fetch_assoc()) {
                              $categoryName = htmlspecialchars($row['name_category']);
                              $totalItems = (int)$row['total_items'];
                              // Calculate progress percentage
                              $progressPercentage = (int)$row['percentage'];
                              ?>
                              <div class="mb-3">
                                  <div class="small text-gray-500"><?php echo $categoryName; ?>
                                  <div class="small float-right">
                                    <b><?php echo $progressPercentage?>%</b>
                                  </div>
                                  </div>
                                  
                                  <div class="progress" style="height: 12px;">
                                      <div class="progress-bar <?php echo ($progressPercentage >= 80) ? 'bg-success' : (($progressPercentage >= 50) ? 'bg-warning' : 'bg-danger'); ?>" 
                                          role="progressbar" 
                                          style="width: <?php echo $progressPercentage; ?>%" 
                                          aria-valuenow="<?php echo $progressPercentage; ?>" 
                                          aria-valuemin="0" 
                                          aria-valuemax="100"></div>
                                  </div>
                              </div>
                              <?php
                          }
                      } else {
                          echo '<p>Нет данных для отображения.</p>';
                      }
                      ?>
                  </div>
              </div>
            </div>
            <div class="col-xl-12 col-lg-12 mb-4">
              <div class="card">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">Последние 500 заказов</h6>
                  </div>
                  <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush">
                          <thead class="thead-light">
                              <tr>
                                  <th>ID заказа</th>
                                  <th>Клиент</th>
                                  <th>Сумма заказа</th>
                                  <th>Статус</th>
                                  <th>Состав</th>
                                  <th>Изменить статус</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php
                              while ($row = mysqli_fetch_assoc($_SESSION['last_orders'])) {
                                  echo "<tr>";
                                  echo "<td><a href='#'>{$row['id_order']}</a></td>";
                                  echo "<td>{$row['client']}</td>";
                                  echo "<td>{$row['order_total']}</td>";
                                  echo "<td><span class='badge ";
                                  switch ($row['status']) {
                                      case 'pending':
                                          echo "badge-info'>В ожидании"; // Класс для статуса "в ожидании"
                                          break;
                                      case 'completed':
                                          echo "badge-success'>Завершен"; // Класс для статуса "завершен"
                                          break;
                                      case 'canceled':
                                          echo "badge-danger'>Отменён"; // Класс для статуса "отменен"
                                          break;
                                      case "on the way":
                                          echo "badge-warning'>В пути"; // Класс для статуса "в пути"
                                          break;
                                  }
                                  echo "</span></td>";
                                  echo "<td>{$row['order_items']}</td>";
                                  
                                  // Форма для изменения статуса
                                  echo "<td>
                                          <form action='./event_admin/update_status.php' method='POST'>
                                              <input type='hidden' name='id_order' value='{$row['id_order']}'>
                                              <select name='status' onchange='this.form.submit()'>
                                                  <option value='pending'" . ($row['status'] == 'pending' ? ' selected' : '') . ">В ожидании</option>
                                                  <option value='completed'" . ($row['status'] == 'completed' ? ' selected' : '') . ">Завершен</option>
                                                  <option value='canceled'" . ($row['status'] == 'canceled' ? ' selected' : '') . ">Отменён</option>
                                                  <option value='on the way'" . ($row['status'] == 'on the way' ? ' selected' : '') . ">В пути</option>
                                              </select>
                                          </form>
                                        </td>";
                                  echo "</tr>";
                              }
                              ?>
                          </tbody>
                      </table>
                  </div>
                  <div class="card-footer"></div>
              </div>
          </div>
        </div>
        <!---Container Fluid-->
      </div>
    </div>
  </div>


  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script>
    // Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}
let months = ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"];

// Get the current month (0-11)
let currentMonth = new Date().getMonth(); // January is 0, December is 11

// Rearrange months to have the current month as the last label
let rearrangedMonths = [...months.slice(currentMonth + 1), ...months.slice(0, currentMonth + 1)];
// Area Chart Example
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: rearrangedMonths,
    datasets: [{
      label: "Earnings",
      lineTension: 0.3,
      backgroundColor: "rgba(78, 115, 223, 0.5)",
      borderColor: "rgba(78, 115, 223, 1)",
      pointRadius: 3,
      pointBackgroundColor: "rgba(78, 115, 223, 1)",
      pointBorderColor: "rgba(78, 115, 223, 1)",
      pointHoverRadius: 3,
      pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
      pointHoverBorderColor: "rgba(78, 115, 223, 1)",
      pointHitRadius: 10,
      pointBorderWidth: 2,
      data: <?php 
        while ($row = $_SESSION['canva_profit_for_year'] ->fetch_assoc()) {
          $total_income[] = (int) $row['total_income'] ;
        };
        echo json_encode($total_income);
        ?>,
    }],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: 10,
        right: 25,
        top: 25,
        bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: 7
        }
      }],
      yAxes: [{
        ticks: {
          maxTicksLimit: 5,
          padding: 10,
          // Include a dollar sign in the ticks
          callback: function(value, index, values) {
            return '$' + number_format(value);
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: false
    },
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      intersect: false,
      mode: 'index',
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
        }
      }
    }
  }
});
</script>  
</body>
