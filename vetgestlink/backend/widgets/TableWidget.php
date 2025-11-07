<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class TableWidget extends Widget
{
    public $title = 'Card Title';
    public $content = [];

    public function run()
    {
        $tableHeaders = '';
        $tableRows = '';

        if (!empty($this->content)) {
            $firstItem = reset($this->content);
            if ($firstItem) {
                $keys = array_keys((array) $firstItem);
                foreach ($keys as $key) {
                    $tableHeaders .= '<th>' . Html::encode(ucfirst($key)) . '</th>';
                }
            }

            foreach ($this->content as $line) {
                $tableRows .= '<tr>';
                foreach ($keys as $key) {
                    // You can customize this to format datetime, relations, etc
                    $value = is_object($line) ? ($line->$key ?? '') : ($line[$key] ?? '');
                    $tableRows .= '<td>' . Html::encode((string)$value) . '</td>';
                }
                $tableRows .= '</tr>';
            }
        }

        $output = <<<HTML
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header"><h3 class="card-title">{$this->title}</h3></div>
                <div class="card-body">
        HTML;

        if (!empty($this->content)) {
            $output .= <<<HTML
                        <table class="table table-striped">
                            <thead><tr>{$tableHeaders}</tr></thead>
                            <tbody>{$tableRows}</tbody>
                        </table>
            HTML;
        } else {
            $output .= '<p class="text-muted">Nenhuma marcação registrada.</p>';
        }

        $output .= <<<HTML
                </div>
            </div>
        </div>
        HTML;

        return $output;
    }
}
