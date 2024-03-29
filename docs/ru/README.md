# Тестовое задание.

**Условие**: Написать средствами PHP, MySQL, JavaScript форму входа/регистрации нового пользователя. Продумать самостоятельно необходимые поля. В результате заполнения формы пользователь должен предоставить информацию о себе. После входа должен отображаться профайл зарегистрировавшегося пользователя. Использование различных PHP фреймворков не допускается.

## Описание прокета.
**Общая информация.**
Изначально решение для этой тестовой задачи было выполнено проще, однако некоторое время спустя я решил что для понимания работы MVC фреймворков было бы полезно написать простой мини-фреймворк самому, и задача показалась мне подходящей для этого (несмотря на то, что для такого функционала это решение overhead).

**Функционал** 

1. Aвторизация/регистрация
2. Личный кабинет пользователя
3. Logout

**Стек**: docker, PHP7.4, MySQL, nginx, JS ES6, HTML, CSS, Bootstrap. Кроме того, для задачи был написан собственный мини-фрейворк на PHP, реализующий парадигму MVC.

**Страницы проекта**: информация по страницам проекта находится [здесь](https://github.com/plutonio00/auth_test_task/blob/master/docs/ru/pages.md)


## Особенности реализации 

**Frontend**

Так как в проекте был сделан упор на backend, то сборки фронта в проекте нет. В связи с тем, что для фронта использован JS ES6, который впоследствии не был преобразован в ES5, проект нужно запуcкать только в браузерах, которые поддерживают ES6.

**Backend**

В мини-фреймворке реализованы только необходимые для нужного функционала методы. В противном случае улучшать его можно было бы очень долго.

**Авторизация/регистрация**

Формы авторизации и регистрации валидируются как на фронтенде, так и на сервере. Для выполнения авторизации и регистрации используются ajax-запросы

**Структура проекта**:
1. Папка public. Содержит css, js файлы, файлы, которые загружает пользователь, а также точку входа index.php
2. Папка templates. Содержит вьюхи проекта.
3. Папка config. Содержит файлы конфигураций приложения.
4. Папка logs (не содержится в репозитории, создается с помощью скритпа init_project.sh). Содержит логи php.
3. Папка src. Содержит основные файлы проекта:
*  Папка src/core. Содержит классы, реализующие основные функции приложения. Класс Router осуществляет
   маршрутизацию (маршруты по правилу /controller/method), класс Database - работу с базой данных (с помощью
   PDO), класс Application - общее управление приложением. Кроме того, папка также содержит скрипт
   autoload.php, с помощью которого происходит автоматическая загрузка классов.
* Папка src/controller. Содержит контроллеры
* Папка src/model. Содержит модели
* Папка src/helper. Содержит классы-утилиты
* Папка src/form. Содержит классы, имеющие отношение к формам

