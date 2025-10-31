# Widgets do Frontend - VetGestLink

## Estrutura Organizada

Todos os widgets estão localizados em `frontend/widgets/` e suas views em `frontend/widgets/views/`.

## Widgets Disponíveis

### 1. **ThemeAssets** - Gerenciamento de CSS
Carrega automaticamente todos os arquivos CSS do tema.

**Uso no Layout:**
```php
<?php ThemeAssets::widget(); ?>
```

**Uso Personalizado:**
```php
<?php 
ThemeAssets::widget([
    'useDefaultCss' => false,
    'cssFiles' => ['bootstrap.min.css', 'style.css']
]); 
?>
```

---

### 2. **ThemeScripts** - Gerenciamento de JavaScript
Carrega automaticamente todos os scripts do tema.

**Uso no Layout:**
```php
<?php ThemeScripts::widget(); ?>
```

**Uso Personalizado:**
```php
<?php 
ThemeScripts::widget([
    'useDefaultScripts' => false,
    'jsFiles' => ['bootstrap.min.js', 'main.js']
]); 
?>
```

---

### 3. **Header** - Cabeçalho do Site
Widget reutilizável para o header com menu dinâmico.

**Uso:**
```php
<?= Header::widget() ?>
```

**Personalização:**
```php
<?= Header::widget([
    'logoPath' => '/assets/img/custom-logo.png',
    'menuItems' => [
        ['label' => 'Home', 'url' => ['site/index']],
        ['label' => 'Sobre', 'url' => ['site/about']],
    ]
]) ?>
```

---

### 4. **Footer** - Rodapé do Site
Widget reutilizável para o footer.

**Uso:**
```php
<?= Footer::widget() ?>
```

**Personalização:**
```php
<?= Footer::widget([
    'logoPath' => '/assets/img/custom-logo.png',
    'companyLinks' => [
        ['label' => 'Home', 'url' => ['site/index']],
    ],
    'serviceLinks' => [
        ['label' => 'Consultas', 'url' => ['#']],
    ],
    'contactInfo' => [
        ['label' => '123-456-789', 'url' => 'tel:123456789'],
    ]
]) ?>
```

---

### 5. **Preloader** - Animação de Carregamento
Exibe uma animação enquanto a página carrega.

**Uso:**
```php
<?= Preloader::widget() ?>
```

**Personalização:**
```php
<?= Preloader::widget(['logoPath' => '/assets/img/logo.png']) ?>
```

---

### 6. **ScrollToTop** - Botão de Voltar ao Topo
Botão flutuante para rolar a página ao topo.

**Uso:**
```php
<?= ScrollToTop::widget() ?>
```

---

### 7. **Breadcrumb** - Navegação de Páginas
Breadcrumb personalizado com imagem de fundo.

**Uso:**
```php
<?= Breadcrumb::widget([
    'title' => 'Meus Animais',
    'items' => [
        ['label' => 'Animais', 'url' => ['animais/index']],
        ['label' => 'Detalhes'],
    ]
]) ?>
```

---

### 8. **Alert** - Mensagens Flash
Exibe mensagens de sucesso, erro, aviso, etc.

**Uso no Controller:**
```php
Yii::$app->session->setFlash('success', 'Operação realizada com sucesso!');
Yii::$app->session->setFlash('error', 'Ocorreu um erro.');
Yii::$app->session->setFlash('warning', 'Atenção!');
Yii::$app->session->setFlash('info', 'Informação importante.');
```

**Uso na View:**
```php
<?= Alert::widget() ?>
```

---

### 9. **Sidebar** - Menu Lateral
Menu lateral para páginas internas.

**Uso:**
```php
<?= Sidebar::widget() ?>
```

**Personalização:**
```php
<?= Sidebar::widget([
    'title' => 'Navegação',
    'items' => [
        ['label' => 'Dashboard', 'url' => ['site/index'], 'icon' => 'fa-home'],
        ['label' => 'Perfil', 'url' => ['user/profile'], 'icon' => 'fa-user'],
    ]
]) ?>
```

---

## Vantagens da Estrutura de Widgets

✅ **Reutilização**: Use os widgets em qualquer view  
✅ **Manutenção**: Altere em um lugar, afeta todo o site  
✅ **Organização**: Código limpo e bem estruturado  
✅ **Personalização**: Fácil customização via parâmetros  
✅ **Performance**: Carregamento otimizado de assets  

---

## Exemplo de Layout Completo

```php
<?php
use frontend\widgets\ThemeAssets;
use frontend\widgets\ThemeScripts;
use frontend\widgets\Navbar;
use frontend\widgets\Footer;
use frontend\widgets\Preloader;
use frontend\widgets\ScrollToTop;
use frontend\widgets\Alert;
?>

<?php $this->beginPage() ?>
<!doctype html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?= Html::encode($this->title) ?></title>
    <?php ThemeAssets::widget(); ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?= Preloader::widget() ?>
<?= Navbar::widget() ?>

<main>
    <?= Alert::widget() ?>
    <?= $content ?>
</main>

<?= Footer::widget() ?>
<?= ScrollToTop::widget() ?>
<?php ThemeScripts::widget(); ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
```

---

## Exemplo de View com Sidebar

```php
<?php
use frontend\widgets\Breadcrumb;
use frontend\widgets\Sidebar;
?>

<?= Breadcrumb::widget(['title' => 'Minha Página']) ?>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?= Sidebar::widget() ?>
        </div>
        <div class="col-md-9">
            <!-- Conteúdo da página -->
        </div>
    </div>
</div>
```

---

## Estrutura de Arquivos

```
frontend/
├── widgets/
│   ├── Alert.php
│   ├── Breadcrumb.php
│   ├── Footer.php
│   ├── Header.php
│   ├── Preloader.php
│   ├── ScrollToTop.php
│   ├── Sidebar.php
│   ├── ThemeAssets.php
│   ├── ThemeScripts.php
│   └── views/
│       ├── alert.php
│       ├── breadcrumb.php
│       ├── footer.php
│       ├── header.php
│       ├── preloader.php
│       ├── scroll-to-top.php
│       └── sidebar.php
└── views/
    └── layouts/
        └── main.php
```

