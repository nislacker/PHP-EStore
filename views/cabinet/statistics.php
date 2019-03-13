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

<?php //require_once 'charts/chart1_AvgTimeOnSiteForUser.php' ?>

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

<?php //require_once 'charts/chart2_TimesOfVisitingCategory.php' ?>
<?php
// ключ -- id-товара, значение -- количество просмотров(посещений) товара
$productsViews = Statistic::getProductsViews();

ksort($productsViews);

$productsViewsStr = implode("', '", $productsViews);
$productsViewsStr = "['" . $productsViewsStr . "']";


$products = [];

foreach ($productsViews as $k => $v) {
    $products[] = $k; //Statistic::getProductNameById($k);
}

$productsStr = implode("', '", $products);
$productsStr = "['" . $productsStr . "']";

//print_r($productsStr);


?>

<?php require_once 'charts/chart3_TimesOfVisitingProduct.php'; ?>

    <div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive-sm">
                    <table class="table table-hover table-bordered table-sm">
                        <thead>
                        <tr>
                            <th>Id товара</th>
                            <th>Наименование</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        foreach ($products as $product): ?>

                            <tr class="bg-primary">
                                <td><?= $product; ?></td>
                                <td><?= Statistic::getProductNameById($product); ?></td>
                            </tr>


                        <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php include ROOT . '/views/layouts/footer.php'; ?>