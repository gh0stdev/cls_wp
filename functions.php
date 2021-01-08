<?php

## Отключаем стандартные виджеты WordPress
add_action( 'widgets_init', 'unregister_basic_widgets' );
function unregister_basic_widgets() {
    unregister_widget('WP_Widget_Pages');            // Виджет страниц
    unregister_widget('WP_Widget_Calendar');         // Календарь
    unregister_widget('WP_Widget_Archives');         // Архивы
    unregister_widget('WP_Widget_Links');            // Ссылки
    unregister_widget('WP_Widget_Meta');             // Мета виджет
    unregister_widget('WP_Widget_Search');           // Поиск
    unregister_widget('WP_Widget_Text');             // Текст
    unregister_widget('WP_Widget_Categories');       // Категории
    unregister_widget('WP_Widget_Recent_Posts');     // Последние записи
    unregister_widget('WP_Widget_Recent_Comments');  // Последние комментарии
    unregister_widget('WP_Widget_RSS');              // RSS
    unregister_widget('WP_Widget_Tag_Cloud');        // Облако меток
    unregister_widget('WP_Nav_Menu_Widget');         // Меню
    unregister_widget('WP_Widget_Media_Audio');      // Audio
    unregister_widget('WP_Widget_Media_Video');      // Video
    unregister_widget('WP_Widget_Media_Gallery');    // Gallery
    unregister_widget('WP_Widget_Media_Image');      // Image
}

## Удаление базовых элементов (ссылок) из тулбара
add_action( 'add_admin_bar_menus', function(){
    remove_action( 'admin_bar_menu', 'wp_admin_bar_customize_menu', 40); // Настроить тему
    remove_action( 'admin_bar_menu', 'wp_admin_bar_search_menu', 4 );    // поиск
    remove_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu', 10 );      // WordPress ссылки (WordPress лого)
    remove_action( 'admin_bar_menu', 'wp_admin_bar_updates_menu', 50 );
    remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
    remove_action( 'admin_bar_menu', 'wp_admin_bar_new_content_menu', 70 );
});

## Добавляет ссылку на страницу всех настроек в пункт меню админки "Настройки"
add_action('admin_menu', 'all_settings_link');
function all_settings_link(){
    add_menu_page( __('Настройка Разработчика'), __('Настройка Разработчика'), 'manage_options', 'options.php?foo' );
}

# Удалим из меню различные разделы (пункты)
add_action('admin_menu', 'remove_menus');
function remove_menus(){
    remove_menu_page( 'index.php' );                  // Консоль
    remove_menu_page( 'edit.php' );                   // Записи
    remove_menu_page( 'upload.php' );                 // Медиафайлы
    remove_menu_page( 'edit.php?post_type=page' );    // Страницы
    remove_menu_page( 'edit-comments.php' );          // Комментарии
    remove_menu_page( 'themes.php' );                 // Внешний вид
    remove_menu_page( 'plugins.php' );                // Плагины
    remove_menu_page( 'users.php' );                  // Пользователи
    remove_menu_page( 'tools.php' );                  // Инструменты
    remove_menu_page( 'options-general.php' );        // Параметры
}