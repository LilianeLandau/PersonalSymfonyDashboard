Personal exercise - Symfony
Etapes EasyAdmin

**GUIDE TRES SUCCINCT POUR INSTALLER ET CONFIGURER EASY ADMIN**


[1	PREAMBULE	2](#_toc189146702)

[2	ADRESSE GIT REPOSITORY	2](#_toc189146703)

[3	INSTALLATION ET CONFIGURATION D'EASYADMIN 4 POUR SYMFONY 7	2](#_toc189146704)

[3.1	Installation d'EasyAdmin	2](#_toc189146705)

[3.2	Création des entités	2](#_toc189146706)

[3.2.1	Créer l'entité Category	2](#_toc189146707)

[3.2.2	Ajouter la méthode __toString() dans l’ entité Category :	2](#_toc189146708)

[3.2.3	Créer l'entité Product	3](#_toc189146709)

[3.3	Mettre à jour la base de données	3](#_toc189146710)

[3.4	Configuration d'EasyAdmin	3](#_toc189146711)

[3.4.1	Créer le Dashboard	3](#_toc189146712)

[3.4.2	Créer les CRUD Controllers	4](#_toc189146713)

[3.5	Finalisation et accès au dashboard	4](#_toc189146714)

[3.5.1	Créez le template pour le dashboard. Créez le fichier templates/admin/dashboard.html.twig :	4](#_toc189146715)

[3.5.2	Assurez-vous que votre pare-feu dans security.yaml autorise l'accès à /admin uniquement aux utilisateurs authentifiés avec le rôle ROLE_ADMIN.	4](#_toc189146716)

[3.5.3	Accédez à votre dashboard à l'URL : http://votre-site.com/admin	4](#_toc189146717)

[4	AFFICHER LES STATISTIQUES DANS EasyAdmin	4](#_toc189146718)

[4.1	Modifier DashboardController.php	4](#_toc189146719)

[4.2	Créer le template twig : templates/admin/dashboard.html.twig	5](#_toc189146720)

[5	EXEMPLES DE FICHIERS	5](#_toc189146721)

[5.1	Exemple dashboard.html.twig	5](#_toc189146722)

[5.2	Exemple DashboardController.php au départ, à la création de EasyAdmin	7](#_toc189146723)

[5.3	Exemple DashboardController.php avec statistiques	8](#_toc189146724)

[5.4	Exemple CategoryCrudController.Php	10](#_toc189146725)

[5.5	Exemple ProductCrudController.Php	11](#_toc189146726)

[6	AFFICHER LES STATISTIQUES DANS EasyAdmin SOUS FORME DE SCHEMAS	12](#_toc189146727)

[6.1	Installer Webpack Encore (si pas déjà fait)	12](#_toc189146728)

[6.2	initialiser les fichiers de configuration de Webpack Encore	12](#_toc189146729)

[6.3	1. Installation des dépendances nécessaires	12](#_toc189146730)

[6.4	Créer un fichier pour les graphiques dans assets/js/chart.js	12](#_toc189146731)

[6.5	Configurer Webpack Encore dans webpack.config.js	13](#_toc189146732)

[6.6	Modifier le package.json pour ajouter les scripts	15](#_toc189146733)

[6.7	modifier le template : templates/admin/dashboard.html.twig	16](#_toc189146734)

[6.8	Compiler les assets : npm run dev	19](#_toc189146735)


1. # <a name="_toc189146702"></a>**PREAMBULE**
Ce document est un guide très succinct pour créer dans Symfony le Dashboard dans EasyAdmin et afficher les statistiques sous forme de schémas

1. # <a name="_toc189146703"></a>**ADRESSE GIT REPOSITORY**
<https://github.com/LilianeLandau/PersonalSymfonyDashboard.git>

<git@github.com:LilianeLandau/PersonalSymfonyDashboard.git>

1. # <a name="_toc189146704"></a>**INSTALLATION ET CONFIGURATION D'EASYADMIN 4 POUR SYMFONY 7**
   1. ## <a name="_toc189146705"></a>***Installation d'EasyAdmin***
Ouvrez un terminal dans le répertoire de votre projet et exécutez :
~~~ bash
composer require easycorp/easyadmin-bundle
~~~
1. ## <a name="_toc189146706"></a>***Création des entités***
Avant de configurer EasyAdmin, assurez-vous d'avoir vos entités. Voici un exemple des entités nécessaires :
1. ### <a name="_toc189146707"></a>**Créer l'entité Category**
~~~ bash
php bin/console make:entity Category
~~~

Répondez aux questions pour créer les champs :

- name (string, 255, not null)
- description (text, null=true)
  1. ### <a name="_toc189146708"></a>**Ajouter la méthode \_\_toString() dans l’ entité Category :**

`    `//méthode magique pour afficher le nom de la catégorie

`    `public function \_\_toString(): string

`    `{

`        `return $this->name ?? '';

`    `}

1. ### <a name="_toc189146709"></a>**Créer l'entité Product**
~~~ bash
php bin/console make:entity Product
~~~

Répondez aux questions pour créer les champs :

- name (string, 255, not null)
- description (text, null=true)
- category (relation ManyToOne avec Category)
  1. ## <a name="_toc189146710"></a>***Mettre à jour la base de données***
~~~ bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
~~~
1. ## <a name="_toc189146711"></a>***Configuration d'EasyAdmin***
   1. ### <a name="_toc189146712"></a>**Créer le Dashboard**
~~~ bash
php bin/console make:admin:dashboard
~~~

Cela créera un fichier `src/Controller/Admin/DashboardController.php`. Modifiez-le comme suit :
~~~ php
<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mon Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Produits', 'fas fa-tag', Product::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
    }
}
~~~
~~~ php

~~~
1. ### <a name="_toc189146713"></a>Créer les CRUD Controllers
~~~ bash
php bin/console make:admin:crud
~~~

Suivez les questions interactives qui vous seront posées, notamment : 

- Choisir l'entité 
- Confirmer la génération du CRUD
  1. ## <a name="_toc189146714"></a>***Finalisation et accès au dashboard***
     1. ### <a name="_toc189146715"></a>**Créez le template pour le dashboard. Créez le fichier `templates/admin/dashboard.html.twig` :**
~~~ twig
{% extends '@EasyAdmin/page/content.html.twig' %}

{% block content %}
    <div class="jumbotron">
        <h1 class="display-4">Bienvenue dans l'administration</h1>
        <p class="lead">Gérez vos catégories, produits et utilisateurs depuis ce tableau de bord.</p>
    </div>
{% endblock %}
~~~
1. ### <a name="_toc189146716"></a>**Assurez-vous que votre pare-feu dans `security.yaml` autorise l'accès à `/admin` uniquement aux utilisateurs authentifiés avec le rôle ROLE\_ADMIN.**
1. ### <a name="_toc189146717"></a>**Accédez à votre dashboard à l'URL : `http://votre-site.com/admin`**

1. # <a name="_toc189146718"></a>**AFFICHER LES STATISTIQUES DANS EasyAdmin**
   1. ## <a name="_toc189146719"></a>***Modifier DashboardController.php***
- Modifiez votre DashboardController.php pour injecter les repositories nécessaires, ceux des entities utilisées.
- Les ajouter dans la fonction \_\_construct : 

`    `public function \_\_construct(

`        `private CategoryRepository $categoryRepository,

`        `private ProductRepository $productRepository,

`        `private UserRepository $userRepository

`    `) {}


- Modifier la méthode index() pour afficher les statistiques :
- ` `#[Route('/admin', name: 'admin')]
- `    `public function index(): Response
- `    `{

- `        `$stats = [
- `            `'categories' => $this->categoryRepository->count([]),
- `            `'products' => $this->productRepository->count([]),
- `            `'users' => $this->userRepository->count([])
- `        `];

- `        `return $this->render('admin/dashboard.html.twig', [
- `            `'stats' => $stats
- `        `]);
- `            `}

1. ## <a name="_toc189146720"></a>***Créer le template twig : templates/admin/dashboard.html.twig***


1. # <a name="_toc189146721"></a>**EXEMPLES DE FICHIERS** 
   1. ## <a name="_toc189146722"></a>***Exemple dashboard.html.twig***
{% extends '@EasyAdmin/page/content.html.twig' %}

{% block content %}

`    `<div class="container-fluid">

`        `<div

`            `class="row">

`            `{# Colonne gauche : Statistiques #}

`            `<div class="col-lg-8">

`                `<h1 class="display-4 mb-4">Bienvenue dans l'administration</h1>

`                `<p class="lead">Gérez vos catégories, produits et utilisateurs depuis ce tableau de bord.</p>

`                `{# Statistiques en colonne unique #}

`                `<div class="card mb-3">

`                    `<div class="card-body d-flex align-items-center">

`                        `<div class="col">

`                            `<h5 class="text-uppercase text-muted mb-0">Catégories</h5>

`                            `<span class="h2 font-weight-bold">{{ stats.categories }}</span>

`                        `</div>

`                        `<div class="col-auto">

`                            `<div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">

`                                `<i class="fas fa-list"></i>

`                            `</div>

`                        `</div>

`                    `</div>

`                `</div>

`                `<div class="card mb-3">

`                    `<div class="card-body d-flex align-items-center">

`                        `<div class="col">

`                            `<h5 class="text-uppercase text-muted mb-0">Produits</h5>

`                            `<span class="h2 font-weight-bold">{{ stats.products }}</span>

`                        `</div>

`                        `<div class="col-auto">

`                            `<div class="icon icon-shape bg-gradient-success text-white rounded-circle shadow">

`                                `<i class="fas fa-tag"></i>

`                            `</div>

`                        `</div>

`                    `</div>

`                `</div>

`                `<div class="card mb-3">

`                    `<div class="card-body d-flex align-items-center">

`                        `<div class="col">

`                            `<h5 class="text-uppercase text-muted mb-0">Utilisateurs</h5>

`                            `<span class="h2 font-weight-bold">{{ stats.users }}</span>

`                        `</div>

`                        `<div class="col-auto">

`                            `<div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">

`                                `<i class="fas fa-users"></i>

`                            `</div>

`                        `</div>

`                    `</div>

`                `</div>

`            `</div>

`        `{% endblock %}


1. ## <a name="_toc189146723"></a>***Exemple DashboardController.php au départ, à la création de EasyAdmin***
<?php

namespace App\Controller\Admin;

use App\Entity\Category;

use App\Entity\Product;

use App\Entity\User;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;

use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController

{

`    `#[Route('/admin', name: 'admin')]

`    `public function index(): Response

`    `{

`        `//return parent::index();

`        `return $this->render('admin/dashboard.html.twig');

`        `// Option 1. You can make your dashboard redirect to some common page of your backend

`        `//

`        `// $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

`        `// return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

`        `// Option 2. You can make your dashboard redirect to different pages depending on the user

`        `//

`        `// if ('jane' === $this->getUser()->getUsername()) {

`        `//     return $this->redirect('...');

`        `// }

`        `// Option 3. You can render some custom template to display a proper dashboard with widgets, etc.

`        `// (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)

`        `//

`        `// return $this->render('some/path/my-dashboard.html.twig');

`    `}

`    `public function configureDashboard(): Dashboard

`    `{

`        `return Dashboard::new()

`            `->setTitle('Dashboard');

`    `}

`    `public function configureMenuItems(): iterable

`    `{

`        `yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

`        `yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class);

`        `yield MenuItem::linkToCrud('Produits', 'fas fa-tag', Product::class);

`        `yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);

`        `// yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);

`    `}

}


1. ## <a name="_toc189146724"></a>***Exemple DashboardController.php avec statistiques***
<?php

namespace App\Controller\Admin;

use App\Entity\Category;

use App\Entity\Product;

use App\Entity\User;

use App\Repository\CategoryRepository;

use App\Repository\ProductRepository;

use App\Repository\UserRepository;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;

use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController

{

`    `public function \_\_construct(

`        `private CategoryRepository $categoryRepository,

`        `private ProductRepository $productRepository,

`        `private UserRepository $userRepository

`    `) {}

`    `#[Route('/admin', name: 'admin')]

`    `public function index(): Response

`    `{

`        `$stats = [

`            `'categories' => $this->categoryRepository->count([]),

`            `'products' => $this->productRepository->count([]),

`            `'users' => $this->userRepository->count([])

`        `];

`        `return $this->render('admin/dashboard.html.twig', [

`            `'stats' => $stats

`        `]);

`        `//return parent::index();

`        `//  return $this->render('admin/dashboard.html.twig');

`        `// Option 1. You can make your dashboard redirect to some common page of your backend

`        `//

`        `// $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

`        `// return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

`        `// Option 2. You can make your dashboard redirect to different pages depending on the user

`        `//

`        `// if ('jane' === $this->getUser()->getUsername()) {

`        `//     return $this->redirect('...');

`        `// }

`        `// Option 3. You can render some custom template to display a proper dashboard with widgets, etc.

`        `// (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)

`        `//

`        `// return $this->render('some/path/my-dashboard.html.twig');

`    `}

`    `public function configureDashboard(): Dashboard

`    `{

`        `return Dashboard::new()

`            `->setTitle('Dashboard');

`    `}

`    `public function configureMenuItems(): iterable

`    `{

`        `yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

`        `yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class);

`        `yield MenuItem::linkToCrud('Produits', 'fas fa-tag', Product::class);

`        `yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);

`        `// yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);

`    `}

}


1. ## <a name="_toc189146725"></a>***Exemple CategoryCrudController.Php***
<?php

namespace App\Controller\Admin;

use App\Entity\Category;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController

{

`    `public static function getEntityFqcn(): string

`    `{

`        `return Category::class;

`    `}

`    `public function configureFields(string $pageName): iterable

`    `{

`        `return [

`            `IdField::new('id')->hideOnForm(),

`            `TextField::new('name', 'Nom'),

`            `TextEditorField::new('description')

`        `];

`    `}

}


1. ## <a name="_toc189146726"></a>***Exemple ProductCrudController.Php***
<?php

namespace App\Controller\Admin;

use App\Entity\Product;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController

{

`    `public static function getEntityFqcn(): string

`    `{

`        `return Product::class;

`    `}

`    `public function configureFields(string $pageName): iterable

`    `{

`        `return [

`            `IdField::new('id')->hideOnForm(),

`            `TextField::new('name', 'Nom'),

`            `TextEditorField::new('description'),

`            `AssociationField::new('category', 'Catégorie')

`                `->setCrudController(CategoryCrudController::class)

`        `];

`    `}

}


1. # <a name="_toc189146727"></a>**AFFICHER LES STATISTIQUES DANS EasyAdmin SOUS FORME DE SCHEMAS**
   1. ## <a name="_toc189146728"></a>***Installer Webpack Encore (si pas déjà fait)***
composer require symfony/webpack-encore-bundle

npm install @symfony/webpack-encore --save-dev

1. ## <a name="_toc189146729"></a>***initialiser les fichiers de configuration de Webpack Encore***
\# Ceci va créer les fichiers webpack.config.js, package.json, etc.

npm install

1. ## <a name="_toc189146730"></a>***1. Installation des dépendances nécessaires***
Avant d'afficher des statistiques sous forme de graphiques, il faut installer un package de visualisation. On peut utiliser **Chart.js** via **Stimulus** :

composer require symfony/stimulus-bundle

npm install chart.js

npm add chart.js

1. ## <a name="_toc189146731"></a>***Créer un fichier pour les graphiques dans assets/js/chart.js***
import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function() {

`    `// Graphique circulaire

`    `const ctx = document.getElementById('statsChart').getContext('2d');

`    `new Chart(ctx, {

`        `type: 'pie',

`        `data: {

`            `labels: ['Catégories', 'Produits', 'Utilisateurs'],

`            `datasets: [{

`                `data: statsData, // Ces données seront définies dans le template

`                `backgroundColor: [

`                    `'rgb(255, 99, 132)',

`                    `'rgb(54, 162, 235)',

`                    `'rgb(255, 205, 86)'

`                `]

`            `}]

`        `},

`        `options: {

`            `responsive: true,

`            `plugins: {

`                `title: {

`                    `display: true,

`                    `text: 'Distribution des données'

`                `}

`            `}

`        `}

`    `});

`    `// Graphique en barres

`    `const ctxBar = document.getElementById('statsBarChart').getContext('2d');

`    `new Chart(ctxBar, {

`        `type: 'bar',

`        `data: {

`            `labels: ['Catégories', 'Produits', 'Utilisateurs'],

`            `datasets: [{

`                `label: 'Nombre d\'entrées',

`                `data: statsData,

`                `backgroundColor: [

`                    `'rgb(255, 99, 132)',

`                    `'rgb(54, 162, 235)',

`                    `'rgb(255, 205, 86)'

`                `]

`            `}]

`        `},

`        `options: {

`            `responsive: true,

`            `plugins: {

`                `title: {

`                    `display: true,

`                    `text: 'Statistiques en barres'

`                `}

`            `}

`        `}

`    `});

});

1. ## <a name="_toc189146732"></a>***Configurer Webpack Encore dans webpack.config.js***
const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.

// It's useful when you use tools that rely on webpack.config.js file.

if (!Encore.isRuntimeEnvironmentConfigured()) {

`    `Encore.configureRuntimeEnvironment(process.env.NODE\_ENV || 'dev');

}

Encore

`    `// directory where compiled assets will be stored

.setOutputPath('public/build/')

`    `// public path used by the web server to access the output path

.setPublicPath('/build')

`    `// only needed for CDN's or subdirectory deploy

`    `//.setManifestKeyPrefix('build/')

`    `/\*

`     `\* ENTRY CONFIG

`     `\*

`     `\* Each entry will result in one JavaScript file (e.g. app.js)

`     `\* and one CSS file (e.g. app.css) if your JavaScript imports CSS.

`     `\*/

.addEntry('app', './assets/js/chart.js')

`    `// When enabled, Webpack "splits" your files into smaller pieces for greater optimization.

.splitEntryChunks()

`    `// will require an extra script tag for runtime.js

`    `// but, you probably want this, unless you're building a single-page app

.enableSingleRuntimeChunk()

`    `/\*

`     `\* FEATURE CONFIG

`     `\*

`     `\* Enable & configure other features below. For a full

`     `\* list of features, see:

`     `\* https://symfony.com/doc/current/frontend.html#adding-more-features

`     `\*/

.cleanupOutputBeforeBuild()

.enableBuildNotifications()

.enableSourceMaps(!Encore.isProduction())

`    `// enables hashed filenames (e.g. app.abc123.css)

.enableVersioning(Encore.isProduction())

`    `// configure Babel

`    `// .configureBabel((config) => {

`    `//     config.plugins.push('@babel/a-babel-plugin');

`    `// })

`    `// enables and configure @babel/preset-env polyfills

.configureBabelPresetEnv((config) => {

`        `config.useBuiltIns = 'usage';

`        `config.corejs = '3.38';

`    `})

`    `// enables Sass/SCSS support

`    `//.enableSassLoader()

`    `// uncomment if you use TypeScript

`    `//.enableTypeScriptLoader()

`    `// uncomment if you use React

`    `//.enableReactPreset()

`    `// uncomment to get integrity="..." attributes on your script & link tags

`    `// requires WebpackEncoreBundle 1.4 or higher

`    `//.enableIntegrityHashes(Encore.isProduction())

`    `// uncomment if you're having problems with a jQuery plugin

`    `//.autoProvidejQuery()

;

module.exports = Encore.getWebpackConfig();


1. ## <a name="_toc189146733"></a>***Modifier le package.json pour ajouter les scripts***
{

`  `"name": "dashboard",

`  `"version": "1.0.0",

`  `"private": true,



`  `"dependencies": {

`    `"chart.js": "^4.4.7"

`  `},

`  `"devDependencies": {

`    `"@symfony/webpack-encore": "^5.0.1"

`  `},



`    `"scripts": {

`        `"dev-server": "encore dev-server",

`        `"dev": "encore dev",

`        `"watch": "encore dev --watch",

`        `"build": "encore production --progress"

`    `}

}




1. ## <a name="_toc189146734"></a>***modifier le template : `templates/admin/dashboard.html.twig `***
{% extends '@EasyAdmin/page/content.html.twig' %}

{% block head\_javascript %}

`    `{{ parent() }}

`    `{{ encore\_entry\_script\_tags('app') }}

`    `<script>

`        `const statsData = [{{ stats.categories }}, {{ stats.products }}, {{ stats.users }}];

`    `</script>

{% endblock %}

{% block main %}

`    `<div class="container-fluid">

`        `<div

`            `class="row">

`            `{# Colonne gauche : Statistiques #}

`            `<div class="col-lg-8">

`                `<h1 class="display-4 mb-4">Bienvenue dans l'administration</h1>

`                `<h2>Statistiques</h2>

`                `{# Statistiques en colonne unique #}

`                `<div class="card mb-3">

`                    `<div class="card-body d-flex align-items-center">

`                        `<div class="col">

`                            `<h5 class="text-uppercase text-muted mb-0">Catégories</h5>

`                            `<span class="h2 font-weight-bold">{{ stats.categories }}</span>

`                        `</div>

`                        `<div class="col-auto">

`                            `<div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">

`                                `<i class="fas fa-list"></i>

`                            `</div>

`                        `</div>

`                    `</div>

`                `</div>

`                `<div class="card mb-3">

`                    `<div class="card-body d-flex align-items-center">

`                        `<div class="col">

`                            `<h5 class="text-uppercase text-muted mb-0">Produits</h5>

`                            `<span class="h2 font-weight-bold">{{ stats.products }}</span>

`                        `</div>

`                        `<div class="col-auto">

`                            `<div class="icon icon-shape bg-gradient-success text-white rounded-circle shadow">

`                                `<i class="fas fa-tag"></i>

`                            `</div>

`                        `</div>

`                    `</div>

`                `</div>

`                `<div class="card mb-3">

`                    `<div class="card-body d-flex align-items-center">

`                        `<div class="col">

`                            `<h5 class="text-uppercase text-muted mb-0">Utilisateurs</h5>

`                            `<span class="h2 font-weight-bold">{{ stats.users }}</span>

`                        `</div>

`                        `<div class="col-auto">

`                            `<div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">

`                                `<i class="fas fa-users"></i>

`                            `</div>

`                        `</div>

`                    `</div>

`                `</div>

`            `</div>

`        `</div>

`        `<div

`            `class="row mt-4">

`            `{# Graphique circulaire #}

`            `<div class="col-md-6">

`                `<div class="card">

`                    `<div class="card-body">

`                        `<canvas id="statsChart"></canvas>

`                    `</div>

`                `</div>

`            `</div>

`            `{# Graphique en barres #}

`            `<div class="col-md-6">

`                `<div class="card">

`                    `<div class="card-body">

`                        `<canvas id="statsBarChart"></canvas>

`                    `</div>

`                `</div>

`            `</div>

`        `</div>

`    `</div>

`    `<script>

`        `// Configuration du graphique circulaire

const ctx = document.getElementById('statsChart').getContext('2d');

new Chart(ctx, {

type: 'pie',

data: {

labels: [

'Catégories', 'Produits', 'Utilisateurs'

],

datasets: [

{

data: [

{{ stats.categories }}, {{ stats.products }}, {{ stats.users }}

],

backgroundColor: ['rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)']

}

]

},

options: {

responsive: true,

plugins: {

title: {

display: true,

text: 'Distribution des données'

}

}

}

});

// Configuration du graphique en barres

const ctxBar = document.getElementById('statsBarChart').getContext('2d');

new Chart(ctxBar, {

type: 'bar',

data: {

labels: [

'Catégories', 'Produits', 'Utilisateurs'

],

datasets: [

{

label: 'Nombre d\'entrées',

data: [

{{ stats.categories }}, {{ stats.products }}, {{ stats.users }}

],

backgroundColor: ['rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)']

}

]

},

options: {

responsive: true,

plugins: {

title: {

display: true,

text: 'Statistiques en barres'

}

}

}

});

`    `</script>

{% endblock %}


1. ## <a name="_toc189146735"></a>***Compiler les assets : npm run dev***


Page 1** sur 19**

