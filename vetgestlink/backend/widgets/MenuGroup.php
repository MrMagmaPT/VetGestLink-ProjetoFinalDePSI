<?php
// File: backend/widgets/MenuGroup.php
namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class MenuGroup extends Widget
{
    public $text;
    public $icon;
    public $subs = []; // cada item: ['text'=>..., 'icon'=>..., 'url'=>...,]

    public function run()
    {
        $parentLink = Html::tag('i', '', ['class' => $this->icon])
            . Html::tag('p', $this->text . Html::tag('i', '', ['class' => 'right fas fa-angle-left']));

        $a = Html::a($parentLink, '#', array_merge(['class' => 'nav-link']));

        $children = '';
        foreach ($this->subs as $item) {
            if ($item['type'] === '1') {
                $children .= MenuGroup::widget([
                    'text' => $item['text'] ?? '',
                    'icon' => $item['icon'] ?? '',
                    'subs' => $item['subs'] ?? [],
                ]);
                continue;
            } elseif ($item['type'] === '2') {
                $children .= MenuItem::widget([
                    'icon' => $item['icon'] ?? '',
                    'text' => $item['text'] ?? '',
                    'url' => $item['url'] ?? '#',
                    'active' => $item['active'] ?? false,
                ]);
                continue;
            }
        }
        $ul = Html::tag('ul', $children, ['class' => 'nav nav-treeview']);
        $style = Html::tag('span', '', ['style' => 'padding-bottom:0;']);
        return Html::tag('li', $a . $ul . $style ,array_merge(['class' => 'nav-item']));
    }
}
