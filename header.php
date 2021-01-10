<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>



    <header class="navigation container">
        <nav class="navbar navbar-light bg-light">
            <h1><a class="navbar-brand" href="/">lounge</a></h1>
            <div class="nav-pills cart-box">
                <a href="/lounge/order" class="cart-box-link">
                    <div class="cart-box-link-text" title="Корзина">Заказ / 0&nbsp;₽</div>
                </a>
            </div>
        </nav>

        <div class="cat-filters">
            <nav>
                <ul id="cat-list">
                    <li class="cat_item">
                        <a class="cat_link" href="#list-item-1">Кальян</a>
                    </li>
                    <li class="cat_item">
                        <a class="cat_link" href="#list-item-2">Чаи</a>
                    </li>
                    <li class="cat_item">
                        <a class="cat_link" href="#list-item-3">Бургеры</a>
                    </li>
                    <li class="cat_item">
                        <a class="cat_link" href="#list-item-4">Пиво крафт</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

<div class="container">


