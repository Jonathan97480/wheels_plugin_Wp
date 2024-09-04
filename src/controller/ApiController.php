<?php

namespace Berou\JonDevWheelCheck\Controller;

use WP_Error;
use WP_REST_Request;


class ApiController
{




    public function __construct()
    {

        add_action('init', [$this, 'initApiRest']);
    }
    /**
     * Undocumented function
     *  Init api rest for plugin
     * @return void
     */
    public function initApiRest()
    {

        add_action('wp_ajax_add_product_to_cart', array($this, 'add_product_to_cart_ajax'));
        add_action('wp_ajax_nopriv_add_product_to_cart', array($this, 'add_product_to_cart_ajax'));

        add_action('rest_api_init', function () {
            register_rest_route('jon_dev_wheel_check/v1', '/remove_wheel', array(
                'methods' => 'GET',
                'callback' => [$this, 'remove_wheel'],
            ), array('id'));
            register_rest_route('jon_dev_wheel_check/v1', '/remove_brand', array(
                'methods' => 'GET',
                'callback' => [$this, 'remove_brand'],
            ), array('id'));

            register_rest_route('jon_dev_wheel_check/v1', '/get_wheels', array(
                'methods' => 'GET',
                'callback' => [$this, 'get_wheels'],
            ), array('page', 'limit'));

            register_rest_route('jon_dev_wheel_check/v1', '/search_wheel', array(
                'methods' => 'GET',
                'callback' => [$this, 'find_wheels'],
            ), array('search'));

            register_rest_route('jon_dev_wheel_check/v1', '/update_brand', array(
                'methods' => 'POST',
                'callback' => [$this, 'update_brand'],

            ));
        });
    }


    public function validate_permission()
    {
        return current_user_can('edit_post'); // ou tout autre vérification d'autorisation
    }

    /**
     * Undocumented function
     *  Add brand to database
     * @param [type] $data
     * @return json
     */
    public function update_brand(WP_REST_Request  $data)
    {
        $data = $data->get_json_params();

        if (!isset($data['id']) || !isset($data['name']) || !isset($data['picture_id'])) {
            return json_encode([
                'success' => false,
                'message' => 'Error: data not found',
                'data' => $data
            ]);
        }

        $dataBaseController = new DatabaseController();
        $result = $dataBaseController->update_brand($data['id'], $data['name'], $data['picture_id']);
        if (!is_array($result)) {
            return json_encode([
                'success' => false,
                'message' => 'Error: ' . 'Brand not updated',
            ]);
        }

        return json_encode([
            'success' => true,
            'message' => 'Brand updated',
            'data' => $result
        ]);
    }

    /**
     * Undocumented function
     *  Find wheels by search
     * @param [type] $data
     * @return void
     */
    public function find_wheels($data)
    {
        $dataBaseController = new DatabaseController();
        $wheels = $dataBaseController->find_wheels($data['search']);

        return json_encode([
            'success' => true,
            'data' => $wheels,
            'message' => 'Wheels found'
        ]);
    }


    /**
     * Undocumented function
     *  Get all wheels
     * @param [type] $data
     * @return void
     */
    public function get_wheels($data)
    {
        $dataBaseController = new DatabaseController();
        $wheels = $dataBaseController->get_all_pneus($data['page'], $data['limit']);

        return json_encode([
            'success' => true,
            'data' => $wheels,
            'message' => 'Wheels found'
        ]);
    }


    /**
     * Undocumented function
     *  Remove wheel by id from database
     * @param integer $id
     * @return boolean
     */
    public function remove_wheel($data)
    {
        $dataBaseController = new DatabaseController();
        $product =  $dataBaseController->get_whell_by_id($data['id'])[0];
        $dataBaseController->remove_wheel($data['id']);

        //Remove product woocommerce
        $wcDeleteController = new WcDeleteController();
        $result =  $wcDeleteController->wh_deleteProduct($product->wheel_woocommerce_product_id);
        return  json_encode([
            'success' => is_bool($result),
            'message' => is_bool($result) ? 'Wheel removed' : $result->get_error_message()
        ]);
    }

    /**
     * Undocumented function
     *  Remove brand by id from database
     * @param integer $id
     * @return boolean
     */
    public function remove_brand($data)
    {
        $dataBaseController = new DatabaseController();
        $dataBaseController->remove_brand($data['id']);

        return true;
    }


    public function add_product_to_cart_ajax()
    {

        if (!isset($_POST['product_id'])) {
            wp_send_json_error('ID du produit manquant');
        }

        $product_id = absint($_POST['product_id']);
        $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;


        //verifier si le produit existe
        $product = wc_get_product($product_id);
        if (!$product) {
            wp_send_json_error('Produit introuvable');
        } else {
            //verifier si le produit est en stock
            if (!$product->is_in_stock()) {
                wp_send_json_error('Produit en rupture de stock');
            } else {
                // Ajouter le produit au panier
                $added = WC()->cart->add_to_cart($product_id, $quantity);

                if ($added) {
                    wp_send_json_success('Produit ajouté au panier');
                } else {
                    wp_send_json_error('Produit en rupture de stock');
                }
            }
        }
    }
}
