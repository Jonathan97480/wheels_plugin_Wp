<?php

/* Exemple $pneu contenu de la variable 
array(1) { [0]=> object(stdClass)#3368 (28) { ["id"]=> string(2) "17" ["wheel_guarantee"]=> string(15) "Aucune garantie" ["wheel_model"]=> string(5) "test5" ["wheel_category"]=> string(8) "Tourisme" ["wheel_subcategory"]=> string(12) "Pneus larges" ["wheel_dimension"]=> string(14) "45/4545R77 45Q" ["wheel_profile"]=> string(6) "gfgdgd" ["wheel_vehicle_type"]=> string(1) "0" ["wheel_season"]=> string(5) "Hiver" ["wheel_width"]=> string(2) "45" ["wheel_height"]=> string(4) "4545" ["wheel_diameter"]=> string(2) "77" ["wheel_load_index"]=> string(2) "45" ["wheel_speed"]=> string(1) "Q" ["wheel_xl"]=> string(1) "0" ["wheel_runflat"]=> string(1) "0" ["wheel_use"]=> string(0) "" ["wheel_winter_mark"]=> string(1) "0" ["wheel_mountain_law"]=> string(1) "0" ["wheel_fuel_efficiency"]=> string(1) "A" ["wheel_ground_adhesion"]=> string(1) "A" ["wheel_rolling_noise"]=> string(0) "" ["wheel_noise_level"]=> string(1) "A" ["wheel_woocommerce_product_id"]=> string(2) "37" ["wheel_brand_id"]=> string(1) "2" ["wheel_price"]=> string(3) "124" ["created_at"]=> string(19) "2024-08-23 10:42:38" ["updated_at"]=> string(19) "2024-08-23 10:42:38" } }
*/

use Berou\JonDevWheelCheck\Controller\DatabaseController;
use Berou\JonDevWheelCheck\Controller\VariableController;

$databaseController = new DatabaseController();
$variableController = new VariableController();
$maintCategorys = $variableController::mainCategorys;
$subCategorys = $variableController::subCategorys;
$pneusSpeed = $variableController::pneusSpeed;
$pneusGroundAdhesion = $variableController::pneusGroundAdhesion;
$pneusNoiseLevel = $variableController::pneusNoiseLevel;
$pneusSaision = $variableController::pneusSaision;
$pneusGuarantee = $variableController::pneusGuarantee;
$image = wp_get_attachment_image_url($pneu[0]->wheel_picture_id, 'full');

//Variable Notificaion
$products   = [];
$notifications = [];

//Update pneu
if (isset($_POST['wheel_model'])) {
    $brandName = $databaseController->get_brand_by_id($pneu[0]->wheel_brand_id)[0]->brand_name;
    $model = $brandName . ' ' . $_POST['wheel_width'] . '/' . $_POST['wheel_height'] . 'R' . $_POST['wheel_diameter'] . ' ' . $_POST['wheel_load_index'] . $_POST['wheel_speed'] . ' ' . $_POST['wheel_profile'];

    $data = [
        'wheel_guarantee' => $_POST['wheel_guarantee'],
        'wheel_model' => $model,
        'wheel_category' => $_POST['wheel_category'],
        'wheel_subcategory' => $_POST['wheel_subcategory'],
        'wheel_dimension' => $_POST['wheel_dimension'],
        'wheel_profile' => $_POST['wheel_profile'],
        'wheel_vehicle_type' => $_POST['wheel_vehicle_type'],
        'wheel_season' => $_POST['wheel_season'],
        'wheel_width' => $_POST['wheel_width'],
        'wheel_height' => $_POST['wheel_height'],
        'wheel_diameter' => $_POST['wheel_diameter'],
        'wheel_load_index' => $_POST['wheel_load_index'],
        'wheel_speed' => $_POST['wheel_speed'],
        'wheel_xl' => isset($_POST['wheel_xl']) ? 1 : 0,
        'wheel_runflat' => isset($_POST['wheel_runflat']) ? 1 : 0,
        'wheel_use' => $_POST['wheel_use'],
        'wheel_winter_mark' => isset($_POST['wheel_winter_mark']) ? 1 : 0,
        'wheel_mountain_law' => isset($_POST['wheel_mountain_law']) ? 1 : 0,
        'wheel_fuel_efficiency' => $_POST['wheel_fuel_efficiency'],
        'wheel_ground_adhesion' => $_POST['wheel_ground_adhesion'],
        'wheel_rolling_noise' => $_POST['wheel_rolling_noise'],
        'wheel_noise_level' => $_POST['wheel_noise_level'],
        'wheel_price' => $_POST['wheel_price'],
        'wheel_picture_id' => $_POST['wheel_image_id'],
    ];

    $new_pneu = $databaseController->update_pneu($_POST['id'], $data);
    //get is error
    if (!is_array($new_pneu)) {
        array_push($notifications, [
            'success' => false,
            'message' => $new_pneu->get_error_message()
        ]);
    } else {
        array_push($notifications, [
            'success' => true,
            'message' => 'Pneu modifié avec succès'
        ]);
        $pneu = $new_pneu;

        //update product in woocommerce
        $args = array(
            'name' => $model,
            'description' => $_POST['description'],
            'short_description' => $_POST['short_description'],
            'image_id' => $_POST['wheel_image_id'],
            'price' => $_POST['wheel_price'],
        );

        $products = wc_get_products(array('include' => array($pneu[0]->wheel_woocommerce_product_id)));
        $products[0]->set_name($args['name']);
        $products[0]->set_description($args['description']);
        $products[0]->set_short_description($args['short_description']);
        $products[0]->set_regular_price($args['price']);
        $products[0]->set_image_id($args['image_id']);
        $products[0]->save();
        $products;
    }
} else {

    //get prduct in woocommerce

    $products = wc_get_products(array('include' => array($pneu[0]->wheel_woocommerce_product_id)));
}

?>

<div class="wrap">
    <?php foreach ($notifications as $notification) : ?>
        <div class="notice notice-<?php echo $notification['success'] ? 'success' : 'error'; ?> is-dismissible">
            <p><?php echo $notification['message']; ?></p>
        </div>
    <?php endforeach; ?>
    <h1>Modifier un pneu</h1>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $pneu[0]->id; ?>">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="wheel_guarantee">Garantie</label></th>
                    <td>
                        <select name="wheel_guarantee" id="wheel_guarantee">
                            <?php foreach ($pneusGuarantee as $guarantee) : ?>
                                <option value="<?php echo $guarantee; ?>" <?php echo $guarantee == $pneu[0]->wheel_guarantee ? 'selected' : ''; ?>><?php echo $guarantee; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wheel_model">Modèle</label></th>
                    <td><input type="text" name="wheel_model" id="wheel_model" value="<?php echo $pneu[0]->wheel_model; ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wheel_category">Catégorie</label></th>
                    <td><input type="text" name="wheel_category" id="wheel_category" value="<?php echo $pneu[0]->wheel_category; ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wheel_subcategory">Sous-catégorie</label></th>
                    <td><input type="text" name="wheel_subcategory" id="wheel_subcategory" value="<?php echo $pneu[0]->wheel_subcategory; ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wheel_dimension">Dimension</label></th>
                    <td><input type="text" name="wheel_dimension" id="wheel_dimension" value="<?php echo $pneu[0]->wheel_dimension; ?>"></td>
                </tr>
                <tr>
                    <th><label for="">Image du pneu</label></th>
                    <td>
                        <button class="browser button button-hero" id="upload_image_button">Charger une nouvelle image</button>
                        <input type="number" name="wheel_image_id" id="wheel_image_id" hidden value="<?= $pneu[0]->wheel_picture_id; ?>">
                        <img src="<?php echo $image ?>" alt="Image du pneu" style="max-width: 200px;" id="pictureView">

                    </td>


                </tr>
                <tr>
                    <th scope="row"><label for="wheel_profile">Profil</label></th>
                    <td><input type="text" name="wheel_profile" id="

                        wheel_profile" value="<?php echo $pneu[0]->wheel_profile; ?>"></td>
                </tr>

                <tr>
                    <th scope="row"><label for="wheel_vehicle_type">Type de véhicule</label></th>
                    <td>
                        <select name="wheel_vehicle_type" id="wheel_vehicle_type">
                            <option value="0" <?php echo $pneu[0]->wheel_vehicle_type == 0 ? 'selected' : ''; ?>>Tourisme</option>
                            <option value="1" <?php echo $pneu[0]->wheel_vehicle_type == 1 ? 'selected' : ''; ?>>4x4</option>
                            <option value="2" <?php echo $pneu[0]->wheel_vehicle_type == 2 ? 'selected' : ''; ?>>Utilitaire</option>
                        </select>
                    </td>


                </tr>

                <tr>
                    <th scope="row"><label for="wheel_season">Saison</label></th>
                    <td>
                        <select name="wheel_season" id="wheel_season">
                            <?php foreach ($pneusSaision as $season) : ?>
                                <option value="<?php echo $season; ?>" <?php echo $season == $pneu[0]->wheel_season ? 'selected' : ''; ?>><?php echo $season; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>

                </tr>

                <tr>
                    <th scope="row"><label for="wheel_width">Largeur</label></th>
                    <td><input type="number" name="wheel_width" id="wheel_width" value="<?php echo $pneu[0]->wheel_width; ?>"></td>

                </tr>

                <tr>
                    <th scope="row"><label for="wheel_height">Hauteur</label></th>
                    <td><input type="number" name="wheel_height" id="wheel_height" value="<?php echo $pneu[0]->wheel_height; ?>"></td>

                </tr>

                <tr>
                    <th scope="row"><label for="wheel_diameter">Diamètre</label></th>
                    <td><input type="number" name="wheel_diameter" id="wheel_diameter" value="<?php echo $pneu[0]->wheel_diameter; ?>"></td>


                </tr>


                <tr>
                    <th scope="row"><label for="wheel_load_index">Indice de charge</label></th>
                    <td><input type="number" name="wheel_load_index" id="wheel_load_index" value="<?php echo $pneu[0]->wheel_load_index; ?>"></td>

                </tr>

                <tr>
                    <th scope="row"><label for="wheel_speed">Indice de vitesse</label></th>
                    <td><input type="text" name="wheel_speed" id="wheel_speed" value="<?php echo $pneu[0]->wheel_speed; ?>"></td>

                </tr>

                <tr>
                    <th scope="row"><label for="wheel_xl">XL</label></th>
                    <td><input type="checkbox" name="wheel_xl" id="wheel_xl" value="1" <?php echo $pneu[0]->wheel_xl == 1 ? 'checked' : ''; ?>></td>

                </tr>

                <tr>
                    <th scope="row"><label for="wheel_runflat">Runflat</label></th>
                    <td><input type="checkbox" name="wheel_runflat" id="wheel_runflat" value="1" <?php echo $pneu[0]->wheel_runflat == 1 ? 'checked' : ''; ?>></td>

                </tr>

                <tr>
                    <th scope="row"><label for="wheel_use">Usage</label></th>
                    <td><input type="text" name="wheel_use" id="wheel_use" value="<?php echo $pneu[0]->wheel_use; ?>"></td>

                </tr>


                <tr>
                    <th scope="row"><label for="wheel_winter_mark">Voiture électrique</label></th>
                    <td><input type="checkbox" name="wheel_winter_mark" id="wheel_winter_mark" value="1" <?php echo $pneu[0]->wheel_winter_mark == 1 ? 'checked' : ''; ?>></td>


                </tr>

                <tr>
                    <th scope="row"><label for="wheel_mountain_law">Loi montagne</label></th>
                    <td><input type="checkbox" name="wheel_mountain_law" id="wheel_mountain_law" value="1" <?php echo $pneu[0]->wheel_mountain_law == 1 ? 'checked' : ''; ?>></td>

                </tr>

                <tr>
                    <th scope="row"><label for="wheel_fuel_efficiency">Efficacité énergétique</label></th>
                    <td>
                        <select name="wheel_fuel_efficiency" id="wheel_fuel_efficiency">
                            <?php foreach ($variableController::pneusfuelEfficiency as $fuelEfficiency) : ?>
                                <option value="<?php echo $fuelEfficiency; ?>" <?php echo $fuelEfficiency == $pneu[0]->wheel_fuel_efficiency ? 'selected' : ''; ?>><?php echo $fuelEfficiency; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>


                </tr>

                <tr>
                    <th scope="row"><label for="wheel_ground_adhesion">Adhérence sur sol mouillé</label></th>
                    <td>
                        <select name="wheel_ground_adhesion" id="wheel_ground_adhesion">
                            <?php foreach ($pneusGroundAdhesion as $groundAdhesion) : ?>
                                <option value="<?php echo $groundAdhesion; ?>" <?php echo $groundAdhesion == $pneu[0]->wheel_ground_adhesion ? 'selected' : ''; ?>><?php echo $groundAdhesion; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>

                </tr>

                <tr>
                    <th scope="row"><label for="wheel_rolling_noise">Bruit de roulement</label></th>
                    <td><input type="text" name="wheel_rolling_noise" id="wheel_rolling_noise" value="<?php echo $pneu[0]->wheel_rolling_noise; ?>"></td>

                </tr>

                <tr>
                    <th scope="row"><label for="wheel_noise_level">Niveau sonore</label></th>
                    <td>
                        <select name="wheel_noise_level" id="wheel_noise_level">
                            <?php foreach ($pneusNoiseLevel as $noiseLevel) : ?>
                                <option value="<?php echo $noiseLevel; ?>" <?php echo $noiseLevel == $pneu[0]->wheel_noise_level ? 'selected' : ''; ?>><?php echo $noiseLevel; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>


                </tr>

                <tr>
                    <th scope="row"><label for="wheel_price">Prix</label></th>
                    <td>
                        <input type="text" name="wheel_price" id="wheel_price" value="<?php echo  $pneu[0]->wheel_price; ?>">
                    </td>


                </tr>

            </tbody>

        </table>
        <table class="text_table_wheel">
            <tbody>
                <tr>
                    <th><label for="description">description</label>
                    </th>
                    <td>
                        <?php echo $variableController->custom_plugin_text_editor($products[0]->get_description(), "description", "description") ?>

                    </td>
                </tr>
                <tr>
                    <th><label for="short_description">description courte</label> </th>
                    <td>
                        <?php echo $variableController->custom_plugin_text_editor($products[0]->get_short_description(), "short_description", "short_description") ?>

                    </td>
                </tr>
            </tbody>
        </table>

        <input type="submit" value="Modifier le pneu" class="button button-primary">

    </form>
</div>



<!-- css -->
<style>
    .text_table_wheel th {
        width: 10%;
    }
</style>

<!-- JS -->
<script>
    jQuery(document).ready(function($) {
        const URLBASE = jon_dev_api_url.url;

        //get is number in input text
        document.getElementById('wheel_price').addEventListener('input', function(e) {
            e.preventDefault();
            let value = e.target.value;
            if (isNaN(value)) {
                e.target.value = '';
            }
        });


        var custom_uploader;
        $('#upload_image_button').click(function(e) {
            e.preventDefault();
            if (custom_uploader) {
                custom_uploader.open();
                return;
            }
            custom_uploader = wp.media.frames.file_frame = wp.media({
                multiple: false,
                library: {
                    type: 'image'
                },
                button: {
                    text: 'Select Image'
                },
                title: 'Select an image or enter an image URL.',
            });
            custom_uploader.on('select', function() {
                console.log(custom_uploader.state().get('selection').toJSON());
                attachment = custom_uploader.state().get('selection').first().toJSON();
                console.log(attachment);
                $('#pictureView').attr('src', attachment.url);
                $('#wheel_image_id').val(attachment.id);
            });
            custom_uploader.open();
        });

    });
</script>