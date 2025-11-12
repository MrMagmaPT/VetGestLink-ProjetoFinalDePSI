<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class TableWidget extends Widget
{
    public $title = 'Card Title';
    public $content = [];
    public $columns = [];
    public $emptyMessage = 'No data available.';
    public function run()
    {
        $tableHeaders = '';
        $tableRows = '';

        if (!empty($this->content)) {
            $firstItem = reset($this->content);
            $keysFromFirst = $firstItem ? array_keys((array) $firstItem) : [];

            // usar $this->columns se fornecido, senão usar as chaves do primeiro item
            $allowedKeys = !empty($this->columns) ? array_values($this->columns) : $keysFromFirst;

            // montar cabeçalhos apenas para as colunas permitidas
            foreach ($allowedKeys as $key) {
                $tableHeaders .= '<th>' . Html::encode(ucfirst($key)) . '</th>';
            }

            foreach ($this->content as $line) {
                $tableRows .= '<tr>';
                foreach ($allowedKeys as $key) {
                    // obter valor (objeto ou array associativo)
                    $value = '';
                    if (is_object($line)) {
                        $value = isset($line->{$key}) ? $line->{$key} : '';
                    } else {
                        $value = isset($line[$key]) ? $line[$key] : '';
                    }

                    // garantir que arrays/objetos não causam "Array to string conversion"
                    if (is_array($value) || is_object($value)) {
                        $value = json_encode($value, JSON_UNESCAPED_UNICODE);
                    }

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
            $output .= <<<HTML
                <p class="text-muted">{$this->emptyMessage}</p>
            HTML;
        }

        $output .= <<<HTML
                </div>
            </div>
        </div>
        HTML;

        return $output;
    }
}
