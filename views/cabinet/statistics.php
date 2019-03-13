<?php include ROOT . '/views/layouts/header.php'; ?>

    <!--
    Модуль должен собирать информацию о том, как пользователи посещают сайт за определенное время.
	В админ. части необходимо представить следующую информацию в виде графиков:
    - Среднее время нахождения пользователя на сайте;
    - Посещаемость категории;
    - Посещаемость конкретного товара;
    - Кол-во посещений за период по дням;
    - Список десяти самых покупаемых товаров. В виде диаграммы баров.
    -->

<?php

$usersIds = Statistic::getUsersIds();
$usersAvgTimesOnline = [];

foreach ($usersIds as $userId) {
    $usersAvgTimesOnline[] = Statistic::getAverageTimeThatUserIsOnlineById($userId);
}

$usersIdsStr = implode("', '", $usersIds);
$usersIdsStr = "['" . $usersIdsStr . "']";

$usersAvgTimesOnlineStr = implode("', '", $usersAvgTimesOnline);
$usersAvgTimesOnlineStr = "['" . $usersAvgTimesOnlineStr . "']";

?>

<?php require_once 'charts/chart1_AvgTimeOnSiteForUser.php' ?>

<?php

$categoriesVisitingTimes = Statistic::getTimesOfVisitingCategories();

ksort($categoriesVisitingTimes);

$categoriesVisitingTimesStr = implode("', '", $categoriesVisitingTimes);
$categoriesVisitingTimesStr = "['" . $categoriesVisitingTimesStr . "']";

$categories = [];

foreach ($categoriesVisitingTimes as $k => $v) {
    $categories[] = Statistic::getCategoryNameById($k);
}

$categoriesStr = implode("', '", $categories);
$categoriesStr = "['" . $categoriesStr . "']";
?>

<?php require_once 'charts/chart2_TimesOfVisitingCategory.php' ?>
<?php
// ключ -- id-товара, значение -- количество просмотров(посещений) товара
$productsViews = Statistic::getProductsViews();

ksort($productsViews);

$productsViewsStr = implode("', '", $productsViews);
$productsViewsStr = "['" . $productsViewsStr . "']";

$products = [];

foreach ($productsViews as $k => $v) {
    $products[] = $k;
}

$productsStr = implode("', '", $products);
$productsStr = "['" . $productsStr . "']";

?>

<?php require_once 'charts/chart3_TimesOfVisitingProduct.php'; ?>


<?php

$orders = Order::getAllOrders();

$allOrders = [];

foreach ($orders as $order) {

    $allOrders[] = json_decode($order['products'], true);
}

$productsBuys5 = [];

foreach ($allOrders as $k => $v) {
    foreach ($v as $kk => $vv) {
        if (!isset($productsBuys5[$kk])) {
            $productsBuys5[$kk] = 0;
        }

        $productsBuys5[$kk] += $vv;
    }
}

arsort($productsBuys5);

$productsBuys5 = array_slice($productsBuys5, 0, 10, true);

$productsBuysStr5 = implode("', '", $productsBuys5);
$productsBuysStr5 = "['" . $productsBuysStr5 . "']";

$products5 = [];

foreach ($productsBuys5 as $k => $v) {
    $products5[] = $k;
}

$productsStr5 = implode("', '", $products5);
$productsStr5 = "['" . $productsStr5 . "']";

?>

<?php
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <form action="<?= $actual_link; ?>" method="post" style="margin-left: 200px; margin-bottom: 50px;">
                    <input type="date" name="dateStart" value="<?php if (isset($_POST['dateStart'])) {
                        echo $_POST['dateStart'];
                    } ?>">
                    <input type="date" name="dateEnd" value="<?php if (isset($_POST['dateEnd'])) {
                        echo $_POST['dateEnd'];
                    } ?>">
                    <button type="submit" name="submit">Показать график</button>
                </form>

                <?php


                //$titleText = '';
                //
                //if (isset($_POST['submit']) && isset($_POST['dateStart']) && isset($_POST['dateEnd'])) {
                //    echo $_POST['dateStart'] . ' ' . $_POST['dateEnd'];
                //
                //    $titleText = 'Посещаемость сайта за период с ' . $_POST['dateStart'] . ' по' . $_POST['dateEnd'];
                //} else {
                //    $titleText = 'Посещаемость сайта';
                //}

                ?>

                <?php

                $logs = Statistic::getAllUsersLoginLogoutTimes();
                $logTimeIn = [];
                $dates = [];

                foreach ($logs as $log) {
                    $logTimeIn[] = $log['login_time'] . '<br>';

                    if (isset($_POST['dateStart']) && isset($_POST['dateStart']) &&
                        $log['login_time'] >= strtotime($_POST['dateStart']) &&
                        $log['login_time'] <= strtotime($_POST['dateEnd'])) {
                        $dates[] = date('d-m-Y', $log['login_time']);
                    }

//    if (!isset($dates[ date('dd.mm.YY', $log['login_time']) ])) {
//        $dates[ date('dd.mm.YY', $log['login_time']) ] = 0;
//    }
//    else
//    {
//        $dates[ date('dd.mm.YY', $log['login_time']) ]++;
//    }
                }

                $datesCounts = array_count_values($dates);

                $datesCountsStr = implode("', '", $datesCounts);
                $datesCountsStr = "['" . $datesCountsStr . "']";

                $datesToShow = [];

                foreach ($datesCounts as $k => $v) {
                    $datesToShow[] = $k;
                }

                $datesToShowStr = implode("', '", $datesToShow);
                $datesToShowStr = "['" . $datesToShowStr . "']";

                ?>


            </div>
        </div>
    </div>

<?php require_once 'charts/chart4_TimesOfVisitingByPeriodInDays.php'; ?>

<?php require_once 'charts/chart5_10TopMostBuyingProducts.php'; ?>


<?php include ROOT . '/views/layouts/footer.php'; ?>