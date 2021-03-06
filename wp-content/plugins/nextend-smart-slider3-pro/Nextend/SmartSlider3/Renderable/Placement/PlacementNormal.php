<?php


namespace Nextend\SmartSlider3\Renderable\Placement;


use Nextend\SmartSlider3\Renderable\Component\AbstractComponent;

class PlacementNormal extends AbstractPlacement {

    public function attributes(&$attributes) {
        $data = $this->component->data;

        $this->upgradeData();

        $attributes['data-pm'] = 'normal';


        $devices = array(
            'desktopportrait',
            'desktoplandscape',
            'tabletportrait',
            'tabletlandscape',
            'mobileportrait',
            'mobilelandscape'
        );

        $desktopPortraitSelfAlign = $data->get('desktopportraitselfalign', 'inherit');
        $desktopPortraitMaxWidth  = intval($data->get('desktopportraitmaxwidth', 0));
        $desktopPortraitHeight    = $data->get('desktopportraitheight', 0);

        foreach ($devices as $device) {
            $margin = $data->get($device . 'margin');
            if (!empty($margin)) {
                $marginValues = $this->component->spacingToPxValue($margin);

                $cssText = array();
                if ($marginValues[0] != 0) {
                    $cssText[] = '--margin-top:' . $marginValues[0] . 'px';
                }
                if ($marginValues[1] != 0) {
                    $cssText[] = '--margin-right:' . $marginValues[1] . 'px';
                }
                if ($marginValues[2] != 0) {
                    $cssText[] = '--margin-bottom:' . $marginValues[2] . 'px';
                }
                if ($marginValues[3] != 0) {
                    $cssText[] = '--margin-left:' . $marginValues[3] . 'px';
                }

                $this->component->style->add($device, '', implode(';', $cssText));
            }

            $height = $data->get($device . 'height');
            if ($height === 0 || !empty($height)) {
                if ($height == 0) {
                    if ($desktopPortraitHeight > 0) {
                        $this->component->style->add($device, '', 'height:auto');
                    }
                } else {
                    $this->component->style->add($device, '', 'height:' . $height . 'px');
                }
            }

            $maxWidth = intval($data->get($device . 'maxwidth', -1));
            if ($maxWidth > 0) {
                $this->component->style->add($device, '', 'max-width:' . $maxWidth . 'px');
            } else if ($maxWidth === 0 && $device != 'desktopportrait' && $maxWidth != $desktopPortraitMaxWidth) {
                $this->component->style->add($device, '', 'max-width:none');
            }


            $selfAlign = $data->get($device . 'selfalign', 'inherit');

            if ($device == 'desktopportrait') {
                $this->component->style->add($device, '', AbstractComponent::selfAlignToStyle($selfAlign));
            } else if ($desktopPortraitSelfAlign != $selfAlign) {
                $this->component->style->add($device, '', AbstractComponent::selfAlignToStyle($selfAlign));
            }
        }

    }

    protected function upgradeData() {
        $data = $this->component->data;

        $devices = array(
            'desktopportrait',
            'desktoplandscape',
            'tabletportrait',
            'tabletlandscape',
            'mobileportrait',
            'mobilelandscape'
        );
        foreach ($devices as $device) {
            $margin = $data->get($device . 'margin', null);
            if ($margin !== null) {
                $data->set($device . 'margin', str_replace('px+', 'px', $margin));
            }
        }
    }

    public function adminAttributes(&$attributes) {

        $this->component->createDeviceProperty('maxwidth', 0);
        $this->component->createDeviceProperty('margin', '0|*|0|*|0|*|0|*|px');
        $this->component->createDeviceProperty('height', 0);
        $this->component->createDeviceProperty('selfalign', 'inherit');
    }
}