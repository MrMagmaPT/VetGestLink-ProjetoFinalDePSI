<?php
use yii\helpers\Html;
use yii\helpers\Url;

// Define default values for variables if not already set
$title = $title ?? 'Default Sidebar Title';
$items = $items ?? [
    ['label' => 'Dashboard', 'url' => ['/dashboard'], 'icon' => 'fa-tachometer-alt'],
    ['label' => 'Settings', 'url' => ['/settings'], 'icon' => 'fa-cogs'],
];
?>

<div class="sidebar-widget">
    <div class="widget-title">
        <h4><?= Html::encode($title) ?></h4>
    </div>
    <div class="widget-content">
        <ul class="sidebar-menu">
            <?php foreach ($items as $item): ?>
                <li class="sidebar-menu-item">
                    <?= Html::a(
                        '<i class="fas ' . ($item['icon'] ?? 'fa-circle') . '"></i> ' . Html::encode($item['label']),
                        $item['url'],
                        ['class' => 'sidebar-menu-link']
                    ) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<style>
.sidebar-widget {
    background: #fff;
    padding: 30px;
    margin-bottom: 30px;
    border-radius: 5px;
    box-shadow: 0 0 20px rgba(0,0,0,0.08);
}

.widget-title h4 {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #00d363;
}

.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-menu-item {
    margin-bottom: 10px;
}

.sidebar-widget .sidebar-menu-link {
    display: block;
    padding: 12px 15px;
    color: #333;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.sidebar-widget .sidebar-menu-link:hover {
background-color: #00d363;F
    color: #fff;
    transform: translateX(5px);
}

.sidebar-menu-link i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}
</style>
