<?php

use Berou\JonDevWheelCheck\Controller\DatabaseController;

include_once 'componants/card_wheels.php';

$databaseController = new DatabaseController();
$brands = $databaseController->get_all_brands();

if (!isset($id)) {

    return;
}
//get wheel by id
$wheel = $databaseController->get_whell_by_id($id)[0];
$brand = $databaseController->get_brand_by_id($wheel->wheel_brand_id)[0];
//get product in woocommerce
$product = wc_get_product($wheel->wheel_woocommerce_product_id);
$quantity = $product->get_stock_quantity();

?>

<div>
    <link rel="stylesheet" href="<?= $files . 'src/assets/css/energy_label.css' ?>">
    <link rel="stylesheet" href="<?= $files . 'src/assets/css/wheels_product_card.css' ?>">
    <link rel="stylesheet" href="<?= $files . 'src/assets/css/wheel_info_page.css' ?>">


    <section class="wheel-info-header">
        <div class="wheels-product-card-img">
            <img class="card-wheel-img" src="<?= wp_get_attachment_image_url($wheel->wheel_picture_id) ?>" alt="<?= $wheel->wheel_model  ?>">
            <div>
                <img class="card-brand-img" src="<?= wp_get_attachment_image_url($brand->brand_logo_id) ?>" alt="">
            </div>
        </div>
        <div>
            <div class="wheel-info-header-title">
                <h2> <?= $brand->brand_name  . ' ' . $wheel->wheel_width . '/' . $wheel->wheel_height . ' R' . $wheel->wheel_diameter . ' ' . $wheel->wheel_lad_index . ' ' . $wheel->wheel_speed . ' ' .  $wheel->wheel_profile ?></h2>
            </div>
            <div class="wheel-info-dimension">
                <?= $wheel->wheel_dimension ?>
            </div>
            <div class="wheels-product-card-icons energy-label">
                <div class="efficiency">
                    <img src="<?= $files . 'src/assets/img/fuel.svg' ?>" alt="">
                    <div class="grade <?= strtolower($wheel->wheel_fuel_efficiency) ?>"><?= $wheel->wheel_fuel_efficiency ?></div>
                </div>
                <div class="efficiency">
                    <img src="<?= $files . 'src/assets/img/cloud.svg' ?>" alt="">
                    <div class="grade <?= strtolower($wheel->wheel_ground_adhesion) ?>"><?= $wheel->wheel_ground_adhesion ?></div>
                </div>
                <div class="noise">
                    <img src="<?= $files . 'src/assets/img/sound.svg' ?>" alt="">
                    <div><?= $wheel->wheel_rolling_noise ?>db</div>
                </div>
                <div class="saison">
                    <img src="<?= $files . 'src/assets/img/sun.svg' ?>" alt="">
                    <div><?= $wheel->wheel_season ?></div>
                </div>
            </div>
        </div>
    </section>
    <section class="wheel-info-price">
        <div class="wheel-info-card-price">
            <div class="wheel-info-card-price-title">
                <h3 id="price-product-wheel"><?= $wheel->wheel_price ?> €</h3>
                <span id="quantity-product-wheel"><?= $quantity ?> en stock</span>

            </div>
            <div class="wheel-info-card-action-block">
                <div>
                    <div class="wheel-info-card-set-quantity">
                        <button class="b-l">-</button>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?= $quantity ?>">
                        <button class="b-r">+</button>


                    </div>

                </div>
                <button class="btn-info-card-add-cart" id="add-cart" data-product-id="<?= $wheel->wheel_woocommerce_product_id  ?>">Ajouter au panier</button>
            </div>
            <div>

                <div class="wheel-info-card-info-payment">
                    <img src="<?= $files . 'src/assets/img/lock.svg' ?>" width="33" alt="">
                    <span>Paiement sécurisé</span>
                </div>

            </div>
        </div>
    </section>
    <section class="wheel-info-description">
        <div class="wheel-info-description-title">
            <h3>Description</h3>
        </div>
        <div class="wheel-info-description-content">
            <p><?= $product->get_description() ?></p>

        </div>
    </section>

    <section class="wheel-info-features-content">
        <div class="wheel-info-features-title">
            <h3>Caractéristiques techniques</h3>
        </div>
        <div class="wheel-info-features">
            <span class="whell-info-ligne">
                <div>
                    <strong>Durée de garantie</strong>
                    <span>
                        <?= $wheel->wheel_guarantee ?>
                    </span>
                </div>
                <div>
                    <strong>Catégorie</strong>
                    <span>
                        <?= $wheel->wheel_category ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Sous-catégorie</strong>
                    <span>
                        <?= $wheel->wheel_subcategory ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Dimension pneu</strong>
                    <span>
                        <?= $wheel->wheel_dimension ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Pneu profil </strong>
                    <span>
                        <?= $wheel->wheel_profile ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Dimension</strong>
                    <span>
                        <?= $wheel->wheel_dimension ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Type de véhicule</strong>
                    <span>
                        <?= $wheel->wheel_vehicle_type ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Saison</strong>
                    <span>
                        <?= $wheel->wheel_season ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Largeur</strong>
                    <span>
                        <?= $wheel->wheel_width ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Hauteur</strong>
                    <span>
                        <?= $wheel->wheel_height ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Diamètre</strong>
                    <span>
                        <?= $wheel->wheel_diameter ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Indice de charge</strong>
                    <span>
                        <?= $wheel->wheel_load_index ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Indice de vitesse</strong>
                    <span>
                        <?= $wheel->wheel_speed ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>XL (Renforcé)</strong>
                    <span>
                        <?= $wheel->wheel_xl == 1 ? 'Oui' : 'Non' ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Run Flat</strong>
                    <span>
                        <?= $wheel->wheel_runflat == 1 ? 'Oui' : 'Non' ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Usage</strong>
                    <span>
                        <?= $wheel->wheel_use ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Marquage Hiver M+S</strong>
                    <span>
                        <?= $wheel->wheel_winter_mark == 1 ? 'Oui' : 'Non' ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Loi Montagne</strong>
                    <span>
                        <?= $wheel->wheel_mountain_law == 1 ? 'Oui' : 'Non' ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Efficacite carburant
                    </strong>
                    <span>
                        <?= $wheel->wheel_fuel_efficiency ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Adherence sol
                    </strong>
                    <span>
                        <?= $wheel->wheel_ground_adhesion ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Bruit roulement
                    </strong>
                    <span>
                        <?= $wheel->wheel_rolling_noise ?>
                    </span>
                </div>
            </span>
            <span class="whell-info-ligne">
                <div>
                    <strong>Niveau sonore
                    </strong>
                    <span>
                        <?= $wheel->wheel_noise_level ?>
                    </span>
                </div>
            </span>
        </div>

    </section>
    <section class="random-wheels-selection">
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
    </section>
</div>


<!-- JS -->
<script>
    jQuery(document).ready(function($) {
        const quantity = document.getElementById('quantity-product-wheel');
        const add = document.querySelector('.b-r');
        const remove = document.querySelector('.b-l');
        const addCart = document.getElementById('add-cart');
        const ajaxurl = '<?= admin_url('admin-ajax.php'); ?>';
        const product_id = '<?= $product->get_id() ?>';

        add.addEventListener('click', () => {
            let value = parseInt(document.getElementById('quantity').value);
            if (value < parseInt(quantity.innerText)) {
                value++;
                document.getElementById('quantity').value = value;
            }
        });

        remove.addEventListener('click', () => {
            let value = parseInt(document.getElementById('quantity').value);
            if (value > 1) {
                value--;
                document.getElementById('quantity').value = value;
            }
        });

        addCart.addEventListener('click', (e) => {
            e.preventDefault();


            const _quantity = document.getElementById('quantity').value;
            const data = {
                action: 'add_product_to_cart',
                product_id: product_id,
                quantity: _quantity
            };



            // Requête AJAX
            jQuery.post(ajaxurl, data, function(response) {
                window.location.href = '<?= get_permalink(wc_get_page_id('cart')) ?>';
            });
        });

    });
</script>