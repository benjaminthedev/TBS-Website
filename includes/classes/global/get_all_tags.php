<?php

/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 15/06/2017
 * Time: 14:29
 * @property  get_post_object
 */
class get_all_tags
{
    private $wpdb;
    private $is_cat;
    public $tags;

    public function __construct($term_id = false, $is_cat = false, $excluded = false, $use_public = false, $search = false)
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->is_cat = $is_cat;


        if (!$use_public) {
            if (!$posts = $this->get_post_object($term_id, $search))
                return false;

            if (!$taxonomies = $this->get_tax_object($posts))
                return false;


            return $this->tags = $this->get_tags($taxonomies, $term_id, $excluded);
        }

    }

    public function get_post_object($term_id, $search)
    {

        $posts = [];


        $query = "SELECT bs_posts.ID FROM bs_posts WHERE 1=1 AND bs_posts.post_type = 'product' AND (bs_posts.post_status = 'publish')";


        if ($term_id) {
            $sep = '';


            $query = "SELECT SQL_CALC_FOUND_ROWS  bs_posts.ID FROM bs_posts ";
            foreach ($term_id as $term => $value)
                $query .= "LEFT JOIN bs_term_relationships AS $term ON (bs_posts.ID = $term.object_id) ";
            $query .= "WHERE 1=1  AND ( ";
            $sep = '';
            foreach ($term_id as $term => $value) {
                $value = is_array($value) ? implode(',', $value) : $value;
                $query .= "$sep ($term.term_taxonomy_id IN ($value)) ";
                $sep = ' AND';
            }
            $query .= ") AND bs_posts.post_type = 'product' AND (bs_posts.post_status = 'publish')";
        }

        if ($search)
            $query .= "AND (((bs_posts.post_title LIKE '%$search%') OR (bs_posts.post_excerpt LIKE '%$search%') OR (bs_posts.post_content LIKE '%$search%'))) ";


        $post_objects = $this->wpdb->get_results($query);

        foreach ($post_objects as $post_object)
            $posts[] = (int)$post_object->ID;


        return $posts;

    }

    public function get_tax_object($posts)
    {

        $taxs = [];
        $posts = is_array($posts) ? implode(',', $posts) : $posts;
        $query = "SELECT DISTINCT  bs_term_relationships.term_taxonomy_id 
              FROM bs_term_relationships 
              WHERE (bs_term_relationships.object_id 
              IN ($posts))";

        $tax_objects = $this->wpdb->get_results($query);

        if (!$tax_objects)
            return false;

        foreach ($tax_objects as $tax_object)
            $taxs[] = (int)$tax_object->term_taxonomy_id;

        return implode(',', $taxs);

    }

    public function get_tags($taxs, $cat_selected, $exclude)
    {


        $excluded = [];

        $cat_selected = $cat_selected && $this->is_cat ? $cat_selected : 0;

        $product_cats = $exclude && key_exists('product_cat', $exclude) ? [] : $this->get_product_cats($taxs, $cat_selected);


        $query = "SELECT  bs_term_taxonomy.term_id, bs_term_taxonomy.taxonomy 
              FROM bs_term_taxonomy 
              WHERE (bs_term_taxonomy.term_id 
              IN ($taxs)) 
              AND bs_term_taxonomy.taxonomy!='product_type' 
              AND bs_term_taxonomy.taxonomy!='product_visibility'
              AND bs_term_taxonomy.taxonomy!='product_cat'
              AND bs_term_taxonomy.taxonomy!='pa_colour'";
        if ($exclude)
            foreach ($exclude as $key => $value) {
                $query .= "AND bs_term_taxonomy.taxonomy!='$key'";
                $excluded[$key] = $key;
            }


        $tax_objects = $this->wpdb->get_results($query);
        $taxs = [];

        if ($tax_objects) {


            foreach ($tax_objects as $object)
                $taxs[$object->taxonomy][] = $object->term_id;


            foreach ($taxs as $tax_key => $tax_values) {
                $term_ids = implode(',', $tax_values);
                $query = "SELECT * FROM bs_terms WHERE (bs_terms.term_id IN ($term_ids)) ORDER BY bs_terms.slug";
                $results = $this->wpdb->get_results($query);

                $taxs[$tax_key] = $results ? $results : [];
            }

            ksort($taxs);

        }

        $taxs = array_merge($product_cats, $taxs);
        $taxs = array_merge($excluded, $taxs);


        return $taxs;
    }

    private function get_product_cats($taxs, $cat_selected)
    {
        $query = "SELECT  bs_term_taxonomy.term_id, bs_term_taxonomy.taxonomy 
              FROM bs_term_taxonomy 
              WHERE (bs_term_taxonomy.term_id 
              IN ($taxs)) 
              AND bs_term_taxonomy.taxonomy='product_cat'
              AND bs_term_taxonomy.parent=$cat_selected";

        $product_objects = $this->wpdb->get_results($query);

        $product_cats = [];

        foreach ($product_objects as $object)
            $product_cats[$object->taxonomy][] = $object->term_id;

        foreach ($product_cats as $product_key => $product_values) {
            $term_ids = implode(',', $product_values);
            $query = "SELECT * FROM bs_terms WHERE (bs_terms.term_id IN ($term_ids)) ORDER BY bs_terms.slug";
            $results = $this->wpdb->get_results($query);
            $product_cats[$product_key] = $results;
        }
        ksort($product_cats);
        return $product_cats;
    }


}


