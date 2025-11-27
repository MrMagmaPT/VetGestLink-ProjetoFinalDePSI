<?php
// File: backend/widgets/MenuGroup.php
namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class MenuGroup extends Widget
{
    public $label;
    public $icon = 'nav-icon fas fa-cog';
    public $items = []; // cada item: ['label'=>..., 'url'=>..., 'active'=>...,]

    public function run()
    {
        $parentLink = Html::tag('i', '', ['class' => $this->icon])
            . Html::tag('p', $this->label . Html::tag('i', '', ['class' => 'right fas fa-angle-left']));

        $a = Html::a($parentLink, '#', array_merge(['class' => 'nav-link']));

        $children = '';
        foreach ($this->items as $item) {
            $children .= MenuItem::widget([
                'label' => $item['label'] ?? '',
                'url' => $item['url'] ?? '#',
                'active' => $item['active'] ?? false,
                'encodeLabel' => $item['encodeLabel'] ?? false,
            ]);
        }

        $ul = Html::tag('ul', $children, ['class' => 'nav nav-treeview']);
        return Html::tag('li', $a . $ul, array_merge(['class' => 'nav-item']));
    }
}
