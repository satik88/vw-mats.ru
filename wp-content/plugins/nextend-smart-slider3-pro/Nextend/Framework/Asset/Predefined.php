<?php

namespace Nextend\Framework\Asset;


use Nextend\Framework\Asset\Css\Css;
use Nextend\Framework\Asset\Fonts\Google\Google;
use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Font\FontSources;
use Nextend\Framework\Form\Form;
use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Plugin;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\SmartSlider3\Application\Frontend\ApplicationTypeFrontend;
use Nextend\SmartSlider3\Settings;

class Predefined {

    public static function backend($force = false) {
        static $once;
        if ($once != null && !$force) {
            return;
        }
        $once = true;
        wp_enqueue_script('jquery');
        $jQueryFallback = site_url('wp-includes/js/jquery/jquery.js');

        Js::addGlobalInline('_N2._jQueryFallback=\'' . $jQueryFallback . '\';');

        $family = n2_x('Montserrat', 'Default Google font family for admin');
        foreach (explode(',', n2_x('latin', 'Default Google font charset for admin')) as $subset) {
            Google::addSubset($subset);
        }
        Google::addFont($family);

        Js::addFirstCode("_N2.r(['AjaxHelper'],function(){_N2.AjaxHelper.addAjaxArray(" . json_encode(Form::tokenizeUrl()) . ");});");

        Plugin::addAction('afterApplicationContent', array(
            FontSources::class,
            'onFontManagerLoadBackend'
        ));
    }

    public static function frontend($force = false) {
        static $once;
        if ($once != null && !$force) {
            return;
        }
        $once = true;
        AssetManager::getInstance();
        if (Platform::isAdmin()) {
            Js::addGlobalInline('window.N2GSAP=' . N2GSAP . ';');
            Js::addGlobalInline('window.N2PLATFORM="' . Platform::getName() . '";');
        }
    
        Js::addGlobalInline('(function(){this._N2=this._N2||{_r:[],_d:[],r:function(){this._r.push(arguments)},d:function(){this._d.push(arguments)}}}).call(window);');
        /**
         * WebP browser support detection
         */
        Js::addGlobalInline('!function(){var r=!1,o=navigator.userAgent,a=o.match(/(Chrome|Firefox|Safari)\/(\d+)\./);a&&("Chrome"==a[1]?r=+a[2]>=32:"Firefox"==a[1]?r=+a[2]>=65:"Safari"==a[1]&&(r=+o.match(/Version\/(\d+)/)[1]>=14),r&&document.documentElement.classList.add("n2webp"))}();');
    

        Js::addStaticGroup(ApplicationTypeFrontend::getAssetsPath() . "/dist/n2.min.js", 'n2');

        FontSources::onFontManagerLoad($force);
    }

    public static function loadLiteBox() {

        Css::addStaticGroup(ResourceTranslator::toPath('$ss3-pro-frontend$/dist/litebox.min.css'), 'litebox');

        Js::addStaticGroup(ResourceTranslator::toPath('$ss3-pro-frontend$/dist/litebox.min.js'), 'litebox');

        Js::addInline('n2const.lightboxMobileNewTab=' . intval(Settings::get('lightbox-mobile-new-tab', 1)) . ';');
    
    }
}