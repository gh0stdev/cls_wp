<?php
## Подключаем файл для функции dbDelta( $sql )
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

## Подключаем библиотеку CarbonFields
use Carbon_Fields\Container;
use Carbon_Fields\Field;

//add_action( 'carbon_fields_register_fields', 'crb_attach_theme_options' );
//function crb_attach_theme_options() {
//    Container::make( 'theme_options', __( 'Theme Options', 'crb' ) )
//        ->add_fields( array(
//            Field::make( 'text', 'crb_text', 'Text Field' ),
//        ) );
//}

add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
    require_once( 'vendor/autoload.php' );
    \Carbon_Fields\Carbon_Fields::boot();
}

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

## Удалим из меню различные разделы (пункты)
add_action('admin_menu', 'remove_menus');
function remove_menus(){
    remove_menu_page( 'index.php' );                  // Консоль
//    remove_menu_page( 'edit.php' );                   // Записи
//    remove_menu_page( 'upload.php' );                 // Медиафайлы
//    remove_menu_page( 'edit.php?post_type=page' );    // Страницы
    remove_menu_page( 'edit-comments.php' );          // Комментарии
    remove_menu_page( 'themes.php' );                 // Внешний вид
    remove_menu_page( 'plugins.php' );                // Плагины
    remove_menu_page( 'users.php' );                  // Пользователи
    remove_menu_page( 'tools.php' );                  // Инструменты
    remove_menu_page( 'options-general.php' );        // Параметры
}

## Оставить панель только для админов
add_action('after_setup_theme', function(){
    if ( ! is_admin() && ! current_user_can('manage_options') )
        show_admin_bar( false );
});

## Убираем панель на фронте
add_action('after_setup_theme', function(){
    if ( ! is_admin() ) show_admin_bar( false );
});

## Добавляем свои пункты меню
add_action('admin_menu', 'gh0stMenu');
function gh0stMenu(){
    add_menu_page(
        __('Заказы'),
        __('Заказы'),
        'manage_options',
        'gh_orders',
        'allOrders',
        'dashicons-format-aside',
        1
    );
    add_menu_page(
        __('Меню'),
        __('Меню'),
        'manage_options',
        'gh_menu_catalog',
        'allMenu',
        'dashicons-book',
        2
    );
    add_menu_page(
        __('Настройки'),
        __('Настройки'),
        'manage_options',
        'gh_options',
        'customOptions',
        'dashicons-admin-tools'
    );
    add_menu_page(
        __('Настройки разработчика'),
        __('Настройки разработчика'),
        'manage_options',
        'options.php?foo',
        '',
        'dashicons-hammer'
    );
}

## Подключаем стили и скрипты
add_action( 'wp_enqueue_scripts', 'scriptsStyleInit' );
function scriptsStyleInit(){
    wp_enqueue_script('jquery');
    wp_enqueue_script( 'bootstrapScripts', get_template_directory_uri() . '/libs/bootstrap/js/bootstrap.bundle.min.js');
    wp_enqueue_script( 'slickScript', get_template_directory_uri() . '/libs/slick/slick.js');
    wp_enqueue_script( 'flickityScript', get_template_directory_uri() . '/libs/flickity/flickity.pkgd.js');
    wp_enqueue_script( 'menuspyScript', get_template_directory_uri() . '/libs/menuspy/menuspy.js');
    wp_enqueue_script( 'catalog', get_template_directory_uri() . '/assets/js/catalog.js');
    wp_enqueue_script( 'custom', get_template_directory_uri() . '/assets/js/custom.js');
    wp_enqueue_style( 'bootstrapStyle', get_template_directory_uri() . '/libs/bootstrap/css/bootstrap.min.css');
    wp_enqueue_style( 'slickStyle', get_template_directory_uri() . '/libs/slick/slick.css');
    wp_enqueue_style( 'flickityStyle', get_template_directory_uri() . '/libs/flickity/flickity.css');
    wp_enqueue_style( 'main', get_template_directory_uri() . '/assets/css/main.css');
}

## Устанавливаем наш мод
function check_install()
{
    global $wpdb;

    $cat_menu = $wpdb->prefix . "cat_menu";
    $products_menu = $wpdb->prefix . "products_menu";
    $orders_menu = $wpdb->prefix . "orders_menu";
    $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";

    if($wpdb->get_var("SHOW TABLES LIKE '$cat_menu'") != $cat_menu) {
        $sql = "CREATE TABLE  {$cat_menu}   (
          `id` int NOT NULL AUTO_INCREMENT,
          `name` varchar(255) NOT NULL,
          `section` varchar(255) NULL DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) {$charset_collate};";

        dbDelta( $sql );
    }

    if($wpdb->get_var("SHOW TABLES LIKE '$products_menu'") != $products_menu) {
        $sql = "CREATE TABLE {$products_menu}  (
          `id` int NOT NULL AUTO_INCREMENT,
          `name` varchar(255) NULL,
          `descriptions` text NULL,
          `count` int NULL,
          `image` text NULL,
          `price` decimal(10, 2) NULL,
          `menu_id` int NOT NULL,
          PRIMARY KEY (`id`)
        ) {$charset_collate};";

        dbDelta( $sql );
    }

    // TO DO Реализовать Ордера
}


function allOrders() {
    include_once( 'admin/Orders/allOrders.php' );
}

function allMenu() {
    include_once( 'admin/Menu/pageMenu.php' );
}

function customOptions() {
    include_once( 'admin/Settings.php' );
}