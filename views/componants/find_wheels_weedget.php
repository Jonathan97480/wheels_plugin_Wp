<?php

use Berou\JonDevWheelCheck\Controller\DatabaseController;
use Berou\JonDevWheelCheck\Controller\variableController;


/**
 * Undocumented function
 * getWidgetWheelsFind function
 * @param [type] $files
 * @return string
 */
function getWidgetWheelsFind($files, $w = 125, $h = 25, $d = 10, $ls = 65, $v = 'Q', $is_ru_f = 0, $s = 'Toutes saisons', $type_v = 'Toutes catégories', $brand = -1, $btn_v = 'Rechercher mes pneus'): string
{

    $databaseController = new DatabaseController();
    $variableController = new variableController();

    $brands = $databaseController->get_all_brands();
    $url = plugin_dir_url(__DIR__);

    /* Slect variable */
    $W = $variableController::pneusWidth;
    $H = $variableController::pneusHeight;
    $R = $variableController::pneusDiameter;
    $LS = $variableController::pneusLoadIndex;
    $V = $variableController::pneusSpeed;
    /* filter variable */
    $mainCategorys = $variableController::mainCategorys;
    $saison = $variableController::pneusSaision;

    ob_start();

?>


    <!-- Widget -->
    <link rel="stylesheet" href="<?= $files . 'src/assets/css/find_wheels.css' ?>">
    <script src="<?= $files . 'src/assets/js/find_wheels_widget.js' ?>"></script>

    <div class="widget-find-wheels">
        <form id="form_find_wheels" action="<?= $url . "Resultat-de-la-recherche-de-pneus" ?>" method="GET">
            <div class="widget-find-wheels_containt">
                <div class="widget-find-wheels_containt-top">
                    <div class="normal-search-by-dimensions">
                        <div class="normal-search-picture">
                            <img src="<?= $files . 'src/assets/img/wheels.png' ?>" alt="">

                        </div>
                        <div class="wheels-containt-text">
                            <span class="wheels-info-widget-W " id="wheel_width">
                                <?= $w ?>
                            </span>
                            <span class="wheels-info-widget-/">/</span>
                            <span class="wheels-info-widget-H" id="wheel_height"><?= $h ?></span>
                            <span class="wheels-info-widget-R">
                                R
                            </span>
                            <span class="wheels-info-widget-D" id="wheel_diameter"><? $d ?></span>
                            <span class="wheels-info-widget-LS" id="wheel_lad_index">
                                <?= $ls ?>
                            </span>
                            <span class="wheels-info-widget-V" id="wheel_speed">
                                <?= $v ?>
                            </span>
                        </div>
                    </div>

                </div>
                <div>

                    <div class="widget-find-wheels-input-block">
                        <span class="input-block border-right ">
                            <h4>Largeur</h4>
                            <select name="width" id="select_wheel_width">
                                <?php foreach ($W as $_w) : ?>

                                    <?php if ($_w == $w) {
                                    ?>
                                        <option value="<?= $_w ?>" selected><?= $_w ?></option>
                                    <?php
                                    } else {
                                    ?>
                                        <option value="<?= $_w ?>"><?= $_w ?></option>
                                    <?php
                                    }
                                    ?>
                                <?php endforeach; ?>
                            </select>
                        </span>
                        <span class="input-block border-right ">
                            <h4>Hauteur</h4>
                            <select name="height" id="select_wheel_height">
                                <?php foreach ($H as $_h) : ?>
                                    <?php
                                    if ($_h == $h) {
                                    ?>
                                        <option value="<?= $_h ?>" selected><?= $_h ?></option>
                                    <?php
                                    } else {
                                    ?>
                                        <option value="<?= $_h ?>"><?= $_h ?></option>
                                    <?php
                                    }
                                    ?>
                                <?php endforeach; ?>

                            </select>
                        </span>
                        <span class="input-block">
                            <h4>Diamètre</h4>
                            <select name="diameter" id="select_wheel_diameter">
                                <?php foreach ($R as $_r) : ?>
                                    <?php
                                    if ($_r == $d) {
                                    ?>
                                        <option value="<?= $_r ?>" selected><?= $_r ?></option>
                                    <?php
                                    } else {
                                    ?>
                                        <option value="<?= $_r ?>"><?= $_r ?></option>
                                    <?php
                                    }
                                    ?>
                                <?php endforeach; ?>
                            </select>
                        </span>

                    </div>
                    <div class="widget-find-wheels-input-block">
                        <span class="input-block border-right ">
                            <h4>Charge</h4>
                            <select name="load_index" id="select_wheel_lad_index">
                                <?php foreach ($LS as $_ls) : ?>
                                    <?php
                                    if ($_ls == $ls) {
                                    ?>
                                        <option value="<?= $_ls ?>" selected><?= $_ls ?></option>
                                    <?php

                                    } else {
                                    ?>
                                        <option value="<?= $_ls ?>"><?= $_ls ?></option>
                                    <?php
                                    }
                                    ?>
                                <?php endforeach; ?>
                            </select>
                        </span>
                        <span class="input-block">
                            <h4>Vitesse</h4>
                            <select name="speed" id="select_wheel_speed">
                                <?php foreach ($V as $_v) : ?>
                                    <?php
                                    if ($_v == $v) {
                                    ?>
                                        <option value="<?= $_v ?>" selected><?= $_v ?></option>
                                    <?php
                                    } else {
                                    ?>
                                        <option value="<?= $_v ?>"><?= $_v ?></option>
                                    <?php
                                    }
                                    ?>
                                <?php endforeach; ?>
                            </select>
                        </span>
                    </div>
                </div>
                <div class="wheels_filter_block">
                    <span class="whell-filter-open-icon" id="open_filter">
                        <img src="<?= $files . 'src/assets/img/filter.svg' ?>" alt="" width="32">
                        <span>Affinée la recherche.</span>
                    </span>
                    <div id="filter_block" class="filter-hiden filter_block">
                        <span class="input-filter-block">
                            <label for="isRunFlat">Run flat</label>
                            <?php if ($is_ru_f == 1) : ?>
                                <input type="checkbox" name="isRunFlat" id="isRunFlat" value="1" checked>
                            <?php else : ?>
                                <input type="checkbox" name="isRunFlat" id="isRunFlat" value="0">
                            <?php endif; ?>
                        </span>
                        <span class="input-filter-block">
                            <label for="saison">Saison </label>
                            <select name="saison" id="saison">
                                <?php foreach ($saison as $_s) : ?>
                                    <?php if ($s == $_s) : ?>
                                        <option value="<?= $_s ?>" selected><?= $_s ?></option>
                                    <?php else : ?>
                                        <option value="<?= $_s ?>"><?= $_s ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </span>
                        <span class="input-filter-block">
                            <label for="Vehicule">Type Véhicule</label>
                            <select name="typeVehicule" id="Vehicule">
                                <?php foreach ($mainCategorys as $mainCategory) : ?>
                                    <?php if ($mainCategory == $type_v) : ?>
                                        <option value="<?= $mainCategory ?>" selected><?= $mainCategory ?></option>
                                    <?php else : ?>
                                        <option value="<?= $mainCategory ?>"><?= $mainCategory ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </span>
                        <span class="input-filter-block">
                            <label for="bran_list"> Marques pneu </label>
                            <select name="brand" id="bran_list">

                                <?php foreach ($brands as $_brand) : ?>
                                    <?php
                                    if ($_brand->id == $brand) {
                                    ?>
                                        <option value="<?= $_brand->id ?>" selected><?= $_brand->brand_name ?></option>
                                    <?php
                                    } else {
                                    ?>
                                        <option value="<?= $_brand->id ?>"><?= $_brand->brand_name ?></option>
                                    <?php
                                    }
                                    ?>
                                <?php endforeach; ?>
                                <option value="-1">Toutes les marques</option>
                            </select>
                        </span>
                    </div>
                </div>
                <div>
                    <input type="submit" value="<?= $btn_v ?>" class="wheels-btn-action">
                </div>

        </form>
    </div>
<?php
    return ob_get_clean();
}
