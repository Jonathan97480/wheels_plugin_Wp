<?php

use Berou\JonDevWheelCheck\Controller\DatabaseController;


include_once 'componants/find_wheels_weedget.php';
include_once 'componants/card_wheels.php';

$databaseController = new DatabaseController();

$brand = $databaseController->get_brand_by_id($id)[0];
$allWheelsBrand = $databaseController->get_wheels_by_brand($id);
?>

<!-- load css -->
<link rel="stylesheet" href="<?= $files . 'src/assets/css/wheels_product_card.css' ?>">
<link rel="stylesheet" href="<?= $files . 'src/assets/css/wheels_brand_page.css' ?>">

<div class="container">
    <div class="panel-heading">
        <h3>Rechercher un pneu</h3>
        <?php
        $widget = getWidgetWheelsFind($files);
        echo $widget;
        ?>
    </div>

    <div class="bran-wheels-list">

        <h3>Les pneus de la marque <?= $brand->brand_name ?></h3>
        <div class="bran-wheels-list-header">
            <img src="<?= wp_get_attachment_image_url($brand->brand_logo_id) ?>" alt="<?= $brand->brand_name ?>">
        </div>
        <div class="bran-wheels-list-containt">
            <?php
            foreach ($allWheelsBrand as $wheel) {
                $card = generateCardWheel($wheel, $files);
                echo $card;
            }
            ?>
        </div>


    </div>
</div>