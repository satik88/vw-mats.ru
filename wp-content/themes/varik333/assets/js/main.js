document.addEventListener('DOMContentLoaded', function () {
    let mySwiper = new Swiper('.banner-section__slider', {
        speed: 500,
        spaceBetween: 0,
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
            clickable: true,
        },
        autoplay: {
            delay: 5000,
        },
    });
    let mySwiper2 = new Swiper('.slider-2', {
        speed: 400,
        slidesPerView: 4,
        spaceBetween: 30,
        observer: true,
        observeParents: true,
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
            clickable: true,
        },
        breakpoints: {
            // when window width is >= 320px
            320: {
                slidesPerView: 1,
                spaceBetween: 30,
            },
            // when window width is >= 480px
            510: {
                slidesPerView: 2,
            },
            // when window width is >= 640px
            800: {
                slidesPerView: 3,
            },
            1100: {
                slidesPerView: 4,
            }
        }
    });

    $('.tab').on('click', function (e) {
        e.preventDefault();
        $($(this).siblings()).removeClass('tabs--active');
        $($(this).parent().siblings().find('div')).removeClass('tabs-content--active');

        $(this).addClass('tabs--active');
        $($(this).attr('href')).addClass('tabs-content--active');

    })
    let funtionFavorite = function () {
        let favorite = document.getElementsByClassName("products-item__favorite");
        for (let i = 0; i < favorite.length; i++) {
            favorite[i].addEventListener('click', () => {
                favorite[i].classList.toggle("products-item__favorite--active")
            })
        }
    };
    let filterBtn = function () {
        let catalogFilter = document.querySelectorAll(".catalog__filter-items__button");
        for (let i = 0; i < catalogFilter.length; i++) {
            catalogFilter[i].addEventListener('click', () => {
                catalogFilter.forEach(element => {
                    element.classList.remove("catalog__filter-items__button--active")
                });
                catalogFilter[i].classList.toggle("catalog__filter-items__button--active");
            });
        }
    };
    $(".filter__item-drop, .filter__extra").on('click', function () {
        $(this).toggleClass("filter__item-drop--active");
        $(this).next().slideToggle('100');
    })
    $('.filter-style').styler();
    $(".js-range-slider").ionRangeSlider({
        grid: false,
        type: "double",
        min: 100000,
        max: 500000,
    });
    $(".catalog__filter-btn-grid").on('click', function () {
        $(this).addClass("catalog__filter-button--active");
        $(".catalog__filter-btn-line").removeClass("catalog__filter-button--active");
        $(".products-item__wrapper").removeClass("products-item__wrapper-list");
    });
    $(".catalog__filter-btn-line").on('click', function () {
        $(this).addClass("catalog__filter-button--active");
        $(".catalog__filter-btn-grid").removeClass("catalog__filter-button--active");
        $(".products-item__wrapper").addClass("products-item__wrapper-list");
    });
    $(".rateYo").rateYo({
        spacing: "7px",
    });
    $(".menu__btn").on('click', function () {
        $(".menu-mobile__list").toggleClass("menu-mobile__list--active")
    });
    $(".footer__section-title").on('click', function() {
        $(this).toggleClass("footer__section-title--active")
        $(this).next().slideToggle();
    });
    $(".aside-btn").on('click', function() {
        $(this).next().slideToggle();

    });
    filterBtn();
    funtionFavorite();
});