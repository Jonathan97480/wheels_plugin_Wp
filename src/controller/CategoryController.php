<?php

namespace Berou\JonDevWheelCheck\Controller;

use WP_Error;
use Berou\JonDevWheelCheck\Controller\VariableController;


class CategoryController
{

    const sub_categorys = VariableController::subCategorys;
    const main_categorys = VariableController::mainCategorys;

    public function __construct($file)
    {
        register_activation_hook($file, array($this, 'CreateCategoryAndSubCategory'));
    }

    /**
     * Undocumented function
     *  Create category and sub category for wheel
     * @return void
     */
    public static function CreateCategoryAndSubCategory()
    {
        // Vérifier que les propriétés existent et sont des tableaux
        if (!is_array(self::main_categorys) ||  !is_array(self::sub_categorys)) {
            echo 'Les catégories principales ou sous-catégories ne sont pas définies correctement.';
            return;
        }

        foreach (self::main_categorys as $mainCategory) {
            // Vérifier si la catégorie existe déjà
            $term = get_term_by('name', $mainCategory, 'wheel_category');
            $term_id = null;
            if (!$term) {
                // Créer la catégorie parent si elle n'existe pas
                $parent_category = wp_create_category(
                    $mainCategory,

                );
            } else {
                $term_id = $term->term_id;
            }

            //add woocommerce category
            $term = get_term_by('name', $mainCategory, 'product_cat');
            $term_id = null;
            if (!$term) {
                // Créer la catégorie parent si elle n'existe pas
                $parent_category = wp_insert_term(
                    $mainCategory,
                    'product_cat',
                    array(
                        'description' => 'This is a category for wheel',
                        'slug' => $mainCategory,
                        'parent' => 0

                    )
                );
            }


            // Ajouter les sous-catégories
            foreach (self::sub_categorys as $subCategory) {
                $sub_term = get_term_by('name', $subCategory, 'wheel_category');
                if (!$sub_term) {
                    $sub_category = wp_create_category(
                        $subCategory,
                        $parent_category

                    );
                }

                //add woocommerce category
                $sub_term = get_term_by('name', $subCategory, 'product_cat');
                if (!$sub_term) {
                    $sub_category = wp_insert_term(
                        $subCategory,
                        'product_cat',
                        array(
                            'description' => 'This is a category for wheel',
                            'slug' => $subCategory,
                            'parent' => $parent_category
                        )
                    );
                }
            }
        }
    }

    /**
     * Undocumented function
     *  Remove category and sub category for wheel
     * @return void
     */
    public static function RemooveCategorysAndSubCategorys()
    {
        foreach (self::main_categorys as $mainCategory) {
            $idCatexist = get_cat_ID($mainCategory);

            if ($idCatexist) {
                wp_delete_category($idCatexist);

                foreach (self::sub_categorys as $subCategory) {
                    $idSubCatexist = get_cat_ID($subCategory);
                    if ($idSubCatexist) {
                        wp_delete_category($idSubCatexist);
                    }
                }
            }
        }
    }
}
