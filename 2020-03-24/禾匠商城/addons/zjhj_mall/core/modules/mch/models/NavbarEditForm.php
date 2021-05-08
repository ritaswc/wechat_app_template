<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/11/22
 * Time: 17:27
 */

namespace app\modules\mch\models;

use app\models\AppNavbar;
use app\models\Option;

class NavbarEditForm extends MchModel
{
    public $store_id;

    public $navbar;
    public $navigation_bar_color;

    public function rules()
    {
        return [
            [['navbar', 'navigation_bar_color'], 'required'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        if (empty($this->navbar['navs'])) {
            $this->navbar['navs'] = [];
        }
        $new_navs = [];
        foreach ($this->navbar['navs'] as $i => $nav) {
            $nav['active_icon'] = $nav['active_icon'] ? $nav['active_icon'] : $nav['icon'];
            $nav['active_color'] = $nav['active_color'] ? $nav['active_color'] : $nav['color'];
            $new_navs[] = $nav;
        }
        $this->navbar['navs'] = $new_navs;
        Option::set('navigation_bar_color', $this->navigation_bar_color, $this->store_id, 'app');
        if (AppNavbar::setNavbar($this->navbar, $this->store_id)) {
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        } else {
            return [
                'code' => 0,
                'msg' => '保存失败',
            ];
        }
    }
}
