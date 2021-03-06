<?php


namespace Nextend\SmartSlider3\Slider;


use Nextend\Framework\Data\Data;

class SliderParams extends Data {

    /**
     * @var string
     */
    protected $sliderType;

    public function __construct($sliderType, $data = null, $json = false) {

        $this->sliderType = $sliderType;

        parent::__construct($data, $json);

        $this->upgradeData();
    }

    private function upgradeData() {

        $this->upgradeSliderTypeResponsive();

        $this->upgradeMaxSliderHeight();

        $this->upgradeLimitSlideWidth();

        $this->upgradeShowOn();

        $this->upgradeShowOn('widget-arrow-display-');

        $this->upgradeShowOn('widget-autoplay-display-');

        $this->upgradeShowOn('widget-bar-display-');

        $this->upgradeShowOn('widget-bullet-display-');

        $this->upgradeShowOn('widget-shadow-display-');

        $this->upgradeShowOn('widget-thumbnail-display-');

        $this->upgradeShowOn('widget-fullscreen-display-');

        $this->upgradeShowOn('widget-html-display-');

        $this->upgradeShowOn('widget-indicator-display-');

        $this->upgradeAdaptiveResponsiveMode();

        $this->upgradeCustomSliderSize();


        $this->upgradeLoadingType();

        $this->upgradeSlideBackgroundOptimize();

        $this->upgradeThumbnailsControlSize();

        $this->upgradeCarouselSideSpacing();

        $this->upgradeShowcaseSideSpacing();

    }

    private function upgradeSliderTypeResponsive() {
        if ($this->sliderType == 'carousel' || $this->sliderType == 'showcase') {
            if ($this->get('responsive-mode') == 'fullpage') {
                $this->set('responsive-mode', 'fullwidth');
            }
        }
    }

    private function upgradeMaxSliderHeight() {
        if ($this->has('responsiveSliderHeightMax')) {
            $maxSliderHeight = intval($this->get('responsiveSliderHeightMax', 3000));
            if ($maxSliderHeight < 1) {
                $maxSliderHeight = 3000;
            }

            $sliderWidth  = intval($this->get('width'));
            $sliderHeight = intval($this->get('height'));

            $maxSliderWidth = round($sliderWidth * ($maxSliderHeight / $sliderHeight));

            $maxSlideWidth = intval($this->get('responsiveSlideWidthMax', 3000));
            if ($this->has('responsiveSlideWidth')) {
                if ($this->get('responsiveSlideWidth', 1)) {
                    if ($maxSliderWidth < $maxSlideWidth) {
                        $this->set('responsiveSlideWidthMax', $maxSliderWidth);
                    }
                } else {
                    if ($maxSliderWidth > 100) {
                        $this->set('responsiveSlideWidth', 1);
                        $this->set('responsiveSlideWidthMax', $maxSliderWidth);
                    }
                }
            } else {
                $maxWidth = INF;
                if ($maxSlideWidth > 0) {
                    $maxWidth = min($maxWidth, $maxSlideWidth);
                }
                if ($maxSliderWidth > 0) {
                    $maxWidth = min($maxWidth, $maxSliderWidth);
                }
                if ($maxWidth != INF) {
                    $this->set('responsiveSlideWidth', 1);
                    $this->set('responsiveSlideWidthMax', $maxWidth);
                }
            }
            $this->un_set('responsiveSliderHeightMax');
        }

    }

    private function upgradeLimitSlideWidth() {
        if (!$this->has('responsiveLimitSlideWidth')) {
            if (!$this->has('responsiveSlideWidth')) {
                /**
                 * Layout: Auto, fullpage
                 */
                if ($this->get('responsiveSlideWidthMax') > 0) {
                    $this->set('responsiveLimitSlideWidth', 1);
                    $this->set('responsiveSlideWidth', 1);
                } else {
                    $this->set('responsiveLimitSlideWidth', 0);
                    $this->set('responsiveSlideWidth', 0);
                }
            } else {
                /**
                 * Layout: full width
                 */
                if (!$this->get('responsiveSlideWidth') && !$this->get('responsiveSlideWidthDesktopLandscape') && !$this->get('responsiveSlideWidthTablet') && !$this->get('responsiveSlideWidthTabletLandscape') && !$this->get('responsiveSlideWidthMobile') && !$this->get('responsiveSlideWidthMobileLandscape')) {
                    $this->set('responsiveLimitSlideWidth', 0);
                } else {
                    $this->set('responsiveLimitSlideWidth', 1);
                }
            }

        }
    }

    private function upgradeShowOn($pre = '') {

        $this->upgradeShowOnDevice($pre . 'desktop');
        $this->upgradeShowOnDevice($pre . 'tablet');
        $this->upgradeShowOnDevice($pre . 'mobile');
    }

    private function upgradeShowOnDevice($device, $pre = '') {
        if ($this->has($pre . $device)) {
            $value = $this->get($pre . $device);
            $this->un_set($pre . $device);

            $this->set($device . 'portrait', $value);
            $this->set($device . 'landscape', $value);
        }
    }

    private function upgradeAdaptiveResponsiveMode() {
        $responsiveMode = $this->get('responsive-mode');
        if ($responsiveMode === 'adaptive') {
            $this->set('responsiveScaleUp', 0);
        }
    }

    private function upgradeCustomSliderSize() {
        $deviceModes = array(
            'desktop-landscape',
            'tablet-portrait',
            'tablet-landscape',
            'mobile-portrait',
            'mobile-landscape'
        );

        foreach ($deviceModes as $deviceMode) {
            if (intval($this->get($deviceMode)) === 1) {

                if (intval($this->get('slider-size-override')) === 0) {
                    $this->set('slider-size-override', 1);
                }

                $this->set('slider-size-override-' . $deviceMode, 1);
                $this->set('responsive-breakpoint-' . $deviceMode . '-enabled', 1);
            }
        }

    }

    private function upgradeLoadingType() {
        if (!empty($this->get('dependency'))) {
            $this->set('loading-type', 'afterOnLoad');
        } else {
            if (!$this->has('loading-type') && $this->get('delay') > 0) {
                $this->set('loading-type', 'afterDelay');
            }
        }
    }

    private function upgradeSlideBackgroundOptimize() {
        $optimize = $this->get('optimize');
        //Allow "Convert To Webp" if "Optimize" was enabled earlier.
        if (!empty($optimize) && $optimize) {
            $this->set('optimize-webp', 1);
        }
    

        //Slide Background Resize
        $isResizeBackgroundEnabled = $this->get('optimize-background-image-custom');
        $resizeBackgroundWidth     = $this->get('optimize-background-image-width');
        if (!empty($optimize) && $optimize) {
            $this->set('optimize-thumbnail-scale', 1);
            $this->set('optimize-thumbnail-quality', intval($this->get('optimize-quality', 70)));

            if (!empty($isResizeBackgroundEnabled) && $isResizeBackgroundEnabled && !empty($resizeBackgroundWidth)) {
                $this->set('optimize-scale', 1);

                $this->set('optimize-slide-width-normal', (int)$resizeBackgroundWidth);
            }
        }
    }

    private function upgradeThumbnailsControlSize() {
        $isThumbnailEnabled = $this->get('widget-thumbnail-enabled');

        if ($isThumbnailEnabled) {

            if (!$this->has('widget-thumbnail-tablet-width') && !$this->has('widget-thumbnail-mobile-width')) {
                $defaultThumbnailWidth = intval($this->get('widget-thumbnail-width', 100));
                $this->set('widget-thumbnail-tablet-width', $defaultThumbnailWidth);
                $this->set('widget-thumbnail-mobile-width', $defaultThumbnailWidth);
            }
            if (!$this->has('widget-thumbnail-tablet-height') && !$this->has('widget-thumbnail-mobile-height')) {
                $defaultThumbnailHeight = intval($this->get('widget-thumbnail-height', 60));
                $this->set('widget-thumbnail-tablet-height', $defaultThumbnailHeight);
                $this->set('widget-thumbnail-mobile-height', $defaultThumbnailHeight);
            }

        }
    }

    private function upgradeCarouselSideSpacing() {
        if ($this->sliderType == 'carousel') {
            if ($this->has('optimize-background-image-width')) {
                /**
                 * This setting was available only before version 3.5 so, if we end up here then it is an old slider.
                 * Earlier we automatically created top and bottom side spacing: (Slider Height - Slide Height) / 2
                 * so for old sliders we need to set those values for Side Spacing top and bottom.
                 */
                $sliderHeight = intval($this->get('height'));
                $slideHeight  = intval($this->get('slide-height'));
                if ($sliderHeight > $slideHeight) {
                    $heightDifference = $sliderHeight - $slideHeight;
                    $spacingValue     = intval($heightDifference / 2);

                    if (!$this->get('side-spacing-desktop-enable')) {
                        $this->set('side-spacing-desktop-enable', 1);
                        $this->set('side-spacing-desktop', $spacingValue . '|*|0|*|' . $spacingValue . '|*|0');
                        $this->set('height', ($sliderHeight - $heightDifference));
                    }
                }
            }
        }
    }

    private function upgradeShowcaseSideSpacing() {
        if ($this->sliderType == 'showcase') {
            if ($this->has('optimize-background-image-width')) {
                /**
                 * This setting was available only before version 3.5 so, if we end up here then it is an old slider.
                 * Earlier we automatically created top and bottom side spacing: (Slider Height - Slide Height) / 2
                 * so for old sliders we need to set those values for Side Spacing top and bottom.
                 */
                $sliderHeight = intval($this->get('height'));
                $slideHeight  = intval($this->get('slide-height'));
                if ($sliderHeight > $slideHeight) {
                    $heightDifference = $sliderHeight - $slideHeight;
                    $spacingValue     = intval($heightDifference / 2);
                    $this->set('side-spacing-desktop-enable', 1);
                    $this->set('side-spacing-desktop', $spacingValue . '|*|20|*|' . $spacingValue . '|*|20');
                    $this->set('height', ($sliderHeight - $heightDifference));
                }
            }
        }
    }
}