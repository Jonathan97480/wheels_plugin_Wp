<?php

namespace Berou\JonDevWheelCheck\Controller;

use WP_Error;

class DatabaseController
{
    //MAIN TABLE
    const DB_TABLE = 'jon_dev_wheel_check';
    const DB_VERSION = '1.1';
    const DB_OPTION = 'jon_dev_wheel_check_db_version';

    //BRAND TABLE
    const DB_TABLE_BRAND = 'jon_dev_wheel_check_brand';
    const DB_VERSION_BRAND = '1.1';
    const DB_OPTION_BRAND = 'jon_dev_wheel_check_db_version_brand';
    //PAGE TABLE 
    const DB_TABLE_PAGE = 'jon_dev_wheel_check_page';
    const DB_VERSION_PAGE = '1.1';
    const DB_OPTION_PAGE = 'jon_dev_wheel_check_db_version_page';


    public function __construct()
    {
        $this->create_table();
    }

    /**
     * Undocumented function
     * Create table in database
     * @return void
     */
    public function create_table()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $table_name = $wpdb->prefix . self::DB_TABLE;


        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
                wheel_guarantee VARCHAR(100) NOT NULL,
                wheel_model VARCHAR(100) NOT NULL,
                wheel_category VARCHAR(100) NOT NULL,
                wheel_subcategory VARCHAR(100) NOT NULL,
                wheel_dimension VARCHAR(100) NOT NULL,
                wheel_profile VARCHAR(100) NOT NULL,
                wheel_vehicle_type INT NOT NULL,
                wheel_season VARCHAR(100) NOT NULL,
                wheel_width INT NOT NULL,
                wheel_height INT NOT NULL,
                wheel_diameter INT NOT NULL,
                wheel_load_index INT NOT NULL,
                wheel_speed VARCHAR(100) NOT NULL,
                wheel_xl TINYINT(1) NOT NULL DEFAULT 0,
                wheel_runflat TINYINT(1) NOT NULL DEFAULT 0,
                wheel_use VARCHAR(100) NULL,
                wheel_winter_mark TINYINT(1) NOT NULL DEFAULT 0,
                wheel_mountain_law TINYINT(1) NOT NULL DEFAULT 0,
                wheel_fuel_efficiency VARCHAR(100) NULL,
                wheel_ground_adhesion VARCHAR(100) NULL,
                wheel_rolling_noise VARCHAR(100) NULL,
                wheel_noise_level VARCHAR(100) NULL,
                wheel_woocommerce_product_id INT NOT NULL,
                wheel_picture_id INT NOT NULL,
                wheel_brand_id INT NOT NULL,
                wheel_price FLOAT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY  (id)
            ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);




        //CREATE TABLE BRAND
        $table_name = $wpdb->prefix . self::DB_TABLE_BRAND;
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
            brand_name VARCHAR(100) NOT NULL,
            brand_logo_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        //CREATE TABLE PAGE WHEEL
        $table_name = $wpdb->prefix . self::DB_TABLE_PAGE;
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                    id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
                    page_name VARCHAR(100) NOT NULL,
                   page_wp_id INT NOT NULL,
                    page_template VARCHAR(100) NOT NULL,
                    page_shortcode VARCHAR(100) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY  (id)
                ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }


    /**
     * Undocumented function
     * Drop table
     * @return void
     */
    public function drop_table()
    {
        //DROP MAIN TABLE
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE;
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);

        //DROP BRAND TABLE
        $table_name = $wpdb->prefix . 'jon_dev_wheel_check_brand';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
    }

    /**
     * Undocumented function
     * Get all pneus
     * @return object
     */
    public static function get_all_pneus($page = 1, $limit = 10)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE;
        $sql = "SELECT * FROM $table_name LIMIT $limit OFFSET " . ($page - 1) * $limit;
        $result = $wpdb->get_results($sql);
        //get number page
        $sql = "SELECT COUNT(*) FROM $table_name";
        $total = $wpdb->get_var($sql);
        $pages = ceil($total / $limit);
        $result = array(
            'data' => $result,
            'page' => $page,
            'pages' => $pages
        );

        return $result;
    }
    /**
     * Undocumented function
     *  Get all brands
     * @return array
     */
    public static function get_all_brands()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE_BRAND;
        $sql = "SELECT * FROM $table_name";
        return $wpdb->get_results($sql);
    }

    /**
     * Undocumented function
     * Add brand to database
     * @param string $brand_name
     * @param integer $brand_logo_id
     * @return integer | WP_Error
     */
    public static function add_brand(string $brand_name, int $brand_logo_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE_BRAND;
        $wpdb->insert(
            $table_name,
            array(
                'brand_name' => $brand_name,
                'brand_logo_id' => $brand_logo_id
            )
        );
        //get is error
        $error = $wpdb->last_error;
        if ($error) {
            return new WP_Error(
                'error',
                $error
            );
        }

        return $wpdb->insert_id;
    }

    /**
     * Undocumented function
     * Add pneu to database
     * @param array $data
     * @return integer | WP_Error
     */
    public static function add_pneu(array $data)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE;
        $wpdb->insert(
            $table_name,
            $data
        );
        //get is error
        $error = $wpdb->last_error;
        if ($error) {
            return $error;
        }

        return $wpdb->insert_id;
    }

    public static function remove_wheel(int $id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE;
        $wpdb->delete(
            $table_name,
            array('id' => $id)
        );
    }

    public static function remove_brand(int $id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE_BRAND;
        $wpdb->delete(
            $table_name,
            array('id' => $id)
        );
    }

    public static function get_whell_by_id(int $id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE;
        $sql = "SELECT * FROM $table_name WHERE id = $id";
        return $wpdb->get_results($sql);
    }

    /**
     * Undocumented function
     * Get pneu by model name
     * @param string $model
     * @return array
     */
    public static function get_pneu_by_model(string $model)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE;
        $sql = "SELECT * FROM $table_name WHERE wheel_model = '$model'";
        return $wpdb->get_results($sql);
    }

    public static function find_wheels($recherche)
    {

        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE;
        $sql = "SELECT * FROM $table_name WHERE wheel_model LIKE '%$recherche%' OR wheel_category LIKE '%$recherche%' OR wheel_subcategory LIKE '%$recherche%' OR wheel_dimension LIKE '%$recherche%' OR wheel_profile LIKE '%$recherche%' OR wheel_season LIKE '%$recherche%' OR wheel_speed LIKE '%$recherche%' OR wheel_use LIKE '%$recherche%' OR wheel_fuel_efficiency LIKE '%$recherche%' OR wheel_ground_adhesion LIKE '%$recherche%' OR wheel_rolling_noise LIKE '%$recherche%' OR wheel_noise_level LIKE '%$recherche%' OR wheel_price LIKE '%$recherche%'";
        return $wpdb->get_results($sql);
    }

    /**
     * Undocumented function
     *  Find wheels by widget
     * @param [int] $width
     * @param [int] $height
     * @param [int] $diameter
     * @param [int] $load_index
     * @param [string] $speed_index
     * @param [int] $is_run_flat
     * @param [string] $season
     * @param [string] $type_vehicule 
     * @param [int] $brand_id
     * @return array
     */
    public static function find_wheels_by_widget($width, $height, $diameter, $load_index = null, $speed_index = null, $is_run_flat = null, $season = null, $type_vehicule = null, $brand_id = -1)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE;

        $request = "SELECT * FROM $table_name WHERE wheel_width = $width AND wheel_height = $height AND wheel_diameter = $diameter";

        if ($brand_id != -1) {
            $request .= " AND wheel_brand_id = $brand_id";
        }

        if ($season != 'Toutes saisons' && $season != null) {
            $request .= " AND wheel_season = '$season'";
        }

        if ($load_index != null) {
            $request .= " AND wheel_load_index = $load_index";
        }

        if ($speed_index != null) {
            $request .= " AND wheel_speed = '$speed_index'";
        }

        if ($is_run_flat != null) {
            $request .= " AND wheel_runflat = $is_run_flat";
        }

        if ($type_vehicule != null) {
            $request .= " AND wheel_category = '$type_vehicule'";
        }





        $sql = $request;
        return $wpdb->get_results($sql);
    }

    /**
     * Undocumented function
     * Update pneu
     * @param integer $id
     * @param array $data
     * @return array|WP_Error
     */
    public static function update_pneu($id, $data)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE;
        $wpdb->update(
            $table_name,
            $data,
            array('id' => $id)
        );
        //get is error
        $error = $wpdb->last_error;
        if ($error) {
            return new WP_Error(
                'error',
                $error
            );
        }

        //return new data
        return self::get_whell_by_id($id);
    }

    /**
     * Undocumented function
     * Update brand
     * @param integer $id
     * @param string $name
     * @param integer $id_picture
     * @return array|WP_Error
     */
    public static function update_brand($id, $name, $id_picture)
    {

        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE_BRAND;
        $wpdb->update(
            $table_name,
            array(
                'brand_name' => $name,
                'brand_logo_id' => $id_picture
            ),
            array('id' => $id)
        );

        $error = $wpdb->last_error;
        if ($error) {
            return new WP_Error(
                'error',
                $error
            );
        }

        return self::get_brand_by_id($id);
    }

    /**
     * Undocumented function
     * Get brand by id
     * @param integer $id
     * @return array
     */
    public static function get_brand_by_id($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE_BRAND;
        $sql = "SELECT * FROM $table_name WHERE id = $id";
        return $wpdb->get_results($sql);
    }

    /**
     * Undocumented function
     * Get pneu by brand
     * @param integer $brand_id
     * @return array
     */
    public static function getWheelsByBrand($brand_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE;
        $sql = "SELECT * FROM $table_name WHERE wheel_brand_id = $brand_id";
        return $wpdb->get_results($sql);
    }

    /**
     * Undocumented function
     * Get random wheels
     * @param integer number of wheels
     * @return array
     */
    public static function get_random_wheels($limit = 5)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE;
        $sql = "SELECT * FROM $table_name ORDER BY RAND() LIMIT $limit";
        return $wpdb->get_results($sql);
    }


    /**
     * Undocumented function
     *  Get brand by name
     * @param [type] $brand_id
     * @return array
     */
    public static function get_wheels_by_brand($brand_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE;
        $sql = "SELECT * FROM $table_name WHERE wheel_brand_id = $brand_id";
        return $wpdb->get_results($sql);
    }

    //PAGE
    public static function add_page($page_name, $page_wp_id, $chortcode, $page_template = "page.php")
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE_PAGE;
        $wpdb->insert(
            $table_name,
            array(
                'page_name' => $page_name,
                'page_wp_id' => $page_wp_id,
                'page_shortcode' => $chortcode,
                'page_template' => $page_template
            )
        );
        //get is error
        $error = $wpdb->last_error;
        if ($error) {
            return new WP_Error(
                'error',
                $error
            );
        }

        return $wpdb->insert_id;
    }

    public static function remove_page($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE_PAGE;
        $wpdb->delete(
            $table_name,
            array('id' => $id)
        );
    }

    public static function get_page_by_id($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE_PAGE;
        $sql = "SELECT * FROM $table_name WHERE id = $id";
        return $wpdb->get_results($sql);
    }

    public static function get_page_by_name($name)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE_PAGE;
        $sql = "SELECT * FROM $table_name WHERE page_name = '$name'";
        return $wpdb->get_results($sql);
    }

    public static function get_page_by_shortcode($shortcode)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE_PAGE;
        $sql = "SELECT * FROM $table_name WHERE page_shortcode = '$shortcode'";
        return $wpdb->get_results($sql);
    }

    public static function get_all_pages()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE_PAGE;
        $sql = "SELECT * FROM $table_name";
        return $wpdb->get_results($sql);
    }
}
