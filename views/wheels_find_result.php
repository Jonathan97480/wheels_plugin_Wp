<?php


use Berou\JonDevWheelCheck\Controller\DatabaseController;

include_once 'componants/card_wheels.php';
include_once 'componants/find_wheels_weedget.php';

$databaseController = new DatabaseController();

$result_find = $databaseController->find_wheels_by_widget(
    $width,
    $height,
    $diameter,
    $load_index,
    $speed_index,
    $is_run_flat,
    $saison,
    $type_vehicule,
    $brand
);



?>

<div>
    <link rel="stylesheet" href="<?= $files . 'src/assets/css/energy_label.css' ?>">
    <link rel="stylesheet" href="<?= $files . 'src/assets/css/wheels_product_card.css' ?>">

    <div class="panel-heading">

        <?php
        $widget = getWidgetWheelsFind(
            $files,
            $width,
            $height,
            $diameter,
            $load_index,
            $speed_index,
            $is_run_flat,
            $saison,
            $type_vehicule,
            $brand,
            'Faire une autre Recherche'
        );
        echo $widget;

        ?>
        <h3>Résultat de la recherche </h3>
        <?php
        if (count($result_find) <= 0) {
        ?>
            <div class="alert-no-result" role="alert">
                Aucun résultat trouvé
            </div>
        <?php
            return;
        }

        ?>
        <div class="bran-wheels-list">
            <div class="bran-wheels-list-containt">
                <?php
                foreach ($result_find as $wheel) {
                    $card = generateCardWheel($wheel, $files);
                    echo $card;
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- css -->
<style>
    .bran-wheels-list-containt {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
    }
</style>