<?php

use common\models\Marcacao;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Marcações';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container py-3">

    <div class="d-flex justify-content-center align-items-center mb-4">
        <h1 class="fw-bold"><?= Html::encode($this->title) ?></h1>
    </div>

    <?php foreach ($dataProvider->models as $i => $model): ?>
        <div class="card mb-3 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-2">
                            Marcação #<?= Html::encode($model->id) ?>
                        </h5>

                        <p class="mb-1">
                            <strong>📅 Data:</strong>
                            <?= Html::encode(Yii::$app->formatter->asDate($model->data, 'php:d/m/Y')) ?>
                        </p>

                        <p class="mb-1">
                            <strong>🕒 Hora:</strong>
                            <?= Html::encode($model->horainicio) ?> —
                            <?= Html::encode($model->horafim) ?>
                        </p>

                        <p class="text-muted mb-0">
                            Criado em:
                            <?= Yii::$app->formatter->asDatetime($model->created_at) ?>
                        </p>
                    </div>

                    <!-- Toggle button with chevron -->
                    <div class="ms-3">
                        <button class="btn toggle-diagnostico"
                                type="button"
                                data-target="#diagnostico-<?= $i ?>"
                                aria-expanded="false"
                                aria-controls="diagnostico-<?= $i ?>">
                            <span class="me-2">Ver diagnóstico</span>
                            <span class="chev" aria-hidden="true">▾</span>
                        </button>
                    </div>
                </div>

                <!-- Collapsible Diagnóstico (initially hidden) -->
                <div id="diagnostico-<?= $i ?>" class="collapse-custom mt-3" hidden>
                    <div class="card card-body bg-light border">
                        <strong>Diagnóstico:</strong>
                        <p class="mb-0">
                            <?= $model->diagnostico ? Html::encode($model->diagnostico) : '<em>Sem diagnóstico</em>' ?>
                        </p>
                    </div>
                </div>

            </div>

        </div>
    <?php endforeach; ?>


</div>
<style>/* Collapsible wrapper */
    .collapse-custom {
        overflow: hidden;
        max-height: 0;
        transition: max-height 0.35s ease;
    }

    /* When opened */
    .collapse-custom.show {
        max-height: 500px; /* enough space for text */
    }
</style>

<script>
    (function () {
        // Helper: try native Bootstrap collapse if available (Bootstrap 5)
        function bsToggle(btn, targetEl) {
            try {
                if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
                    // If bootstrap present, use its Collapse and events so styles behave normally
                    var bsCollapse = bootstrap.Collapse.getInstance(targetEl) || new bootstrap.Collapse(targetEl, {toggle: false});
                    bsCollapse.toggle();
                    return true;
                }
            } catch (e) {
                // ignore and fallback
            }
            return false;
        }

        // Fallback: custom toggle
        function fallbackToggle(el) {
            var isShown = el.classList.contains('show');
            if (isShown) {
                // hide
                el.style.maxHeight = el.scrollHeight + 'px'; // set current then force to 0 for smooth anim
                requestAnimationFrame(function () {
                    el.style.maxHeight = '0px';
                    el.classList.remove('show');
                    // after transition, hide to remove from accessibility flow
                    setTimeout(function () { el.hidden = true; }, 300);
                });
            } else {
                // show
                el.hidden = false;
                // set to 0 then to scrollHeight to animate
                el.style.maxHeight = '0px';
                requestAnimationFrame(function () {
                    el.classList.add('show');
                    el.style.maxHeight = el.scrollHeight + 'px';
                });
                // clear maxHeight after animation to allow responsive height
                setTimeout(function () { el.style.maxHeight = ''; }, 300);
            }
        }

        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.toggle-diagnostico');
            if (!btn) return;
            var targetSelector = btn.getAttribute('data-target');
            if (!targetSelector) return;
            var target = document.querySelector(targetSelector);
            if (!target) return;

            // Try Bootstrap first
            var usedBootstrap = bsToggle(btn, target);
            if (!usedBootstrap) {
                // Fallback behavior
                fallbackToggle(target);
            }

            // Toggle aria-expanded
            var expanded = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
        });
    })();
</script>
