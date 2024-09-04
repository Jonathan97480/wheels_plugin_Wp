<?php

namespace Berou\JonDevWheelCheck\Controller;

use Berou\JonDevWheelCheck\JonDevWheel;

class AdminController
{



    public function __construct()
    {
        $this->init_hooks();
    }

    public function init_hooks()
    {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        /*   add_shortcode('jon_dev_car_check', [$this, 'render_shortcode']); */
    }


    public function add_admin_menu()
    {

        add_menu_page(
            'Jon Dev Wheel Check',
            'Jon Dev Wheel Check',
            'manage_options',
            'jon_dev_wheel_check',
            [$this, 'render_admin_add_wheel'],
            'dashicons-car',
            6
        );
        add_submenu_page(
            'jon_dev_wheel_check',
            'Ajouter un pneu',
            'Ajouter un pneu',
            'manage_options',
            'add_wheel',
            [$this, 'render_admin_add_wheel'],
        );

        add_submenu_page(
            'jon_dev_wheel_check',
            'Ajouter une marque',
            'Ajouter une marque',
            'manage_options',
            'add_brans',
            [$this, 'render_admin_add_brand']
        );

        add_pages_page(
            'jon_dev_wheel_edit_wheel',
            'jon_dev_wheel_dit_wheel',
            'manage_options',
            'update_wheel',
            [$this, 'render_admin_edit_wheel'],
            110
        );
    }

    public function register_settings() {}

    public function render_admin_add_wheel()
    {
        /*   var_dump($page); */
        JonDevWheel::render('add_wheel');
    }

    public function render_admin_add_brand()
    {
        /*   var_dump($page); */
        JonDevWheel::render('add_brand');
    }

    public function render_admin_edit_wheel()
    {

        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);

            $this->render_edit_pneu_form($id);
        } else {
            echo '<p>ID de pneu manquant dans l\'URL.</p>';
        }
    }
    public static function render_edit_pneu_form($id)
    {
        $pneu = DatabaseController::get_whell_by_id($id);
        JonDevWheel::render('edit_wheel', ['pneu' => $pneu]);
    }

    /*  public function render_shortcode()
    {
        JonDevWheel::render('modalView');
    } */
}
