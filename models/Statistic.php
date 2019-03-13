<?php
/**
 * Created by PhpStorm.
 * User: nislacker
 * Date: 12.03.2019
 * Time: 23:05
 */

class Statistic
{
    public static function getAllUsersLoginLogoutTimes()
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT * FROM user_login_logout_statistics';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public static function getAllCategoriesNames()
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT name FROM category ORDER BY id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        $categories = $result->fetchAll();

        $categoriesNames = [];

        foreach ($categories as $k => $v)
        {
            $categoriesNames[] = $v['name'];
        }

        return $categoriesNames;
    }

    public static function getCategoryNameById($categoryId)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT name FROM category WHERE id = :id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $categoryId, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        $category = $result->fetch();
        return $category['name'];
    }

    public static function getTimesOfVisitingCategories()
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT category_id, count(*) as count
FROM user_visit_leave_category
GROUP BY category_id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        $categories = $result->fetchAll();

        $categoriesTimesOfVisiting = [];

        foreach ($categories as $k => $v)
        {
            $category_id = $v['category_id'];
            $categoriesTimesOfVisiting[$category_id] = $v['count'];
        }

        return $categoriesTimesOfVisiting;
    }

    public static function getTotalUserLoginLogoutTimes($id)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT * FROM user_login_logout_statistics WHERE user_id = :id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public static function getUsersIds()
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT id FROM user ORDER BY id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        $usersIds = $result->fetchAll();
        $ids = [];

        foreach ($usersIds as $usersId) {
            $ids[] = $usersId['id'];
        }

        return $ids;
    }

    public static function getAverageTimeThatUserIsOnline()
    {
        $usersTimes = Statistic::getAllUsersLoginLogoutTimes();
        $sumTime = 0;

        foreach ($usersTimes as $userTime) {
            //
            $logoutTime = intval($userTime['logout_time']);
            $loginTime = intval($userTime['login_time']);
            $timeDiffSecs = $logoutTime - $loginTime;

            $sumTime += $timeDiffSecs;
        }

        return $sumTime / count($usersTimes);
    }

    /**
     * @param $id -- id пользователя
     * @return float|int -- среднее число минут,
     * проводимое пользователем за посещение
     */
    public static function getAverageTimeThatUserIsOnlineById($id)
    {
        $totalTime = self::getTotalTimeThatUserIsOnlineById($id);
        $totalTimes = self::getTotalTimesThatUserIsOnlineById($id);
        if ($totalTimes['count(id)'] == 0) {
            return 0;
        }
        return $totalTime / $totalTimes['count(id)'] / 60;
    }

    public static function getTotalTimesThatUserIsOnlineById($id)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT count(id) FROM user_login_logout_statistics WHERE user_id = :id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        $userTimes = $result->fetch();

        return $userTimes;
    }

    public static function getTotalTimeThatUserIsOnlineById($id)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT login_time, logout_time FROM user_login_logout_statistics WHERE user_id = :id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        $userTimes = $result->fetchAll();
        $sumTime = 0;

        foreach ($userTimes as $userTime) {
            //

            if (!isset($userTime['logout_time']) || !isset($userTime['login_time'])) {
                continue;
            }
            $logoutTime = intval($userTime['logout_time']);
            $loginTime = intval($userTime['login_time']);


            $timeDiffSecs = $logoutTime - $loginTime;

            $sumTime += $timeDiffSecs;
        }

        return $sumTime;
    }

    public static function getTotalUserLoginsCount()
    {
        $totalUsersLoginsCount = count(self::getAllUsersLoginLogoutTimes());
        return $totalUsersLoginsCount;
    }

    public static function getAverageTimeThatUsersAreOnline()
    {
        $usersIds = self::getUsersIds();

        echo '<pre>';
        //    echo implode(', ', $ids);
        print_r($usersIds);
        echo '</pre>';

        $ids = [];

        foreach ($usersIds as $k => $v) {
            $ids[$v] = self::getTotalUserLoginLogoutTimes($v);
        }


        echo '<pre>';
        //    echo implode(', ', $ids);
        print_r($ids);
        echo '</pre>';
    }

    public static function getAdminId()
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT id FROM user WHERE role = :role';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $role = 'admin';
        $result->bindParam(':role', $role, PDO::PARAM_STR);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        $adminId = intval($result->fetch());

        return $adminId;
    }

    // Ведение статистики по времени входа/выхода из уч. записи

    public static function setLoginTimeById($id)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO user_login_logout_statistics (user_id, login_time) VALUES (:id, :time)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $time = time(); // now
        $result->bindParam(':time', $time, PDO::PARAM_INT);

        $result->execute();
    }

    public static function setLogoutTimeById($id)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'UPDATE user_login_logout_statistics SET logout_time = :time WHERE user_id = :id ORDER BY id DESC LIMIT 1';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $time = time(); // now
        $result->bindParam(':time', $time, PDO::PARAM_INT);

        $result->execute();
    }


    // Ведение статистики по категориям

    public static function setCategoryVisitTimeByUserAndCategoryId($userId, $categoryId)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO user_visit_leave_category (user_id, category_id, time_in) VALUES (:id, :category, :time)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $userId, PDO::PARAM_INT);
        $result->bindParam(':category', $categoryId, PDO::PARAM_INT);
        $time = time(); // now
        $result->bindParam(':time', $time, PDO::PARAM_INT);

        $result->execute();
    }

    public static function setCategoryLeaveTimeByUserId($userId)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'UPDATE user_visit_leave_category SET time_out = :time WHERE user_id = :id ORDER BY id DESC LIMIT 1';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $userId, PDO::PARAM_INT);
        $time = time(); // now
        $result->bindParam(':time', $time, PDO::PARAM_INT);

        $result->execute();
    }
}