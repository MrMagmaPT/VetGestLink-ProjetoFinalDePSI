<?php
/** @var yii\web\View $this */
$this->title = 'VetGestLink - Gestão Moderna para Clínicas Veterinárias';
$logoUrl = Yii::getAlias('@web') . '/static/img/logo/logo.png';
?>
<main style="background: #fff;">
    <!-- Slider Moderno -->
    <section class="slider-area" style="background: url('<?= Yii::getAlias('@web') ?>/static/img/gallery/section_bg02.png') center center/cover no-repeat;">
        <div class="slider-active dot-style">
            <!-- Slide 1 -->
            <div class="single-slider d-flex align-items-center slider-height text-white" style="background: linear-gradient(90deg, #4CB88A 0%, #94E2B6 100%); min-height: 400px;">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-7 col-md-8">
                            <div class="hero__caption">
                                <span class="badge mb-2" style="background: #94E2B6; color: #222;" data-animation="fadeInUp" data-delay=".3s">Gestão veterinária eficiente</span>
                                <h1 class="display-4 fw-bold mb-3" data-animation="fadeInUp" data-delay=".3s" style="color: #fff;">Organize, atenda e cresça com VetGestLink </h1>
                                <p class="lead mb-4" data-animation="fadeInUp" data-delay=".6s" style="color: #fff;">Soluções modernas para clínicas, profissionais e clientes. Tudo num só lugar, com segurança e comodidade.</p>
                                <a href="#servicos" class="btn btn-lg shadow" style="background: #94E2B6; color: #222; border: none;" data-animation="fadeInLeft" data-delay=".3s">Conheça os Serviços <i class="ti-arrow-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-5 d-none d-lg-block text-center">
                            <img src="<?= $logoUrl ?>" alt="Logo VetGestLink" class="img-fluid rounded shadow-lg" style="max-height: 220px; background: #fff; padding: 1.5rem;" data-animation="fadeInRight" data-delay=".5s">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="single-slider d-flex align-items-center slider-height text-white" style="background: linear-gradient(90deg, #4CB88A 0%, #94E2B6 100%); min-height: 400px;">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-7 col-md-8">
                            <div class="hero__caption">
                                <span class="badge mb-2" style="background: #4CB88A; color: #fff;" data-animation="fadeInUp" data-delay=".3s">Tecnologia para veterinários</span>
                                <h1 class="display-4 fw-bold mb-3" data-animation="fadeInUp" data-delay=".3s" style="color: #fff;">Atenda com segurança e <span style="color:#fff">praticidade</span></h1>
                                <p class="lead mb-4" data-animation="fadeInUp" data-delay=".6s" style="color: #fff;">O sistema que liga profissionais, clínicas e clientes num só local.</p>
                                <a href="#servicos" class="btn btn-lg shadow" style="background: #4CB88A; color: #fff; border: none;" data-animation="fadeInLeft" data-delay=".3s">Saiba Mais <i class="ti-arrow-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-5 d-none d-lg-block text-center">
                            <img src="<?= $logoUrl ?>" alt="Logo VetGestLink" class="img-fluid rounded shadow-lg" style="max-height: 220px; background: #fff; padding: 1.5rem;" data-animation="fadeInRight" data-delay=".5s">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Serviços -->
    <section id="servicos" class="py-5" style="background: #fff;">
        <div class="container">
            <div class="row mb-5">
                <div class="col text-center">
                    <span class="fw-bold" style="color: #4CB88A;">Os Nossos Serviços</span>
                    <h2 class="fw-bold mb-3" style="color: #4CB88A;">O que oferecemos</h2>
                    <p class="text-muted">Tudo para facilitar o dia a dia da sua clínica veterinária.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center">
                        <div class="card-body">
                            <span class="flaticon-animal-kingdom display-4 mb-3" style="color: #4CB88A;"></span>
                            <h5 class="fw-bold mb-2" style="color: #4CB88A;">Gestão de Pacientes</h5>
                            <p class="text-muted">Organize fichas, histórico e marcações de forma simples e rápida.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center">
                        <div class="card-body">
                            <span class="flaticon-animals display-4 mb-3" style="color: #94E2B6;"></span>
                            <h5 class="fw-bold mb-2" style="color: #94E2B6;">Gestão Financeira</h5>
                            <p class="text-muted">Acompanhe receitas, despesas e relatórios para uma gestão eficiente.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center">
                        <div class="card-body">
                            <span class="flaticon-animals-1 display-4 mb-3" style="color: #4CB88A;"></span>
                            <h5 class="fw-bold mb-2" style="color: #4CB88A;">Comunicação com Clientes</h5>
                            <p class="text-muted">Envie lembretes, notificações e mantenha o contacto com os seus clientes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sobre Moderno -->
    <section class="py-5" style="background: #fff;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="<?= Yii::getAlias('@web') ?>/static/img/about/about_lft.png" alt="Sobre VetGestLink" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6">
                    <span class="fw-bold" style="color: #4CB88A;">Sobre o VetGestLink</span>
                    <h2 class="fw-bold mb-3" style="color: #4CB88A;">Tecnologia e cuidado para clínicas veterinárias</h2>
                    <p class="text-muted mb-4">A nossa plataforma foi criada para simplificar processos, melhorar o atendimento e impulsionar o crescimento do seu negócio veterinário. Com uma interface intuitiva, recursos completos e apoio dedicado, tem mais tempo para se focar no que importa: cuidar dos animais.</p>
                    <a href="#" class="btn btn-lg shadow" style="background: #4CB88A; color: #fff; border: none;">Saber Mais</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testemunhos -->
    <section class="py-5" style="background: #fff;">
        <div class="container">
            <div class="row mb-5">
                <div class="col text-center">
                    <span class="fw-bold" style="color: #4CB88A;">Testemunhos</span>
                    <h2 class="fw-bold mb-3" style="color: #4CB88A;">O que dizem os nossos clientes</h2>
                </div>
            </div>
            <div class="testimonial-active h1-testimonial-active dot-style d-flex flex-wrap justify-content-center gap-4">
                <div class="single-testimonial text-center p-4 mb-4" style="border: 2px solid #94E2B6; border-radius: 1rem;">
                    <div class="testimonial-caption">
                        <div class="testimonial-founder mb-3">
                            <img src="<?= Yii::getAlias('@web') ?>/static/img/gallery/testi-logo.png" alt="Cliente" class="rounded-circle mb-2" style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #4CB88A;">
                            <span class="fw-bold" style="color: #4CB88A;">Margaret Lawson</span>
                            <p class="mb-1" style="color: #94E2B6;">Clínica PetCare</p>
                        </div>
                        <div class="testimonial-top-cap">
                            <p class="fst-italic">“O VetGestLink facilitou imenso a rotina da nossa clínica. Atendimento mais rápido e clientes mais satisfeitos!”</p>
                        </div>
                    </div>
                </div>
                <div class="single-testimonial text-center p-4 mb-4" style="border: 2px solid #4CB88A; border-radius: 1rem;">
                    <div class="testimonial-caption">
                        <div class="testimonial-founder mb-3">
                            <img src="<?= Yii::getAlias('@web') ?>/static/img/gallery/testi-logo.png" alt="Cliente" class="rounded-circle mb-2" style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #94E2B6;">
                            <span class="fw-bold" style="color: #94E2B6;">Carlos Silva</span>
                            <p class="mb-1" style="color: #4CB88A;">Vet Saúde</p>
                        </div>
                        <div class="testimonial-top-cap">
                            <p class="fst-italic">“A plataforma é intuitiva e o apoio está sempre disponível. Recomendo a todos os colegas veterinários!”</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 text-center" style="background: url('<?= Yii::getAlias('@web') ?>/static/img/gallery/section_bg02.png') center center/cover no-repeat; position: relative; color: #fff;">
        <div style="position: absolute; inset: 0; background: rgba(76,184,138,0.92); z-index: 1;"></div>
        <div class="container" style="position: relative; z-index: 2;">
            <h2 class="fw-bold mb-3">Pronto para modernizar a sua clínica?</h2>
            <p class="lead mb-4">Experimente o VetGestLink e veja como a tecnologia pode transformar o seu negócio veterinário.</p>
            <?php
            use yii\helpers\Url;
            $ctaUrl = Yii::$app->user->isGuest ? Url::to(['/site/signup']) : Url::to(['/dashboard']);
            ?>
            <a href="<?= $ctaUrl ?>" class="btn btn-lg shadow" style="background: #94E2B6; color: #222; border: none;">Comece Agora</a>
        </div>
    </section>
</main>
