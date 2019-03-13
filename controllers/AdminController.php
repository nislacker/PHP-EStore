<?php

/**
 * Контроллер AdminController
 * Главная страница в админпанели
 */
class AdminController extends AdminBase
{
    /**
     * Action для стартовой страницы "Панель администратора"
     */
    public function actionIndex()
    {
        // Проверка доступа
        self::checkAdmin();

        // Записываем в БД время входа админа
        $adminId = Statistic::getAdminId();
        Statistic::setLoginTimeById($adminId);

        // Подключаем вид
        require_once(ROOT . '/views/admin/index.php');
        return true;
    }

}
