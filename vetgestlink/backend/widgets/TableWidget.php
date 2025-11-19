<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use backend\models;

class TableWidget extends Widget
{
    public $title = 'Card Title';
    public $content = [];
    public $columns = [];
    public $revaluedColumns = []; // ex: ['animais_id' => 'animalporid(%%)', 'userprofiles_id' => 'userporid(%%)']
    public $alternateNamingColumns = []; // ex: ['horainicio' => 'Início', 'horafim' => 'Fim', 'animais_id' => 'Animal']
    public $emptyMessage = 'No data available.';

    public function run()
    {
        $tableHeaders = '';
        $tableRows = '';

        if (!empty($this->content)) {
            $firstItem = reset($this->content);
            $keysFromFirst = $firstItem ? array_keys((array) $firstItem) : [];

            $allowedKeys = !empty($this->columns) ? array_values($this->columns) : $keysFromFirst;

            foreach ($allowedKeys as $key) {
                $label = (isset($this->alternateNamingColumns[$key]) && $this->alternateNamingColumns[$key] !== '')
                    ? $this->alternateNamingColumns[$key]
                    : ucfirst($key);
                $tableHeaders .= '<th>' . Html::encode($label) . '</th>';
            }

            foreach ($this->content as $line) {
                $tableRows .= '<tr>';
                foreach ($allowedKeys as $key) {
                    $value = '';
                    if (is_object($line)) {
                        $value = isset($line->{$key}) ? $line->{$key} : '';
                    } else {
                        $value = isset($line[$key]) ? $line[$key] : '';
                    }

                    // aplicar revaluedColumns se definido
                    if (isset($this->revaluedColumns[$key]) && $this->revaluedColumns[$key] !== '') {
                        $evaluated = $this->evaluateRevaluedExpression($this->revaluedColumns[$key], $value);
                        if ($evaluated !== null) {
                            $value = $evaluated;
                        }
                    }

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

    /**
     * Interpreta expressão do tipo "functionName(%%, 'static', 123)" ou "Class::method(%%)"
     * Substitui %% pelo valor atual e tenta chamar a callable. Retorna null se não puder (para nao desmontrar tudo).
     */
    private function evaluateRevaluedExpression(string $expression, $value)
    {
        $expression = trim($expression);
        if ($expression === '') {
            return null;
        }
        //basicamente um regex para extrar a parte da função e os argumentos
        if (!preg_match('/^\s*([\\\\\w:]+)\s*\((.*)\)\s*$/', $expression, $m)) {
            return null;
        }
        //divide a função atravez do trim e do regex anterior
        $fnPart = $m[1];
        $argsStr = trim($m[2]);

        //prepara os valores para um callable e os argumentos atravez de um array_map e "explode"
        $rawArgs = [];
        if ($argsStr !== '') {
            $rawArgs = array_map('trim', explode(',', $argsStr));
        }

        //processa os argumentos que sao enviados (if ternario ou if inline)
        $args = [];
        foreach ($rawArgs as $arg) {
            $replacement = (is_array($value) || is_object($value)) ? json_encode($value, JSON_UNESCAPED_UNICODE) : (string)$value;
            if (strpos($arg, '%%') !== false) {
                $arg = str_replace('%%', $replacement, $arg);
            }
            //concatena string, retira as aspass e plicas, faz ofsset numerico
            if ((strlen($arg) >= 2) && (($arg[0] === '"' && substr($arg, -1) === '"') || ($arg[0] === "'" && substr($arg, -1) === "'"))) {
                $arg = substr($arg, 1, -1);
            } elseif (is_numeric($arg)) {
                if (strpos($arg, '.') !== false) {
                    $arg = (float)$arg;
                } else {
                    $arg = (int)$arg;
                }
            }
            $args[] = $arg;
        }
        //finalmente cria o callable e tenta chamar
        if (strpos($fnPart, '::') !== false) {
            list($class, $method) = explode('::', $fnPart, 2);
            $callable = [$class, $method];
        } else {
            $callable = $fnPart;
        }

        //verifica se o calable pode ser chamado, se puder, chama e retorna o valor obtido, se não puder retorna null atravez de um try catch para lidar com exeções e não explodir por todos os lados
        if (is_callable($callable)) {
            try {
                return call_user_func_array($callable, $args);
            } catch (\Throwable $e) {
                return null;
            }
        }
        return null;
    }
}
