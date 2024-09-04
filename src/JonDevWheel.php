<?php

namespace Berou\JonDevWheelCheck;

use Berou\JonDevWheelCheck\Controller\AdminController;
use Berou\JonDevWheelCheck\Controller\DatabaseController;
use Berou\JonDevWheelCheck\Controller\WcDeleteController;
use Berou\JonDevWheelCheck\Controller\ApiController;
use Berou\JonDevWheelCheck\Controller\CategoryController;
use Berou\JonDevWheelCheck\Controller\CreatePageController;
use Berou\JonDevWheelCheck\Page\wheelsMainPage;

class JonDevWheel
{
    public $mainfilesPath = '';
    const TRANSIENT_ACTIVATION = 'jon_dev_Wheel_check_activation';

    public function __construct($file)
    {
        $this->mainfilesPath = $file;
        register_activation_hook($file, array($this, 'plugin_activation'));
        register_deactivation_hook($file, array($this, 'plugin_deactivation'));
        // register_uninstall_hook($file, array($this, 'plugin_remove'));

        add_action('init', [$this, 'enqueue_scripts']);
        new ApiController();
        $CategoryController = new CategoryController(__FILE__);

        if (is_admin()) {
            $adminController =  new AdminController();

            add_action('admin_enqueue_scripts', array($this, 'shapeSpace_load_wp_media_files'));
        }
    }

    public function enqueue_scripts()
    {

        wp_enqueue_script('jon_dev_wheel_check', plugin_dir_url(__FILE__) . 'assets/js/apiFront.js', ['jquery'], '1.0', true);
        wp_localize_script('jon_dev_wheel_check', 'jon_dev_api_url', [
            'url' => rest_url('')
        ]);

        // Add all shortcode
        add_shortcode('wheels_find', [$this, 'shortcode_wheels_find']);
        add_shortcode('wheels_find_result', [$this, 'shortcode_wheels_find_result']);
        add_shortcode('wheels_info', [$this, 'shortcode_wheels_info']);
        add_shortcode('wheels_brands', [$this, 'shortcode_wheels_brands']);
    }
    public function shapeSpace_load_wp_media_files()
    {

        wp_enqueue_media();
    }
    /**
     * Plugin activation
     * function to activate plugin and set transient
     * @return void
     */
    public function plugin_activation()
    {
        set_transient(self::TRANSIENT_ACTIVATION, 1);
        $dataBaseController = new DatabaseController();
        $createPageController = new CreatePageController();

        //CREATE PAGE FOR PLUGIN
        $createPageController->create_page_find_wheels();
        $createPageController->create_page_wheels_info();
        $createPageController->create_page_wheels_brands();
        $createPageController->create_page_wheels_find_result();
    }

    /**
     * Plugin deactivation
     * function to deactivate plugin and clear plugin data and options
     * @return void
     */
    public function plugin_deactivation()
    {
        $createPageController = new CreatePageController();
        $createPageController->remove_page();
    }

    public function plugin_remove()
    {
        // $clearPluginController = new ClearPluginController();
        // $clearPluginController->clearPlugin();
        $CategoryController = new CategoryController(__FILE__);
        $CategoryController->RemooveCategorysAndSubCategorys();

        //DROP ALL TABLE PLUGIN
        $dataBaseController = new DatabaseController();
        $dataBaseController->drop_table();

        //DELETE ALL PAGES
        $createPageController = new CreatePageController();
        $createPageController->remove_page();
    }


    /**
     * Notice activation
     * function to display notice activation plugin
     * @return void
     */
    public function notice_activation()
    {
        if (get_transient(self::TRANSIENT_ACTIVATION)) {

            self::render('notices', ['message' => 'Plugin Jon Dev Wheel Check activé']);
            delete_transient(self::TRANSIENT_ACTIVATION);
        }
    }

    /**
     * Render
     * function to render view
     * @param string $name
     * @param array $args
     * @return void
     */
    public static function render(string $name, array $args = []): void
    {
        extract($args);

        $file = JON_DEV_WHEEL_CHECK_DIR_PATH . "views/{$name}.php";

        if (file_exists($file)) {
            ob_start();

            include_once($file);

            echo ob_get_clean();
        }
    }



    public function shortcode_wheels_find()
    {
        ob_start();
        self::render('find_wheels', ['files' => plugin_dir_url($this->mainfilesPath)]);
        return ob_get_clean();
    }

    public function shortcode_wheels_brands()
    {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);

            ob_start();
            self::render('wheel_brand', ['files' => plugin_dir_url($this->mainfilesPath), 'id' => $id]);
            return ob_get_clean();
        } else {
            echo '<p>ID de pneu manquant dans l\'URL.</p>';
        }
    }
    public function shortcode_wheels_info()
    {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);

            ob_start();
            self::render('wheels_info', ['files' => plugin_dir_url($this->mainfilesPath), 'id' => $id]);
            return ob_get_clean();
        } else {
            echo '<p>ID de pneu manquant dans l\'URL.</p>';
        }
    }
    public function shortcode_wheels_find_result()
    {

        if (isset($_GET['W']) && isset($_GET['H']) && isset($_GET['D'])) {

            $width = intval($_GET['W']);
            $height = intval($_GET['H']);
            $diameter = intval($_GET['D']);
            $load_index = intval($_GET['Lad_index']);
            $speed_index = $_GET['Speed'];
            $is_run_flat = $_GET['is_runflat'];
            $saison = $_GET['saison'];
            $type_vehicule = $_GET['typeVehicule'];
            $brand = $_GET['brand'];


            ob_start();
            self::render('wheels_find_result', [
                'files' => plugin_dir_url($this->mainfilesPath),
                'width' => $width,
                'height' => $height,
                'diameter' => $diameter,
                'load_index' => $load_index,
                'speed_index' => $speed_index,
                'is_run_flat' => $is_run_flat,
                'saison' => $saison,
                'type_vehicule' => $type_vehicule,
                'brand' => $brand
            ]);

            return ob_get_clean();
        } else {
            echo '<p>Dimension du pneu manquant dans l\'URL.</p>';
        }
    }
}
