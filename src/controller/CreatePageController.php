<?php

namespace Berou\JonDevWheelCheck\Controller;

use WP_Error;
use Berou\JonDevWheelCheck\Controller\DatabaseController;


class CreatePageController
{


    public function __construct() {}

    public static function create_page_find_wheels()
    {
        $page_title = 'Rechercher des pneus';
        $page_content = '[wheels_find]';
        $page_template = '';

        // Check if the page already exists
        $query = new \WP_Query([
            'post_type' => 'page',
            'post_status' => 'publish',
            'title' => $page_title

        ]);

        if (!$query->have_posts()) {
            // Create post object
            $page = [
                'post_title' => $page_title,
                'post_content' => $page_content,
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'post_slug' => 'wheels-find',
                'page_template' => get_template_part('page')
            ];

            // Insert the post into the database
            $id = wp_insert_post($page);

            if (is_int($id)) {
                $db =   new DatabaseController();
                $db->add_page($page_title, $id, $page_content);
            }
        }
    }

    public static function create_page_wheels_info()
    {
        $page_title = 'Informations sur le pneu';
        $page_content = '[wheels_info]';
        $page_template = '';

        // Check if the page already exists
        $query = new \WP_Query([
            'post_type' => 'page',
            'post_status' => 'publish',
            'title' => $page_title

        ]);

        if (!$query->have_posts()) {
            // Create post object
            $page = [
                'post_title' => $page_title,
                'post_content' => $page_content,
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'post_slug' => 'wheels-info',
                'page_template' => get_template_part('page')
            ];

            // Insert the post into the database
            $id = wp_insert_post($page);

            if (is_int($id)) {
                $db =   new DatabaseController();
                $db->add_page($page_title, $id, $page_content);
            }
        }
    }


    public static function create_page_wheels_brands()
    {
        $page_title = 'pneus par marques';
        $page_content = '[wheels_brands]';
        $page_template = '';

        // Check if the page already exists
        $query = new \WP_Query([
            'post_type' => 'page',
            'post_status' => 'publish',
            'title' => $page_title

        ]);

        if (!$query->have_posts()) {
            // Create post object
            $page = [
                'post_title' => $page_title,
                'post_content' => $page_content,
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'post_slug' => 'wheels-brands',
                'page_template' => get_template_part('page')
            ];

            // Insert the post into the database
            $id =  wp_insert_post($page);

            if (is_int($id)) {
                $db =   new DatabaseController();
                $db->add_page($page_title, $id, $page_content);
            }
        }
    }

    public static function create_page_wheels_find_result()
    {
        $page_title = 'Résultat de la recherche de pneus';
        $page_content = '[wheels_find_result]';
        $page_template = '';

        // Check if the page already exists
        $query = new \WP_Query([
            'post_type' => 'page',
            'post_status' => 'publish',
            'title' => $page_title

        ]);

        if (!$query->have_posts()) {
            // Create post object
            $page = [
                'post_title' => $page_title,
                'post_content' => $page_content,
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'post_slug' => 'wheels-find-result',
                'page_template' => get_template_part('page')
            ];

            // Insert the post into the database
            $id =  wp_insert_post($page);

            if (is_int($id)) {
                $db =   new DatabaseController();
                $db->add_page($page_title, $id, $page_content);
            }
        }
    }

    public static function remove_page()
    {
        $page_title = 'Rechercher des pneus';
        $page_title2 = 'Informations sur le pneu';
        $page_title3 = 'pneus par marques';
        $page_title4 = 'Résultat de la recherche de pneus';

        $query = new \WP_Query([
            'post_type' => 'page',
            'post_status' => 'publish',
            'title' => $page_title
        ]);

        if ($query->have_posts()) {
            $query->the_post();
            wp_delete_post(get_the_ID());
        }

        $query = new \WP_Query([
            'post_type' => 'page',
            'post_status' => 'publish',
            'title' => $page_title2
        ]);

        if ($query->have_posts()) {
            $query->the_post();
            wp_delete_post(get_the_ID());
        }

        $query = new \WP_Query([
            'post_type' => 'page',
            'post_status' => 'publish',
            'title' => $page_title3
        ]);

        if ($query->have_posts()) {
            $query->the_post();
            wp_delete_post(get_the_ID());
        }


        $query = new \WP_Query([
            'post_type' => 'page',
            'post_status' => 'publish',
            'title' => $page_title4
        ]);

        if ($query->have_posts()) {
            $query->the_post();
            wp_delete_post(get_the_ID());
        }
    }
}
