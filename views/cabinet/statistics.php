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

foreach ($categoriesVisitingTimes as $k => $v)
{
    $categories[] = Statistic::getCategoryNameById($k);
}

$categoriesStr = implode("', '", $categories);
$categoriesStr = "['" . $categoriesStr . "']";
?>

<?php require_once 'charts/chart2_TimesOfVisitingCategory.php' ?>


<?php include ROOT . '/views/layouts/footer.php'; ?>