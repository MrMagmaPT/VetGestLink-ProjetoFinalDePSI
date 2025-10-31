<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Sobre Nós';
$this->params['breadcrumbs'][] = $this->title;
?>
<main>
    <!-- Hero Area Start -->
    <div class="slider-area2 d-flex align-items-center"
         style="background: linear-gradient(90deg, #4CB88A 0%, #94E2B6 100%); min-height: 180px; padding: 2.5rem 0 1.5rem 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 text-center">
                    <div class="hero-cap pt-2">
                        <h2 class="fw-bold mb-2" style="color: #fff;">Sobre Nós</h2>
                        <p class="lead mb-0" style="color:#fff; opacity:0.95;">Tecnologia e dedicação ao serviço das clínicas veterinárias</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mt-4">
                <div class="col-auto text-center">
                    <img src="<?= Yii::getAlias('@web') ?>/static/img/logo/logoOnly.png" alt="Sobre VetGestLink"
                         class="img-fluid rounded shadow-lg" style="max-height: 120px; background: #fff; padding: 1rem;">
                </div>
            </div>
        </div>
    </div>
    <!-- Hero Area End -->

    <!-- About Details Start -->
    <div class="about-details section-padding30" style="background: #fff; padding: 1.5rem 0;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-7">
                    <div class="text-center mb-5">
                        <h3 class="fw-bold" style="color:#4CB88A;">Nossa Missão</h3>
                        <p class="lead mt-3" style="color:#444;">Desenvolver uma solução inovadora que una as competências das três Unidades Curriculares de AMSI, Plataformas e API, proporcionando uma ferramenta robusta e intuitiva para a gestão de clínicas veterinárias em Portugal.</p>
                    </div>
                    <div class="text-center mb-5">
                        <h3 class="fw-bold" style="color:#4CB88A;">Nossa Visão</h3>
                        <p class="lead mt-3" style="color:#444;">Criar uma aplicação web de referência para clínicas veterinárias, permitindo marcação de consultas online, gestão eficiente do backoffice e melhoria contínua do atendimento a clientes e animais.</p>
                    </div>
                    <div class="text-center mb-4">
                        <h3 class="fw-bold" style="color:#4CB88A;">Nossos Valores</h3>
                        <ul class="list-unstyled mt-3" style="color:#4CB88A; font-weight:500; font-size:1.1rem;">
                            <li class="mb-2">Inovação e tecnologia ao serviço da saúde animal</li>
                            <li class="mb-2">Transparência e ética profissional</li>
                            <li class="mb-2">Facilidade de utilização e acessibilidade</li>
                            <li class="mb-2">Foco no cliente e no bem-estar animal</li>
                            <li>Colaboração e aprendizagem contínua</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About Details End -->

    <div class="text-center py-2"
         style="background: #f8f9fa; color: #4CB88A; font-weight: 500; font-size: 1.2rem; margin-bottom: 0;">
        <strong>VetGestLink</strong> &mdash; A sua clínica, mais moderna, mais eficiente, mais próxima.
    </div>
</main>
