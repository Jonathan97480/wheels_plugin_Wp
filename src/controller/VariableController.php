<?php

namespace Berou\JonDevWheelCheck\Controller;




class VariableController
{
    public function __construct() {}

    public const   mainCategorys = [
        'Tourisme',
        '4x4',
        'Utilitaire',
        'Poids Lourd',
        'Agricole',
        'Manutention',
        'Génie Civil',
        'Moto',
        'Quad',
        'Remorque',
        'Autobus',
        'Camionnette',
        'Camion',
        'Voiture',
        'Véhicule de tourisme',
        'Véhicule utilitaire',
        'Véhicule de loisirs',
        'Véhicule de sport',
        'Véhicule de compétition',
        'Véhicule de collection',
        'Véhicule de transport',
        'Véhicule de chantier',
        'Véhicule de manutention',
        'Véhicule agricole',
        'Véhicule de génie civil',

    ];

    public  const subCategorys = [
        'Pneus larges',
        'Pneus XL',
        'Pneus profil bas',
        'Pneus usagés',
        'Pneus rechapés',
        'Pneus d\'été',
        'Pneus d\'hiver',
        'Pneus toutes saisons',
    ];

    public const pneusSpeed = [
        'Q',
        'R',
        'S',
        'T',
        'H',
        'V',
        'W',
        'Y',
        'ZR',
    ];

    public const  pneusfuelEfficiency = [
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
    ];

    public const pneusGroundAdhesion = [
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
    ];

    public const pneusNoiseLevel = [
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
    ];

    public const pneusSaision = [
        'Hiver',
        'Été',
        'Toutes saisons',
    ];

    public const pneusGuarantee = [
        'Aucune garantie',
        '1 an',
        '2 ans',
        '3 ans',
        '4 ans',
        '5 ans',
        '6 ans',
        '7 ans',
        '8 ans',
        '9 ans',
        '10 ans',
    ];

    public const pneusLoadIndex = [

        65,
        70,
        75,
        80,
        85,
        87,
        90,
        95,
        100,
        105,
        110,
        115,
        120,
        125,
        130,
        135,
        140,
        145,
        150,
        155,
        160,
        165,
        170,
        175,
        180,
        185,
        190,
        195,
        200,
        205,
        210,
        215,
        220,
        225,
        230,
        235,
        240,
        245,
        250,
        255,
        260,
        265,
        270,
        275,
        280,
        285,
        290,
        295,
        300,
        305,
        310,
        315,
        320,
        325,
        330,
        335,
        340,
    ];

    public const pneusWidth = [
        125,
        135,
        145,
        155,
        165,
        175,
        185,
        195,
        205,
        215,
        225,
        235,
        245,
        255,
        265,
        275,
        285,
        295,
        305,
        315,
        325,
        335,
        345,
        355,
        365,
        375,
        385,
        395,
        405,
        415,
        425,
        435,
        445,
        455,
        465,
        475,
        485,
        495,
        505,
        515,
        525,
        535,
        545,
        555,
        565,
        575,
        585,
        595,
        605,
        615,
        625,
        635,
        645,
        655,
        665,
        675,
        685,
        695,
        705,
        715,
        725,
        735,
        745,
        755,
        765,
        775,
        785,
        795,
        805,
        815,
        825,
        835,
        845,
        855,
        865,
        875,
        885,
        895,
        905,
        915,
        925,
        935,
        945,
        955,
        965,
        975,
        985,
        995,
    ];

    public const pneusHeight = [
        25,
        30,
        35,
        40,
        45,
        50,
        55,
        60,
        65,
        70,
        75,
        80,
        85,
        90,
        95,
        100,
        105,
        110,
        115,
        120,
        125,
        130,
        135,
        140,
        145,
        150,
        155,
        160,
        165,
        170,
        175,
        180,
        185,
        190,
        195,

    ];

    public const pneusDiameter = [
        10,
        12,
        13,
        14,
        15,
        16,
        17,
        18,
        19,
        20,
        21,
        22,
        23,
        24,
        25,
        26,
        27,
        28,
        29,
        30,
        31,
        32,
        33,
        34,
        35,
        36,
        37,
        38,
        39,
        40,
        41,
        42,
        43,
        44,
        45,
        46,
        47,
        48,
        49,
        50,
    ];


    /**
     * Undocumented function
     *  get categories woocommerce by name
     * @param [string] $name
     * @return WP_Term[]|int[]|string[]|string|WP_Error
     */
    public function get_wc_categories($name)
    {
        $args = array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'name' => $name,
        );
        $categories = get_terms($args);
        return $categories;
    }

    /**
     * Undocumented function
     * generate sku for wheel product (woocommerce)
     * @return string
     */
    function generate_sku()
    {
        $sku = '';
        $sku .= 'WHEEL-';
        $sku .= strtoupper(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 3));
        $sku .= '-';
        $sku .= strtoupper(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 3));
        $sku .= '-';
        $sku .= strtoupper(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 3));
        $sku .= '-';
        $sku .= strtoupper(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 3));
        return $sku;
    }

    /**
     * Undocumented function
     * generate product for woocomerce
     * @param array $post
     * @param WC_Product $product
     * @return int|WP_Error
     */
    function generate_product_for_woocomerce($post,  $product)
    {

        $idCat = $this->get_wc_categories($post['VehicleType'])[0]->term_id;
        $idCatSub =  $this->get_wc_categories($post['subCategory'])[0]->term_id;
        //get is woocommerce active
        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            //create product in woocommerce

            $product->set_name($post['modelName']);
            $product->set_status('publish');
            $product->set_catalog_visibility('visible');
            $product->set_description($_POST['description']);
            $product->set_short_description($post['shortDescription']);
            $product->set_sku($this->generate_sku());
            $product->set_price($_POST['price']);
            $product->set_regular_price($post['price']);
            $product->set_manage_stock(true);
            $product->set_stock_quantity($post['quantity']);
            $product->set_stock_status('instock');
            $product->set_backorders('no');
            $product->set_reviews_allowed(true);
            $product->set_sold_individually(false);
            $product->set_category_ids([$idCat, $idCatSub]);
            $product->set_image_id($_POST['upload_image_id']);
            return $product->save();
        }
    }

    /**
     * Undocumented function
     *
     * @param array $data 
     * @return boolean
     */
    public static function check_fields_required_completed($data)
    {
        $fields = [
            'modelName',
            'upload_image_id',
            'price',
            'width',
            'height',
            'diameter',
            'loadIndex',
            'brand',
            'description',
            'shortDescription',
            'quantity',

        ];

        foreach ($fields as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }
        return true;
    }


    /**
     * Undocumented function
     * add product to cart woocommerce
     * @param [type] $product_id
     * @param integer $quantity
     * @param integer $variation_id
     * @param array $variations
     * @return boolean
     */
    function add_product_cart($product_id, $quantity = 1, $variation_id = 0, $variations = array())
    {
        // Vérifier si le produit existe
        $product = wc_get_product($product_id);

        if (!$product) {
            return false;
        }

        // Ajouter le produit au panier
        $added = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variations);

        // Vérifier si l'ajout a réussi
        if ($added) {
            wc_add_to_cart_message(array($product_id => $quantity), true); // Affiche un message de succès
            return true;
        } else {
            return false;
        }
    }
}
