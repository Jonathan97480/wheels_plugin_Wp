jQuery(document).ready(function ($) {
    const URLBASE = jon_dev_api_url.url;
    const url_plugin = jon_dev_api_url.plugin_url;
    const admin_url = jon_dev_api_url.admin_url;


    //get is number in input text
    document.getElementById('price').addEventListener('input', function (e) {
        e.preventDefault();
        let value = e.target.value;
        if (isNaN(value)) {
            e.target.value = '';
        }
    });


    //upload image

    var custom_uploader;
    $('#upload_image_button').click(function (e) {
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
        custom_uploader.on('select', function () {
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

    let currentPage = document.getElementById('curentP').value;
    let totalPage = document.getElementById('totalP').value;

    displayCurrentPage.textContent = currentPage + ' éléments';
    displayTotalPage.textContent = totalPage;

    nextPage.addEventListener('click', function (e) {
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

    lastPage.addEventListener('click', function (e) {
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

    pageSlector.addEventListener('change', function (e) {
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


    firstPage.addEventListener('click', function (e) {
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

    prevPage.addEventListener('click', function (e) {
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
                        <a class="brand_Delete_Btn" href="${admin_url + '&id=' + pneus.id}">Edit</a>
                        <a class="brand_Edit_Btn" href="" id="${pneus.id}" class="wheel_Delete_Btn">Delete</a>
                    </td>
                    <td>${pneus.wheel_model}</td>
                    <td>${pneus.wheel_guarantee}</td>
                    <td>${pneus.wheel_season}</td>
                    <td>${pneus.wheel_category}</td>
                    <td>${pneus.wheel_subcategory}</td>
                    <td>${pneus.wheel_profile}</td>
                    <td>${pneus.wheel_width}</td>
                    <td>${pneus.wheel_height}</td>
                    <td>${pneus.wheel_diameter}</td>
                    <td>${pneus.wheel_load_index}</td>
                    <td>${pneus.wheel_speed}</td>
                    <td>${getCheckAndUncheckIcon(pneus.wheel_xl)}</td>
                    <td>${getCheckAndUncheckIcon(pneus.wheel_runflat)}</td>
                    <td>${pneus.wheel_use}</td>
                    <td>${getCheckAndUncheckIcon(pneus.wheel_winter_mark)}</td>
                    <td>${getCheckAndUncheckIcon(pneus.wheel_mountain_law)}</td>
                    <td>${pneus.wheel_fuel_efficiency}</td>
                    <td>${pneus.wheel_ground_adhesion}</td>
                    <td>${pneus.wheel_rolling_noise}</td>
                    <td>${pneus.wheel_noise_level}</td>
                </tr>
                `;
        });

        addEventRemoveWheel();



    }

    function getCheckAndUncheckIcon(value) {
        if (value == 1) {
            return `<i class="fas fa-check"> <image src="${url_plugin + 'assets/img/check.svg'}"  /> </i>`;
        } else {
            return `<i class="fas fa-uncheck"><image src="${url_plugin + 'assets/img/unchecked.svg'}"  /></i>`;
        }
    }

    //delete pneu
    function addEventRemoveWheel() {
        var deleteBtns = document.querySelectorAll('.wheel_Delete_Btn');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function (e) {
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
    searchForm.addEventListener('submit', function (e) {
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