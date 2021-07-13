<?php
/* ====================================
 * Plugin Name: Anti-Func
 * Description: Плагин для добавления сторонних кодов, чтобы не залезать в файл functions.php и не ронять сайт при не правильном коде. 
 * Plugin URI: https://www.youtube.com/watch?v=D5kbnrRSbQo
 * Author: Artem Abramovich
 * Author URI: http://artabr.ru/
 * Version: 1.0
 * ==================================== */




                                       // СОЗДАЕМ МЕНЮ
add_action(
    'after_setup_theme',
    function () {
        register_nav_menus([
            'header-menu' => 'Верхняя область2',

        ]);
    }
);
// Изменяет основные параметры меню
add_filter('wp_nav_menu_args', 'filter_wp_menu_args');
function filter_wp_menu_args($args)
{
    if ($args['theme_location'] === 'header-menu') {
        $args['container']  = false;
        $args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
        $args['menu_class'] = 'menu-categories';
    }
    return $args;
}
// Изменяем атрибут class у тега li
add_filter('nav_menu_css_class', 'filter_nav_menu_css_classes', 10, 4);
function filter_nav_menu_css_classes($classes, $item, $args, $depth)
{
    if ($args->theme_location === 'header-menu') {
        $classes = [
            'menu-categories__item'
        ];
        if ($item->current) {
            $classes[] = 'menu-node--active';
        }
    }
    return $classes;
}
// Добавляем классы ссылкам
add_filter('nav_menu_link_attributes', 'filter_nav_menu_link_attributes', 10, 4);
function filter_nav_menu_link_attributes($atts, $item, $args, $depth)
{
    if ($args->theme_location === 'header-menu') {
        $atts['class'] = 'menu-categories__link';
        if ($item->current) {
            $atts['class'] .= ' menu-link--active';
        }
    }
    return $atts;
}


add_action(
    'after_setup_theme',
    function () {
        register_nav_menus([

            'header-menu2' => 'Верхняя область1',
        ]);
    }
);
// Изменяет основные параметры меню
add_filter('wp_nav_menu_args', 'filter_wp_menu_args2');
function filter_wp_menu_args2($args)
{
    if ($args['theme_location'] === 'header-menu2') {
        $args['container']  = false;
        $args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
        $args['menu_class'] = 'menu__list';
    }
    return $args;
}
// Изменяем атрибут class у тега li
add_filter('nav_menu_css_class', 'filter_nav_menu_css_classes2', 10, 4);
function filter_nav_menu_css_classes2($classes, $item, $args, $depth)
{
    if ($args->theme_location === 'header-menu2') {
        $classes = [
            'menu__item'
        ];
        if ($item->current) {
            $classes[] = 'menu-node--active';
        }
    }
    return $classes;
}
// Добавляем классы ссылкам
add_filter('nav_menu_link_attributes', 'filter_nav_menu_link_attributes2', 10, 4);
function filter_nav_menu_link_attributes2($atts, $item, $args, $depth)
{
    if ($args->theme_location === 'header-menu2') {
        $atts['class'] = 'menu__link';
        if ($item->current) {
            $atts['class'] .= ' menu-link--active';
        }
    }
    return $atts;
}
