<?php

use Berou\JonDevWheelCheck\Controller\DatabaseController;
use Berou\JonDevWheelCheck\Controller\variableController;

include_once 'componants/card_wheels.php';
include_once 'componants/find_wheels_weedget.php';
$databaseController = new DatabaseController();
$variableController = new variableController();

$brands = $databaseController->get_all_brands();
$url = plugin_dir_url(__DIR__);
$base_url = get_site_url();





?>
<div class="container">
    <!-- load css -->
    <link rel="stylesheet" href="<?= $files . 'src/assets/css/find_wheels_page.css' ?>">
    <link rel="stylesheet" href="<?= $files . 'src/assets/css/energy_label.css' ?>">


    <!-- laod js -->
    <div class="panel-heading">
        <h3>Rechercher un pneu</h3>
        <?php
        $widget = getWidgetWheelsFind($files);
        echo $widget;
        ?>
    </div>
    <!-- Brands List -->
    <div class="brands-List">
        <h3>Nos marques de pneus</h3>
        <div class="brands-List-containt">
            <?php foreach ($brands as $brand) : ?>
                <div class="brands-List-containt-item ">
                    <a href="<?= $url . 'pneus-par-marques?id=' . $brand->id ?>">
                        <img src="<?= wp_get_attachment_image_url($brand->brand_logo_id) ?>" alt="<?= $brand->brand_name ?>">
                    </a>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <!-- Astuce Brands select -->
    <div class="brands-select">
        <h3>Quelle marque de pneus choisir ?</h3>
        <div class="brands-select-paragraphe">
            <p>Avec le nombre important de pneus présents sur le marché, il peut être difficile de savoir quelle marque de pneus choisir au moment d'effectuer un changement sur votre véhicule.</p>
            <p>
                Pneus premium : Conçus pour les conducteurs les plus exigeants, les pneus premium comme Continental, Michelin ou Pirelli seront parfaits pour ceux qui passent beaucoup de temps sur la route ou recherchent les meilleures performances. Vous les retrouverez le plus souvent équipés en monte d'origine sur les véhicules neufs. Si vous recherchez la sensation de conduite la plus proche de celle que vous aviez à l'achat de votre véhicule, ces pneus seront donc votre premier choix.
            </p>
            <p>
                Pneus medium : Si vous cherchez quelle marque de pneu choisir avec un budget plus limité sans faire de compromis sur la sécurité, tournez-vous vers les catégories medium représentées par des marques comme Feu Vert, Hankook ou Nexen. Ces modèles bénéficient d'une forte expertise, mais avec en plus un souci d'accessibilité pour les conducteurs réguliers.
            </p>
            <p>
                Pneus budget : Pour ceux qui souhaitent limiter leurs dépenses, les pneus budget proposés par exemple par Rovelo ou Tracmax sont la meilleure option. En termes de prix, ils sont imbattables et correspondent bien entendu aux normes pour vous garantir de rouler en toute sécurité. Ils conviendront parfaitement aux conducteurs citadins et occasionnels.
            </p>
        </div>
    </div>
    <!-- Random pneu selection -->
    <div class="random-pneu-selection">
        <h3>Selection de pneus aléatoire</h3>
        <div class="random-pneu-selection-containt">
            <?php
            $random_wheels = $databaseController->get_random_wheels(4);

            //var_dump($random_wheels);
            foreach ($random_wheels as $wheel) {
                $card =  generateCardWheel($wheel, $files);
                echo $card;
            }
            ?>
        </div>
    </div>

    <!--choice wheels dimension  -->
    <div class="wheels-dimension-choise">
        <h3>Comment choisir la bonne dimension pour mes pneus ?
        </h3>

        <p>Pour acheter un pneu il faut s’intéresser à la dimension de celui-ci.Un pneu se caractérise par différentes valeurs, toutes inscrites directement sur son flanc :</p>

        <p>Le premier nombre, constitué de 3 chiffres, correspond à la largeur du pneu en millimètres.</p>
        <p>Le second, composé de 2 chiffres, vous indique le rapport entre sa hauteur et sa largeur en pourcentage.</p>
        <p>La lettre ensuite inscrite, un R, signifie Radial. Il s’agit d’une inscription vous indiquant que le pneu est conçu pour résister à un usage fréquent tout en minimisant votre consommation de carburant. Les pneus des voitures de course sont, eux, estampillés ZR, signifiant que le pneu est conçu pour rouler à plus de 300 km/h.</p>
        <p>Le dernier nombre, avec 2 chiffres, correspond au diamètre de la jante.</p>
        <p>Toujours sur le flanc, une autre valeur doit attirer votre attention. Il s’agit de l’indice de charge. Il vous informe de la capacité maximale de charge du pneu.</p>
        <p>Avant de remplacer vos pneus actuels, prenez soin de noter ces différentes valeurs. Elles vous permettront de choisir des éléments de dimensions identiques.</p>
        <p>Avant de remplacer vos pneus actuels, prenez soin de noter ces différentes valeurs. Elles vous permettront de choisir des éléments de dimensions identiques.</p>
    </div>

</div>
</div>