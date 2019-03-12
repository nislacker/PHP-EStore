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

    <section>
        <div class="table-responsive">
            <div class="container">
                <div class="row">
                    <div class="col-sm-11">

                        <?php require_once 'charts/chart1_AvgTimeOnSiteForUser.php' ?>

                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include ROOT . '/views/layouts/footer.php'; ?>