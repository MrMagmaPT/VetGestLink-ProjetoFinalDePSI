<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Widget para header de página com título, ícone e breadcrumbs.
 */
class PageHeaderWidget extends Widget
{
    /** @var string Título principal */
    public $title;
    /** @var string Ícone FontAwesome (ex: 'fa-dog text-primary') */
    public $icon;
    /** @var array Breadcrumbs: [['label' => ..., 'url' => ...], ...] */
    public $breadcrumbs = [];

    public function run()
    {
        return $this->renderHeader();
    }

    protected function renderHeader()
    {
        return Html::tag('div',
            Html::tag('div',
                Html::tag('div',
                    Html::tag('div',
                        Html::tag('h1',
                            Html::tag('i', '', ['class' => 'fas ' . $this->icon]) . ' ' . Html::encode($this->title),
                            ['class' => 'm-0']
                        ),
                        ['class' => 'col-sm-6']
                    ) .
                    Html::tag('div',
                        Html::tag('ol',
                            $this->renderBreadcrumbs(),
                            ['class' => 'breadcrumb float-sm-right']
                        ),
                        ['class' => 'col-sm-6']
                    ),
                    ['class' => 'row mb-2']
                ),
                ['class' => 'container-fluid']
            ),
            ['class' => 'content-header']
        );
    }

    protected function renderBreadcrumbs()
    {
        $items = [];
        foreach ($this->breadcrumbs as $i => $bc) {
            if (isset($bc['url'])) {
                $items[] = Html::tag('li', Html::a($bc['label'], $bc['url']), ['class' => 'breadcrumb-item']);
            } else {
                $items[] = Html::tag('li', $bc['label'], ['class' => 'breadcrumb-item active']);
            }
        }
        return implode("\n", $items);
    }
}
