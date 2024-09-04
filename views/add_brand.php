<?php

use Berou\JonDevWheelCheck\Controller\DatabaseController;
use Berou\JonDevWheelCheck\Controller\VariableController;

$databaseController = new DatabaseController();
$variableController = new VariableController();
$notifications = [];

if (isset($_POST['name']) && isset($_POST['picture_id'])) {

    $name = $_POST['name'];
    $picture = $_POST['picture_id'];

    $result = $databaseController->add_brand($name, $picture);
    if (!is_int($result)) {
        $notifications[] = [
            'success' => false,
            'message' => 'Error: ' . $result->get_error_message()
        ];
    } else {
        $notifications[] = [
            'success' => true,
            'message' => 'La marque a été ajoutée avec succès'
        ];
    }
}
?>

<!-- HTML -->
<div class="wrap" id="main">
    <?php foreach ($notifications as $notification) : ?>
        <div class="notice notice-<?php echo $notification['success'] ? 'success' : 'error'; ?> is-dismissible">
            <p><?php echo $notification['message']; ?></p>
        </div>
    <?php endforeach; ?>
    <h2>Ajouter une Marque</h2>
    <form method="post" action="">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="name">Name</label>
                    </th>
                    <td>
                        <input type="text" name="name" id="name" class="regular-text" required>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="picture">Picture</label>
                    </th>
                    <td>
                        <img src="" alt="" id="upload_image" style="width: 100px; height:50px;object-fit: scale-down;" hidden>
                        <input type="number" name="picture_id" id="upload_image_id" class="regular-text" required hidden>
                        <input type="button" id="upload_image_button" class="button" value="Upload Image">
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Add Brand">
    </form>
</div>


<!-- HTML LIST BRAND TABLE -->

<div class="wrap">
    <h2>Brand List</h2>
    <table class="wp-list-table widefat fixed striped posts">
        <thead>
            <tr>
                <th scope="col" id="name" class="manage-column column-name column-primary">Action</th>
                <th scope="col" id="name" class="manage-column column-name column-primary">Name</th>
                <th scope="col" id="picture" class="manage-column column-name column-primary">Picture</th>
            </tr>
        </thead>
        <tbody id="the-list">
            <?php
            $brands = $databaseController->get_all_brands();
            foreach ($brands as $brand) {
                $imgUrl =  wp_get_attachment_image_url($brand->brand_logo_id);
            ?>
                <tr id="brand_tr_<?= $brand->id ?>">
                    <td class="name column-name has-row-actions column-primary">
                        <a href="#" class="brand_Delete_Btn" id="<?php echo $brand->id; ?>">Delete</a>
                        <a href="#" class="brand_Edit_Btn" id="<?php echo $brand->id; ?>">Edit</a>
                    </td>

                    <td class="name_brand column-name has-row-actions column-primary" data-colname="Name"><?php echo $brand->brand_name; ?></td>
                    <td class="picture column-picture" data-colname="Picture">
                        <img src="<?php echo  $imgUrl; ?>" alt="<?php echo $brand->name; ?>" data-id="<?= $brand->brand_logo_id  ?>" style="width: 100px; height:50px;object-fit: scale-down; ">
                    </td>

                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>



<!-- js -->
<script>
    jQuery(document).ready(function($) {
        const URLBASE = jon_dev_api_url.url;
        Media_upload('#upload_image_button', '#upload_image', '#upload_image_id');

        //delete brand

        var deleteBtns = document.querySelectorAll('.brand_Delete_Btn');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                let id = e.target.id;
                fetch(URLBASE + 'jon_dev_wheel_check/v1/remove_brand?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        //refresh page
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });



        //Save table row
        let table_body = document.querySelector('#the-list').innerHTML;
        addListenerEditBtn();



        /**
         * Function to check if table body has changed
         *
         * @return boolean
         */
        function checkTableBodyChanged() {
            if (document.querySelector('#the-list').innerHTML != table_body) {
                //clear event listener
                document.querySelectorAll('.brand_Edit_Btn').forEach(btn => {
                    btn.removeEventListener('click', function() {});
                });

                document.querySelector('#the-list').innerHTML = table_body;
                addListenerEditBtn();
                return true;
            }

            return false;
        }

        /**
         * Function to add event listener for edit button
         *
         * @return void
         */
        function addListenerEditBtn() {
            const allBtnEdit = document.querySelectorAll('.brand_Edit_Btn');
            allBtnEdit.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    if (checkTableBodyChanged()) {
                        return;
                    }

                    let id = e.target.id;
                    let tr = document.getElementById('brand_tr_' + id);
                    let name = tr.querySelector('.name_brand').innerText;
                    let picture_id = tr.querySelector('.picture img').getAttribute('data-id');
                    let picture = tr.querySelector('.picture img').getAttribute('src');
                    tr.innerHTML = `
               <td class="name column-name has-row-actions column-primary" data-colname="Name">
                   <a href="#" class="brand_Delete_Btn" id="${id}">Delete</a>
                   <a href="#" class="brand_Edit_Btn" id="${id}">Edit</a>
               </td>
               <td class="name column-name has-row-actions column-primary" data-colname="Name">
                   <input type="text" name="name" id="name_brand" class="" value="${name}" required>
               </td>
               <td class="picture column-picture" data-colname="Picture">
                  <img src="${picture}" alt="${name}" style="width: 100px; height:50px;object-fit: scale-down;" id="upload_image_edit" >
                 <br>
                   <input type="number" name="picture_id" id="upload_image_id_edit" class="regular-text" value="${picture_id}" required hidden>
                 <br>

                   <input type="button" id="upload_image_button_edit" class="button" value="Upload Image">
               </td>
               
               <td>
                   <button class="btn_save_edit" id="${id}">Save</button>
               </td>
               `;

                    //add event listener for upload image
                    Media_upload('#upload_image_button_edit', '#upload_image_edit', '#upload_image_id_edit');

                    //add event listener for save edit
                    document.querySelector('.btn_save_edit').addEventListener('click', function(e) {
                        e.preventDefault();
                        let id = e.target.id;
                        let name = document.querySelector('#name_brand').value;
                        let picture_id = document.querySelector('#upload_image_id_edit').value;

                        fetch(URLBASE + 'jon_dev_wheel_check/v1/update_brand', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({
                                    id: id,
                                    name: name,
                                    picture_id: picture_id
                                }),
                            })
                            .then(response => response.json())
                            .then(data => {

                                data = JSON.parse(data);
                                checkTableBodyChanged();
                                if (data.success) {
                                    //add notification
                                    AddNotification(data.message, true);
                                } else {
                                    alert(data.message);
                                    //add notification
                                    AddNotification(data.message, false);
                                }
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                            });
                    });
                });
            });
        }

        /**
         * Function to add notification
         *
         * @param {string} message
         * @param {boolean} success
         * @return void
         */
        function AddNotification(message, success = true) {
            let notification = document.createElement('div');
            notification.classList.add('notice');
            notification.classList.add('notice-' + (success ? 'success' : 'error'));
            notification.classList.add('is-dismissible');
            notification.innerHTML = `<p>${message}</p>`;
            document.querySelector('#main').prepend(notification);
        }

        /**
         *  Function to upload and select image to server
         * @param {string} idBtn
         * @param {string} idImg
         * @param {string} idInput
         * @returns {void}
         */
        function Media_upload(idBtn, idImg, idInput) {

            //upload image
            var custom_uploader;
            $(idBtn).click(function(e) {
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

                    attachment = custom_uploader.state().get('selection').first().toJSON();
                    console.log(attachment);
                    $(idImg).attr('src', attachment.url);
                    $(idImg).show();
                    $(idInput).val(attachment.id);
                });
                custom_uploader.open();
            });
        }


    });
</script>


<!-- css -->

<style>
    .brand_Delete_Btn {
        color: red;
        cursor: pointer;
    }

    .brand_Edit_Btn {
        color: blue;
        cursor: pointer;
    }

    .btn_save_edit {
        background-color: green;
        color: white;
        cursor: pointer;
    }

    .column-picture {
        width: 200px;
        height: 100px;
    }
</style>