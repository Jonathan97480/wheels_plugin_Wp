<?php

use Berou\JonDevWheelCheck\Controller\DatabaseController;
use Berou\JonDevWheelCheck\Controller\VariableController;

$databaseController = new DatabaseController();
$variableController = new VariableController();
$maintCategorys = $variableController::mainCategorys;
$subCategorys = $variableController::subCategorys;
$pneusSpeed = $variableController::pneusSpeed;
$pneusfuelEfficiency = $variableController::pneusfuelEfficiency;
$pneusGroundAdhesion = $variableController::pneusGroundAdhesion;
$pneusNoiseLevel = $variableController::pneusNoiseLevel;
$pneusSaision = $variableController::pneusSaision;
$pneusGuarantee = $variableController::pneusGuarantee;
$result = $databaseController->get_all_pneus(); //get all pneus
$listBrands = $databaseController->get_all_brands(); //get all brands

//pagination data
$listPneus = $result['data'];
$totalPage = $result['pages'];
$curentPage = $result['page'];

//Variable Notificaion

$notifications = [];




if (isset($_POST['modelName']) && isset($_POST['VehicleType'])) {



    $isFieldRequiredCompleted = $variableController->check_fields_required_completed($_POST);
    if (!$isFieldRequiredCompleted) {
        array_push($notifications, [
            'success' => false,
            'message' => 'Veuillez remplir les champs obligatoires marqués par *'
        ]);
    } else {

        //verifier si le produit existe deja
        $productExist = $databaseController->get_pneu_by_model($_POST['modelName']);
        //verifi si la tableau contient des elements
        if (count($productExist) > 0) {
            array_push($notifications, [
                'success' => false,
                'message' => 'Ce modèle de pneu existe déjà'
            ]);
        } else {


            $idProductWc = $variableController->generate_product_for_woocomerce($_POST, new WC_Product());
            if (!is_int($idProductWc)) {
                array_push($notifications, [
                    'success' => false,
                    'message' => 'Erreur lors de la création du produit'
                ]);
            } else {

                $data = [
                    'wheel_guarantee' => $_POST['wheelGuarantee'],
                    'wheel_model' => $_POST['modelName'],
                    'wheel_category' => $_POST['VehicleType'],
                    'wheel_subcategory' => $_POST['subCategory'],
                    'wheel_dimension' => $_POST['width'] . '/' . $_POST['height'] . 'R' . $_POST['diameter'] . ' ' . $_POST['loadIndex'] . $_POST['speed'],
                    'wheel_profile' => $_POST['profile'],
                    'wheel_vehicle_type' => $_POST['vehicleType'],
                    'wheel_season' => $_POST['saision'],
                    'wheel_width' => $_POST['width'],
                    'wheel_height' => $_POST['height'],
                    'wheel_diameter' => $_POST['diameter'],
                    'wheel_load_index' => $_POST['loadIndex'],
                    'wheel_speed' => $_POST['speed'],
                    'wheel_xl' => $_POST['xl'] == null ? 0 : 1,
                    'wheel_runflat' => $_POST['runflat'] == null ? 0 : 1,
                    'wheel_use' => $_POST['use'],
                    'wheel_winter_mark' => $_POST['winterMark'] == null ? 0 : 1,
                    'wheel_mountain_law' => $_POST['mountainLaw'] == null ? 0 : 1,
                    'wheel_fuel_efficiency' => $_POST['fuelEfficiency'],
                    'wheel_ground_adhesion' => $_POST['groundAdhesion'],
                    'wheel_rolling_noise' => $_POST['rollingNoise'],
                    'wheel_noise_level' => $_POST['noiseLevel'],
                    'wheel_woocommerce_product_id' => $idProductWc,
                    'wheel_brand_id' => $_POST['brand'],
                    'wheel_price' => $_POST['price'],
                    'wheel_picture_id' => $_POST['upload_image_id'],
                ];
                $productId =  $databaseController->add_pneu($data);
                if (!is_int($productId)) {
                    array_push($notifications, [
                        'success' => false,
                        'message' => 'Erreur lors de l\'ajout du pneu'
                    ]);
                } else {
                    array_push($notifications, [
                        'success' => true,
                        'message' => 'Pneu ajouté avec succès'
                    ]);
                }
            }
        }
    }
}



//admin vue
?>
<div class="wrap">
    <?php foreach ($notifications as $notification) : ?>
        <div class="notice notice-<?php echo $notification['success'] ? 'success' : 'error'; ?> is-dismissible">
            <p><?php echo $notification['message']; ?></p>
        </div>
    <?php endforeach; ?>
    <h1>Jon Dev Wheel Check</h1>
    <section>
        <h2>Ajouter un modèle de pneu</h2>
        <form method="post" action="">
            <table class="wp-list-table jon_dev_table_admin">
                <tbody>
                    <tr>

                        <th>
                            <label for="modelName">Nom du modèle *</label>
                        </th>
                        <td>
                            <input type="text" name="modelName" id="modelName" required>
                        </td>

                        <th>
                            <label for="wheelGuarantee">Garantie</label>
                        </th>
                        <td>
                            <select name="wheelGuarantee" id="wheelGuarantee">
                                <?php foreach ($pneusGuarantee as $pneusGuarantee) : ?>
                                    <?php if ($pneusGuarantee == 'Aucune garantie') : ?>
                                        <option value="<?= $pneusGuarantee ?>" selected><?= $pneusGuarantee ?></option>
                                    <?php else : ?>
                                        <option value="<?= $pneusGuarantee ?>"><?= $pneusGuarantee ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <th>
                            <label for="saision">Saison</label>
                        </th>
                        <td>
                            <select name="saision" id="saision">
                                <?php foreach ($pneusSaision as $pneusSaision) : ?>
                                    <?php if ($pneusSaision == 'Hiver') : ?>
                                        <option value="<?= $pneusSaision ?>" selected><?= $pneusSaision ?></option>
                                    <?php else : ?>
                                        <option value="<?= $pneusSaision ?>"><?= $pneusSaision ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="VehicleType">Type de véhicule</label>
                        </th>
                        <td>
                            <select name="VehicleType" id="VehicleType">
                                <?php foreach ($maintCategorys as $maintCategory) : ?>
                                    <?php if ($maintCategory == 'Tourisme') : ?>
                                        <option value="<?= $maintCategory ?>" selected><?= $maintCategory ?></option>
                                    <?php else : ?>
                                        <option value="<?= $maintCategory ?>"><?= $maintCategory ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <th>
                            <label for="subCategory">Sous catégorie</label>
                        </th>
                        <td>
                            <select name="subCategory" id="subCategory">
                                <?php foreach ($subCategorys as $subCategory) : ?>
                                    <?php if ($subCategory == 'Pneus larges') : ?>
                                        <option value="<?= $subCategory ?>" selected><?= $subCategory ?></option>
                                    <?php else : ?>
                                        <option value="<?= $subCategory ?>"><?= $subCategory ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <th>
                            <label for="profile">Pneus profile</label>
                        </th>
                        <td>
                            <input type="text" name="profile" id="profile">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="vehicleType">Type de véhicule</label>
                        </th>
                        <td>
                            <select name="vehicleType" id="vehicleType">
                                <?php foreach ($maintCategorys as $maintCategorys) : ?>
                                    <?php if ($maintCategorys == 'Tourisme') : ?>
                                        <option value="<?= $maintCategorys ?>" selected><?= $maintCategorys ?></option>
                                    <?php else : ?>
                                        <option value="<?= $maintCategorys ?>"><?= $maintCategorys ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <th>
                            <label for="picture">Image du pneu *</label>
                        </th>
                        <td>
                            <input id="upload_image" name="upload_image" type="text" value="<?php if (isset($options['upload-image'])) echo esc_attr($options['upload-image']); ?>">
                            <input type="number" id="upload_image_id" hidden="true" name="upload_image_id" required>
                            <button class="browser button button-hero" id="upload_image_button">Choisir une image</button>
                        </td>
                        <th>
                            <label for="price">Prix *</label>
                        </th>
                        <td>
                            <input type="number" name="price" id="price" required>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="width">Largeur *</label>
                        </th>
                        <td>
                            <input type="number" name="width" id="width" required>
                        </td>
                        <th>
                            <label for="height">Hauteur *</label>
                        </th>
                        <td>
                            <input type="number" name="height" id="height" required>
                        </td>
                        <th>
                            <label for="diameter">Diamètre *</label>
                        </th>
                        <td>
                            <input type="number" name="diameter" id="diameter" required>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="loadIndex">Indice de charge *</label>
                        </th>
                        <td>
                            <input type="number" name="loadIndex" id="loadIndex" required>
                        </td>
                        <th>
                            <label for="speed">Vitesse</label>
                        </th>
                        <td>
                            <select name="speed" id="speed">
                                <?php foreach ($pneusSpeed as $pneusSpeed) : ?>
                                    <?php if ($pneusSpeed == 'Q') : ?>
                                        <option value="<?= $pneusSpeed ?>" selected><?= $pneusSpeed ?></option>
                                    <?php else : ?>
                                        <option value="<?= $pneusSpeed ?>"><?= $pneusSpeed ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <th>
                            <label for="xl">XL</label>
                        </th>
                        <td>
                            <input type="checkbox" name="xl" id="xl">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="runflat">Runflat</label>
                        </th>
                        <td>
                            <input type="checkbox" name="runflat" id="runflat">
                        </td>
                        <th>
                            <label for="use">Usagés</label>
                        </th>
                        <td>
                            <input type="text" name="use" id="use">
                        </td>
                        <th>
                            <label for="winterMark">Marquage hiver</label>
                        </th>
                        <td>
                            <input type="checkbox" name="winterMark" id="winterMark">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="mountainLaw">Loi Montagne</label>
                        </th>
                        <td>
                            <input type="checkbox" name="mountainLaw" id="mountainLaw">
                        </td>
                        <th>
                            <label for="fuelEfficiency">Efficacite carburant</label>
                        </th>
                        <td>
                            <select name="fuelEfficiency" id="fuelEfficiency">
                                <?php foreach ($pneusfuelEfficiency as $pneusfuelEfficiency) : ?>
                                    <?php if ($pneusfuelEfficiency == 'A') : ?>
                                        <option value="<?= $pneusfuelEfficiency ?>" selected><?= $pneusfuelEfficiency ?></option>
                                    <?php else : ?>
                                        <option value="<?= $pneusfuelEfficiency ?>"><?= $pneusfuelEfficiency ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <th>
                            <label for="groundAdhesion">Adhérence sur sol</label>
                        </th>
                        <td>
                            <select name="groundAdhesion" id="groundAdhesion">
                                <?php foreach ($pneusGroundAdhesion as $pneusGroundAdhesion) : ?>
                                    <?php if ($pneusGroundAdhesion == 'A') : ?>
                                        <option value="<?= $pneusGroundAdhesion ?>" selected><?= $pneusGroundAdhesion ?></option>
                                    <?php else : ?>
                                        <option value="<?= $pneusGroundAdhesion ?>"><?= $pneusGroundAdhesion ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="rollingNoise">Bruit de roulement</label>
                        </th>
                        <td>
                            <input type="number" name="rollingNoise" id="rollingNoise">
                        </td>
                        <th>
                            <label for="noiseLevel">Niveau de bruit</label>
                        </th>
                        <td>
                            <select name="noiseLevel" id="noiseLevel">
                                <?php foreach ($pneusNoiseLevel as $pneusNoiseLevel) : ?>
                                    <?php if ($pneusNoiseLevel == 'A') : ?>
                                        <option value="<?= $pneusNoiseLevel ?>" selected><?= $pneusNoiseLevel ?></option>
                                    <?php else : ?>
                                        <option value="<?= $pneusNoiseLevel ?>"><?= $pneusNoiseLevel ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <th>
                            <label for="brand">Marque *</label>
                        </th>
                        <td>
                            <select name="brand" id="brand" required>
                                <option value="">Choisir une marque</option>
                                <?php foreach ($listBrands as $brand) : ?>
                                    <option value="<?= $brand->id ?>"><?= $brand->brand_name ?></option>
                                <?php endforeach; ?>
                            </select>
                    </tr>
                    <tr>
                        <th>
                            <label for="description">Description du pneu *</label>
                        </th>
                        <td>
                            <textarea name="description" id="description" cols="30" rows="10" required></textarea>
                        </td>
                        <th>
                            <label for="shortDescription">Description courte *</label>
                        </th>
                        <td>
                            <textarea name="shortDescription" id="shortDescription" cols="30" rows="10" required></textarea>
                        </td>
                        <th>
                            <label for="quantity">Quantité en stock *</label>
                        </th>
                        <td>
                            <input type="number" name="quantity" id="quantity" required>
                        </td>

                    </tr>

                    <input type="submit" value="Ajouter" class="button action">
                </tbody>

            </table>


        </form>
    </section>
    <section>
        <h2>Liste des modèles de pneus</h2>
        <!-- chearch field -->
        <form method="post" action="" id="wheels_chearch_form">
            <input type="text" name="search" id="search" placeholder="Rechercher un modèle de pneu">
            <input type="submit" value="Rechercher" class="button action">
        </form>
        <br>
        <!-- table list -->
        <table class="wp-list-table widefat plugins jon_dev_table_admin_list">
            <thead>
                <tr>
                    <th>Action</th>
                    <th class="manage-column column-model column-primary" id="model" scope="col">Modèle</th>
                    <th class="manage-column column-guarantee" id="guarantee" scope="col">Garantie</th>
                    <th class="manage-column column-season" id="season" scope="col">Saison</th>
                    <th class="manage-column column-vehicle_type" id="vehicle_type" scope="col">Type de véhicule</th>
                    <th class="manage-column column-subcategory" id="subcategory" scope="col">Sous catégorie</th>
                    <th class="manage-column column-profile" id="profile" scope="col">Profile</th>
                    <th class="manage-column column-width" id="width" scope="col">Largeur</th>
                    <th class="manage-column column-height" id="height" scope="col">Hauteur</th>
                    <th class="manage-column column-diameter" id="diameter" scope="col">Diamètre</th>
                    <th class="manage-column column-load_index" id="load_index" scope="col">Indice de charge</th>
                    <th class="manage-column column-speed" id="speed" scope="col">Vitesse</th>
                    <th class="manage-column column-xl" id="xl" scope="col">XL</th>
                    <th class="manage-column column-runflat" id="runflat" scope="col">Runflat</th>
                    <th class="manage-column column-use" id="use" scope="col">Usagés</th>
                    <th class="manage-column column-winter_mark" id="winter_mark" scope="col">Marquage hiver</th>
                    <th class="manage-column column-mountain_law" id="mountain_law" scope="col">Loi Montagne</th>
                    <th class="manage-column column-fuel_efficiency" id="fuel_efficiency" scope="col">Efficacite carburant</th>
                    <th class="manage-column column-ground_adhesion" id="ground_adhesion" scope="col">Adhérence sur sol</th>
                    <th class="manage-column column-rolling_noise" id="rolling_noise" scope="col">Bruit de roulement</th>
                    <th class="manage-column column-noise_level" id="noise_level" scope="col">Niveau de bruit</th>
                </tr>
            </thead>
            <tbody id="tbody_table_wheels_list">


        </table>
        <!-- pagination -->
        <div class="tablenav bottom">
            <div class="tablenav-pages">
                <span class="displaying-num">1 élément</span>
                <span class="pagination">
                    <a class="first-page disabled" href="" id="first-page">
                        <span class="screen-reader-text">First page</span>
                        <span aria-hidden="true">««</span>
                    </a>
                    <a class="prev-page disabled" href="" id="prev-page">
                        <span class="screen-reader-text">Previous page</span>
                        <span aria-hidden="true">‹</span>
                    </a>

                    <span class="paging-input">
                        <label for="current-page-selector" class="screen-reader-text">Current Page</label>
                        <input class="current-page" id="current-page-selector" type="text" name="paged" value="1" size="1" aria-describedby="table-paging">
                        <span class="tablenav-paging-text"> of <span class="total-pages" id="display_total_page_enum">1</span></span>
                    </span>
                    <a class="next-page" href="" id="next-page">
                        <span class="screen-reader-text">Next page</span>
                        <span aria-hidden="true">›</span>
                    </a>
                    <a class="last-page" href="" id="last-page">
                        <span class="screen-reader-text">Last page</span>
                        <span aria-hidden="true">»</span>
                    </a>
                </span>
            </div>
        </div>

    </section>
</div>

<!-- css -->
<style>
    .jon_dev_table_admin {
        width: 100%;
        max-width: 1920px;
        margin: auto;

    }

    .jon_dev_table_admin th {
        text-align: left;
        width: 10%;


    }

    .jon_dev_table_admin td {
        text-align: left;
        padding-right: 30px;


    }

    .jon_dev_table_admin tr {
        line-height: 40px;
    }

    .jon_dev_table_admin select {
        width: 100%;
    }





    .jon_dev_table_admin input[type="text"],
    .jon_dev_table_admin input[type="number"],
    .jon_dev_table_admin select {
        width: 100%;
    }

    /**submit type */
    .jon_dev_table_admin input[type="submit"] {
        margin-top: 20px;

    }

    .jon_dev_table_admin_list th {
        text-align: center;
    }

    .brand_Delete_Btn {
        color: red;
        cursor: pointer;
    }

    .brand_Edit_Btn {
        color: blue;
        cursor: pointer;
    }
</style>

<!-- js -->
<script>
    jQuery(document).ready(function($) {
        const URLBASE = jon_dev_api_url.url;



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
                $('#upload_image').val(attachment.url);
                $('#upload_image_id').val(attachment.id);
            });
            custom_uploader.open();
        });





        //pagination
        const nextPage = document.getElementById('next-page');
        const lastPage = document.getElementById('last-page');

        const firstPage = document.getElementById('first-page');
        const prevPage = document.getElementById('prev-page');


        const pageSlector = document.getElementById("current-page-selector");
        const displayCurrentPage = document.querySelector('.displaying-num');
        const displayTotalPage = document.getElementById('display_total_page_enum');
        const URLFETCH = URLBASE + 'jon_dev_wheel_check/v1/get_wheels';
        let currentPage = <?= $curentPage ?>;
        let totalPage = <?= $totalPage ?>;
        displayCurrentPage.textContent = currentPage + ' éléments';
        displayTotalPage.textContent = totalPage;

        nextPage.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage < totalPage) {
                currentPage++;
                pageSlector.value = currentPage;
                fetch(URLFETCH + '?page=' + currentPage + '&limit=10')
                    .then(response => response.json())
                    .then(data => {
                        data = JSON.parse(data);
                        console.log(data);
                        if (data.success) {
                            generate_table(data.data);
                        } else {
                            console.error(data.message);
                        }

                    })
                    .catch(error => {
                        console.error('Error:', data.message);
                    });


            }

        });

        lastPage.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                pageSlector.value = currentPage;
                fetch(URLFETCH + '?page=' + currentPage + '&limit=10')
                    .then(response => response.json())
                    .then(data => {
                        data = JSON.parse(data);
                        console.log(data);
                        if (data.success) {
                            generate_table(data.data);
                        } else {
                            console.error(data.message);
                        }

                    })
                    .catch(error => {
                        console.error('Error:', data.message);
                    });


            };

        });

        pageSlector.addEventListener('change', function(e) {
            e.preventDefault();
            if (e.target.value > totalPage) {
                if (totalPage > 0) {
                    e.target.value = totalPage;
                } else {
                    e.target.value = 1;
                }
                return;
            }
            let page = e.target.value;
            fetch(URLFETCH + '?page=' + page + '&limit=10')
                .then(response => response.json())
                .then(data => {
                    data = JSON.parse(data);
                    console.log(data);
                    if (data.success) {
                        generate_table(data.data);
                    } else {
                        console.error(data.message);
                    }

                })
                .catch(error => {
                    console.error('Error:', data.message);
                });

        });


        firstPage.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage = 1;
                pageSlector.value = currentPage;
                fetch(URLFETCH + '?page=' + currentPage + '&limit=10')
                    .then(response => response.json())
                    .then(data => {
                        data = JSON.parse(data);
                        console.log(data);
                        if (data.success) {
                            generate_table(data.data);
                        } else {
                            console.error(data.message);
                        }

                    })
                    .catch(error => {
                        console.error('Error:', data.message);
                    });


            };

        });

        prevPage.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                pageSlector.value = currentPage;
                fetch(URLFETCH + '?page=' + currentPage + '&limit=10')
                    .then(response => response.json())
                    .then(data => {
                        data = JSON.parse(data);
                        console.log(data);
                        if (data.success) {
                            generate_table(data.data);
                        } else {
                            console.error(data.message);
                        }

                    })
                    .catch(error => {
                        console.error('Error:', data.message);
                    });


            };

        });


        //call api to get all wheels end start page
        fetch(URLFETCH + '?page=' + currentPage + '&limit=10')
            .then(response => response.json())
            .then(data => {
                data = JSON.parse(data);
                console.log(data.data.data);
                if (data.success) {

                    generate_table(data.data);
                } else {
                    console.error(data.message);
                }

            })
            .catch(error => {
                console.error('Error:', data.message);
            });




        //regenerate table 
        function generate_table($result) {
            const tbody = document.getElementById('tbody_table_wheels_list');
            tbody.innerHTML = '';
            $result.data.forEach(pneus => {
                tbody.innerHTML += `
                    <tr>
                        <td>
                            <a class="brand_Delete_Btn" href="<?= admin_url('admin.php?page=update_wheel&id=') ?>${pneus.id}">Edit</a>
                            <a class="brand_Edit_Btn" href="" id="${pneus.id}" class="wheel_Delete_Btn">Delete</a>
                        </td>
                        <td>${pneus.wheel_model}</td>
                        <td>${pneus.wheel_guarantee}</td>
                        <td>${pneus.wheel_season}</td>
                        <td>${pneus.wheel_vehicle_type}</td>
                        <td>${pneus.wheel_subcategory}</td>
                        <td>${pneus.wheel_profile}</td>
                        <td>${pneus.wheel_width}</td>
                        <td>${pneus.wheel_height}</td>
                        <td>${pneus.wheel_diameter}</td>
                        <td>${pneus.wheel_load_index}</td>
                        <td>${pneus.wheel_speed}</td>
                        <td>${pneus.wheel_xl}</td>
                        <td>${pneus.wheel_runflat}</td>
                        <td>${pneus.wheel_use}</td>
                        <td>${pneus.wheel_winter_mark}</td>
                        <td>${pneus.wheel_mountain_law}</td>
                        <td>${pneus.wheel_fuel_efficiency}</td>
                        <td>${pneus.wheel_ground_adhesion}</td>
                        <td>${pneus.wheel_rolling_noise}</td>
                        <td>${pneus.wheel_noise_level}</td>
                    </tr>
                    `;
            });

            addEventRemoveWheel();



        }
        //delete pneu
        function addEventRemoveWheel() {
            var deleteBtns = document.querySelectorAll('.wheel_Delete_Btn');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    let id = e.target.id;
                    fetch(URLBASE + 'jon_dev_wheel_check/v1/remove_wheel?id=' + id)
                        .then(response => response.json())
                        .then(data => {
                            data = JSON.parse(data);
                            console.log(data);

                            if (data.success) {
                                //refresh page
                                location.reload();
                            } else {
                                console.error(data.message);
                            }

                        })
                        .catch(error => {
                            console.error('Error:', data.message);
                        });
                });
            });
        }

        //search

        const searchForm = document.getElementById('wheels_chearch_form');
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            let search = document.getElementById('search').value;
            fetch(URLBASE + 'jon_dev_wheel_check/v1/search_wheel?search=' + search)
                .then(response => response.json())
                .then(data => {
                    data = JSON.parse(data);
                    console.log(data);
                    if (data.success) {
                        generate_table(data);
                    } else {
                        console.error(data.message);
                    }

                })
                .catch(error => {
                    console.error('Error:', data.message);
                });
        });

    });
</script>