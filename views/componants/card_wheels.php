<?php


use Berou\JonDevWheelCheck\Controller\DatabaseController;

/**
 * Undocumented function
 *  generateCardWheel function
 * @param [type] $wheel
 * @param [type] $files
 * @return string
 */
function generateCardWheel($wheel, $files): string
{
    $url = plugin_dir_url(__DIR__);
    $base_url = get_site_url();
    $databaseController = new DatabaseController();
    $brand = $databaseController->get_brand_by_id($wheel->wheel_brand_id)[0];
    $cart_page_id = wc_get_page_id('cart');

    ob_start();

?>
    <div class="wheels-product-card">
        <div class="wheels-product-card-img">
            <img class="card-wheel-img" src="<?= wp_get_attachment_image_url($wheel->wheel_picture_id) ?>" alt="<?= $wheel->wheel_model  ?>">
            <div>
                <img class="card-brand-img" src="<?= wp_get_attachment_image_url($brand->brand_logo_id) ?>" alt="">
            </div>
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
        <div class="card-model-wheels">
            <a href="<?= $url . 'informations-sur-le-pneu?id=' . $wheel->id  ?>">
                <?=
                $brand->brand_name  . ' ' . $wheel->wheel_width . '/' . $wheel->wheel_height . ' R' . $wheel->wheel_diameter . ' ' . $wheel->wheel_lad_index . ' ' . $wheel->wheel_speed . ' ' .  $wheel->wheel_profile
                ?>
            </a>
        </div>
        <div class="card-wheels-dimension">
            <?= $wheel->wheel_dimension ?>
        </div>
        <div class="card-wheel-product-price">
            <?= $wheel->wheel_price ?> €
        </div>
        <div class="wheels-product-card-btn">
            <a id="btn-add-wheels-cart<?= $wheel->wheel_woocommerce_product_id ?>" href="" data-product-id="<?= $wheel->wheel_woocommerce_product_id ?>" class=" card-btn-orange card_cart_wheel_add">Ajouter au panier</a>
            <img class="wheel_card_loader_hiden" id="loader<?= $wheel->wheel_woocommerce_product_id ?>" src="<?= $files . 'src/assets/img/loader.svg' ?>" alt="">
            <a id="btn-show-cart<?= $wheel->wheel_woocommerce_product_id ?>" href="<?= $base_url . '?page_id=' . $cart_page_id  ?>" data-product-id="<?= $wheel->wheel_woocommerce_product_id ?>" class=" card-btn-orange " hidden>Voir le panier</a>

        </div>
    </div>


<?php

    return ob_get_clean();
    return ob_get_contents();
}

?>

<!-- JS -->

<script>
    jQuery(document).ready(function($) {
        const URLBASE = '<?= admin_url('admin-ajax.php'); ?>';

        document.querySelectorAll('.card_cart_wheel_add').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                var product_id = $(this).data('product-id'); // ID du produit à ajouter au panier
                var quantity = 1; // Quantité à ajouter
                const btnAddToCart = document.getElementById('btn-add-wheels-cart' + product_id);
                const btnShowCart = document.getElementById('btn-show-cart' + product_id);
                const loader = document.getElementById('loader' + product_id);

                // Afficher le loader
                btnAddToCart.className = 'add_card_wheel_hiden';
                //get btn show cart has hidden
                btnShowCart.hidden = true;

                loader.className = 'wheel_card_loadr_show';
                //force playing animation css
                loader.style.animation = 'spin 1s linear infinite';
                // Requête AJAX
                $.ajax({
                    type: 'POST',
                    url: URLBASE,
                    data: {
                        action: 'add_product_to_cart',
                        product_id: product_id,
                        quantity: quantity,
                    },
                    success: function(response) {
                        if (response.success) {

                            btnAddToCart.className = 'add_card_wheel_in';
                            btnShowCart.hidden = false;
                            loader.className = 'wheel_card_loader_hiden';

                            //  alert('Produit ajouté au panier');

                        } else {
                            if (response.data == 'Produit en rupture de stock') {
                                loader.className = 'wheel_card_loader_hiden';
                                //add p balise down loader
                                loader.insertAdjacentHTML('afterend', '<p class="wheel_stock_out">Produit en rupture de stock</p>');

                            } else {
                                btnAddToCart.className = 'add_card_wheel_in';
                                btnShowCart.hidden = false;
                                loader.className = 'wheel_card_loader_hiden';
                                alert('Erreur: ' + response.data);
                            }

                        }
                    },
                    error: function(error) {
                        console.error('Erreur AJAX:', error);
                    }
                });
            });

        });


    });
</script>