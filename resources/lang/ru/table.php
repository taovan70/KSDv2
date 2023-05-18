<?php

return [
    'name' => 'Название',
    'created' => 'Создано',
    'add' => 'Добавить',
    'yes' => 'Да',
    'no' => 'Нет',
    'category' => 'Категория',
    'parent_category' => 'Родительская категория',
    'categories' => 'Категории',
    'subject' => 'Тематика',
    'subjects' => 'Тематики',
    'section' => 'Раздел',
    'sections' => 'Разделы',
    'sub_section' => 'Подраздел',
    'sub_sections' => 'Подразделы',
    'author' => 'Автор',
    'authors' => 'Авторы',
    'article' => 'Статья',
    'user' => 'Пользователь',
    'users' => 'Пользователи',
    'articles' => 'Статьи',
    'articles_accusative' => 'Статью',
    'tags' => 'Теги',
    "user_logging" => "Логирование",
    'author_fields' => [
        'full_name' => 'ФИО',
        'age' => 'Возраст',
        'gender' => 'Пол',
        'name' => 'Имя',
        'surname' => 'Фамилия',
        'middle_name' => 'Отчество',
        \App\Models\Author::MALE => 'Мужчина',
        \App\Models\Author::FEMALE => 'Женщина',
        'biography' => 'Биография',
        'address' => 'Местоположение',
        'personal_site' => 'Личный сайт',
        'social_networks' => 'Социальные сети',
        'social_network' => 'Социальная сеть',
        'account' => 'Аккаунт',
        'photo' => 'Фотография'
    ],

    'user_fields' =>[
        'name' => 'Имя',
        'email' => 'Email',
        'password' => 'Пароль',
        'password_confirmation' => 'Подтверждение пароля',
        'role' => 'Роль',
    ],

    'article_fields' => [
        'content' => 'Контент',
        'structure' => 'Содержание',
        'elements' => 'Элементы статьи',
        'html_tag' => 'HTML тег',
        'publish_date' => 'Дата публикации',
        'published' => 'Опубликована'
    ],

    'adv_block_fields' => [
        'content' => 'Содержание',
        'active' => 'Активен',
        'device_type' => 'Устройство',
        'color_type' => 'Цветовая схема',
        'description' => 'Описание',
        'page' => 'Страница',
    ]
];
