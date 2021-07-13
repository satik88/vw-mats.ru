<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package varik333
 */

?>

<footer class="footer">
    <div class="container">
      <div class="footer__top">
        <div class="footer__top-inner">
          <div class="footer__top-item footer__top-newsletter">
            <h6 class="footer__top-title footer__top__title">
              Подпишитесь на нашу рассылку
              и узнавайте о акция быстрее
            </h6>
            <form action="" class="footer-form">
              <input class="footer-form__input" type="text" placeholder="Введите ваш e-mail:">
              <button class="footer-form__button" type="submit">Отправить</button>
            </form>
          </div>
          <div class="footer__top-item">
            <h6 class="footer__top-title footer__section-title">
              Информация
            </h6>
            <ul class="footer-list">
              <li class="footer-list__item">
                <a href="#">О компании</a>
              </li>
              <li class="footer-list__item">
                <a href="#">Контакты</a>
              </li>
              <li class="footer-list__item">
                <a href="#">Акции</a>
              </li>
              <li class="footer-list__item">
                <a href="#">Магазины</a>
              </li>
            </ul>
          </div>
          <div class="footer__top-item">
            <h6 class="footer__top-title footer__section-title">
              Интернет-магазин
            </h6>
            <ul class="footer-list">
              <li class="footer-list__item">
                <a href="#">Доставка и самовывоз</a>
              </li>
              <li class="footer-list__item">
                <a href="#">Оплата</a>
              </li>
              <li class="footer-list__item">
                <a href="#">Возврат-обмен</a>
              </li>
              <li class="footer-list__item">
                <a href="#">Новости</a>
              </li>
            </ul>
          </div>
          <div class="footer__top-item footer__top-social">
            <ul class="social-list">
              <li class="social-list__item">
                <a href="#">
                  <img src="<?php echo get_template_directory_uri(); ?>/assets/images/instagram.svg" alt="">
                </a>
              </li>
              <li class="social-list__item">
                <a href="#">
                  <img src="<?php echo get_template_directory_uri(); ?>/assets/images/vk.svg" alt="">
                </a>
              </li>
              <li class="social-list__item">
                <a href="#">
                  <img src="<?php echo get_template_directory_uri(); ?>/assets/images/facebook.svg" alt="">
                </a>
              </li>
              <li class="social-list__item">
                <a href="#">
                  <img src="<?php echo get_template_directory_uri(); ?>/assets/images/youtube.svg" alt="">
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="footer__bottom">
        <a class="footer__bottom-link" href="#">Договор оферты</a>
        <a class="footer__bottom-link" href="#">Политика обработки персональных данных</a>
      </div>
    </div>
  </footer>

<?php wp_footer(); ?>
</body>
</html>

