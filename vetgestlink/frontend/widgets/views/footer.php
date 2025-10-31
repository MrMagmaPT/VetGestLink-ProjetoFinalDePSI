<?php
use yii\helpers\Html;

// Define default values for variables if not already set
$logoPath = '/static/img/logo/logo.png';
$companyLinks = $companyLinks ?? [
    ['label' => 'Sobre Nós', 'url' => ['/site/about']],
    ['label' => 'Carreiras', 'url' => ['/site/careers']],
];
$serviceLinks = $serviceLinks ?? [
    ['label' => 'Consultoria', 'url' => ['/services/consulting']],
    ['label' => 'Apoio', 'url' => ['/services/support']],
];
$contactInfo = $contactInfo ?? [
    ['type' => 'email', 'label' => 'contacto@vetgestlink.com', 'url' => 'mailto:contacto@vetgestlink.com'],
    ['type' => 'phone', 'label' => '+351 234 567 890', 'url' => 'tel:+351234567890'],
];
?>

<footer>
    <!-- Footer Start-->
    <div class="footer-area footer-padding">
        <div class="container">
            <div class="row d-flex justify-content-between">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                    <div class="single-footer-caption mb-50">
                        <div class="single-footer-caption mb-30">
                            <!-- logo -->
                            <div class="footer-logo mb-25">
                                <img src="<?= Yii::getAlias('@web') . $logoPath ?>" alt="VetGestLink">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-5">
                    <div class="single-footer-caption mb-50">
                        <div class="footer-tittle">
                            <h4>Empresa</h4>
                            <ul>
                                <?php foreach ($companyLinks as $link): ?>
                                    <li><?= Html::a($link['label'], $link['url']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-7">
                    <div class="single-footer-caption mb-50">
                        <div class="footer-tittle">
                            <h4>Serviços</h4>
                            <ul>
                                <?php foreach ($serviceLinks as $link): ?>
                                    <li><?= Html::a($link['label'], $link['url']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                    <div class="single-footer-caption mb-50">
                        <div class="footer-tittle">
                            <h4>Contactos</h4>
                            <ul>
                                <?php foreach ($contactInfo as $info): ?>
                                    <li><?= Html::a($info['label'], $info['url']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End-->
</footer>
