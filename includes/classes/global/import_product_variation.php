<?php

/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 13/07/2017
 * Time: 14:02
 */
class import_product_variation
{
    private $import_dir;

    private $current_import_dir;

    private $file_path;

    private $import;

    private $i;

    private $u;

    public $import_count;

    public function __construct($import)
    {

        $this->import = $import;

        $this->import_dir = WP_CONTENT_DIR . '/imports';

        $this->current_import_dir = $this->import_dir . '/' . date('d-m-Y-h-i-s') . '/import';

        if (!file_exists($this->current_import_dir)) mkdir($this->current_import_dir, 0775, true);

        $this->file_path = $this->current_import_dir . '/' . basename($import["name"]);

        $this->csv = $this->current_import_dir . '/import.csv';

        if ($this->proccess_zip() && file_exists($this->csv)) $this->start_import();

        else $this->import_count = 'Unzip failed or import.csv doesn\'t exist';

    }

    private function proccess_zip()
    {

        $zip = new ZipArchive;

        $folder = $this->import_dir . '/' . date('d-m-Y-h-i-s') . '/' . basename($this->import["name"], '.zip');

        move_uploaded_file($this->import["tmp_name"], $this->file_path);

        if ($zip->open($this->file_path) === TRUE) {

            $zip->extractTo($this->import_dir . '/' . date('d-m-Y-h-i-s'));

            $zip->close();

            shell_exec("mv  $folder/* $this->current_import_dir");

            shell_exec("rm  $this->file_path");

            shell_exec("rm -rf $folder");

            return true;

        } else return false;
    }

    private function start_import()
    {

        $products = csv_to_array($this->csv);

        $products = $this->process_products($products);

        $this->i = 0;

        $this->u = 0;

        $variations_processed = $this->variations_processed($products);

//        print_object($variations_processed);
//
        $this->add_products($variations_processed);

        $this->import_count = [
            'imported' => $this->i,
            'updated' => $this->u
        ];

    }

    private function process_products($products)
    {

        $products_processed = array();

        foreach ($products as $key => $product) {

            foreach ($product as $i => $attr) {

                $i = sanitize_title($i);

                $attr = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i", '<$1$2>', $attr);

                $products_processed[$key][$i] = $attr;

            }

            $categories = explode(',', $products_processed[$key]['categories']);

            foreach ($categories as $cat_key => $category) $categories[$cat_key] = explode(' > ', $category);

            $products_processed[$key]['categories'] = $categories;

        }

        return $products_processed;
    }

    private function variations_processed($products)
    {

        $variations_processed = [];

        foreach ($products as $product) {

            if(!isset($product['parent-sku']))

                continue;

            $parent_sku = $product['parent-sku'];

            $variations_processed[$parent_sku]['title'] = $product['parent-title'];

            $variations_processed[$parent_sku]['content'] = $product['product-description'];

            $variations_processed[$parent_sku]['meta_title'] = $product['meta-title'];

            $variations_processed[$parent_sku]['meta_keywords'] = $product['meta-keywords'];

            $variations_processed[$parent_sku]['meta_description'] = $product['meta-description'];

            $variations_processed[$parent_sku]['product_subtitle'] = $product['product-subtitle'];

            $variations_processed[$parent_sku]['variation_type'] = strtolower($product['variation-type']);

            if ($categories = $product['categories']) {

                $variations_processed[$parent_sku]['categories'][] = $categories;

                $variations_processed[$parent_sku]['categories'] = array_map("unserialize", array_unique(array_map("serialize", $variations_processed[$parent_sku]['categories'])));;

            }

            if ($colour = $product['colour']) {

                $variations_processed[$parent_sku]['colours'][] = $colour;

                $variations_processed[$parent_sku]['colours'] = array_unique($variations_processed[$parent_sku]['colours']);

            }

            if ($size = $product['size']) {

                $variations_processed[$parent_sku]['sizes'][] = $size;

                $variations_processed[$parent_sku]['sizes'] = array_unique($variations_processed[$parent_sku]['sizes']);

            }

            if ($brand = $product['brand'])

                $variations_processed[$parent_sku]['brand'][] = $brand;


            if ($age_group = $product['age-group']) {

                $variations_processed[$parent_sku]['age_group'][] = $age_group;

                $variations_processed[$parent_sku]['age_group'] = array_unique($variations_processed[$parent_sku]['age_group']);

            }


            if ($formulation = $product['formulation']) {

                $variations_processed[$parent_sku]['formulation'][] = $formulation;

                $variations_processed[$parent_sku]['formulation'] = array_unique($variations_processed[$parent_sku]['formulation']);

            }

            if ($skin_type = $product['skin-type']) {

                $variations_processed[$parent_sku]['skin_type'][] = $skin_type;

                $variations_processed[$parent_sku]['skin_type'] = array_unique($variations_processed[$parent_sku]['skin_type']);

            }

            if ($hazardous_goods = $product['hazardous-goods']) {

                $variations_processed[$parent_sku]['hazardous_goods'][] = $hazardous_goods;

                $variations_processed[$parent_sku]['hazardous_goods'] = array_unique($variations_processed[$parent_sku]['hazardous_goods']);

            }

            if ($gender = $product['gender']) {

                $variations_processed[$parent_sku]['gender'][] = $gender;

                $variations_processed[$parent_sku]['gender'] = array_unique($variations_processed[$parent_sku]['gender']);

            }

            if ($spf = $product['spf']) {

                $variations_processed[$parent_sku]['spf'][] = $spf;

                $variations_processed[$parent_sku]['spf'] = array_unique($variations_processed[$parent_sku]['spf']);

            }
            if ($_rrp_price = $product['rrp-price-inc-vat']) {

                $variations_processed[$parent_sku]['rrp'][] = $_rrp_price;

                $variations_processed[$parent_sku]['rrp'] = array_unique($variations_processed[$parent_sku]['rrp']);

            }

            $var_sku = $product['variation-sku'];

            $var_title = $product['variation-title'];

            $var_content = $product['product-summary'];

            $_sale_price = $product['sale-price-inc-vat'];

            $ean = $product['ean'];

            $weight = $product['weight-in-kgs'];

            $depth = $product['depth'];

            $width = $product['width'];

            $height = $product['height'];

            $_stock = $product['stock-value'];

            $variation = $product['variation'];

            $variations_processed[$parent_sku]['variations'][$var_sku] = [

                'title' => $var_title,

                'content' => $var_content,

                'regular_price' => $_sale_price,

                'price' => $_sale_price,

                'rrp_price' => $_rrp_price,

                'manage_stock' => 'yes',

                'stock' => $_stock,

                'ean' => $ean,

                'weight' => $weight,

                'length' => $depth,

                'width' => $width,

                'height' => $height,

                'variation' => $variation

            ];


        }

        return $variations_processed;

    }

    private function add_products($variations_processed)
    {

        foreach ($variations_processed as $parent_sku => $product) {

            $var_tax = wc_attribute_taxonomy_name($product['variation_type']);

            if ($parent_id = wc_get_product_id_by_sku($parent_sku))

                wp_update_post([

                    'ID' => $parent_id,

                    'post_title' => iconv('ISO-8859-1','UTF-8',$product['title']),

                    'post_content' => iconv('ISO-8859-1','UTF-8',$product['content']),

                ]);

            else

                $parent_id = wp_insert_post([

                    'post_title' => iconv('ISO-8859-1','UTF-8',$product['title']),

                    'post_type' => 'product',

                    'post_status' => 'publish',

                    'post_content' => iconv('ISO-8859-1','UTF-8',$product['content']),

                ]);

            wp_set_object_terms($parent_id, 'variable', 'product_type', false);

            $attributes = array(

                $product['variation_type'] => array(

                    'name' => $var_tax,

                    'value' => '',

                    'is_visible' => '1',

                    'is_variation' => '1',

                    'is_taxonomy' => '1'

                )
            );

            $updates = array(

                '_sku' => $parent_sku,

                '_yoast_wpseo_metadesc' => $product['meta_description'],

                '_yoast_wpseo_metakeywords' => $product['meta_keywords'],

                '_yoast_wpseo_title' => $product['meta_title'],

                '_rrp_price' => max($product['rrp']),

                '_product_attributes' => $attributes,

                'product_subtitle' => $product['product_subtitle']

            );

            foreach ($updates as $update_key => $update) {

                update_post_meta($parent_id, $update_key, $update);

            }

            $tags = [];

            if (key_exists('colours', $product))

                $tags['product_colour'] = array_unique($product['colours']);

            if (key_exists('sizes', $product))

                $tags['product_size'] = array_unique($product['sizes']);

            if (key_exists('brand', $product))

                $tags['product_brand'] = array_unique($product['brand']);

            if (key_exists('age_group', $product))

                $tags['product_age_group'] = array_unique($product['age_group']);

            if (key_exists('formulation', $product))

                $tags['product_formulation'] = array_unique($product['formulation']);

            if (key_exists('skin_type', $product))

                $tags['product_skin_type'] = array_unique($product['skin_type']);

            if (key_exists('hazardous_goods', $product))

                $tags['product_hazardous_goods'] = array_unique($product['hazardous_goods']);

            if (key_exists('gender', $product))

                $tags['product_gender'] = array_unique($product['gender']);

            if (key_exists('spf', $product))

                $tags['product_spf'] = array_unique($product['spf']);

            if (key_exists('fragrance_name', $product))
                $tags['product_fragrance_name'] = array_unique($product['fragrance_name']);


            foreach ($tags as $tag_key => $tag_list) {

                $terms = [];

                foreach ($tag_list as $tag) {

                    $tag_array = explode(',', $tag);

                    foreach ($tag_array as $tag_item) {

                        $term_exists = term_exists(sanitize_title($tag_item), $tag_key);

                        if (!$term_exists)

                            $term = wp_insert_term($tag_item, $tag_key);

                        else

                            $term = $term_exists;

                        $terms[] = get_term($term['term_id'], $tag_key)->slug;

                    }

                }

                if ($terms)

                    wp_set_post_terms($parent_id, $terms, $tag_key, true);

            }

            if($product['hazardous_goods'] === 'yes')

                wp_set_post_terms(get_the_ID(),6280, 'product_hazardous_goods');

            $categories = [];

            foreach ($product['categories'] as $category_block)

                foreach ($category_block as $category_lists)

                    $categories[] = $category_lists;

            $categories = array_map("unserialize", array_unique(array_map("serialize", $categories)));;

            foreach ($categories as $split_categories) {

                $cat_parent = 0;

                foreach ($split_categories as $category) {

                    $terms = [];

                    if ($category) {

                        $term_exists = term_exists(sanitize_title($category), 'product_cat');

                        if (!$term_exists)

                            $term = wp_insert_term($category, 'product_cat', array(

                                'parent' => $cat_parent

                            ));

                        else

                            $term = $term_exists;

                        $cat_parent = $term['term_id'];

                        $terms[] = $term['term_id'];

                        wp_set_post_terms($parent_id, $terms, 'product_cat', true);

                    }

                }

            }

            $image_dir = $this->current_import_dir . '/' . $parent_sku;

            if (!has_post_thumbnail($parent_id) && file_exists($image_dir))

                $this->update_product_images($image_dir, $parent_id);


            foreach ($product['variations'] as $var_sku => $variation) {

                $var_id = wc_get_product_id_by_sku($var_sku);

                $add_var = false;

                if (!$var_id) {

                    $add_var = true;

                    $var_id = wp_insert_post([

                        'post_title' => $variation['title'],

                        'post_content' => strip_tags($variation['content']),

                        'post_status' => 'publish',

                        'post_parent' => $parent_id,

                        'post_type' => 'product_variation'

                    ]);

                }

                $updates = array(

                    '_sku' => $var_sku,

                    '_manage_stock' => 'yes',

                    'attribute_' . $var_tax => sanitize_title($variation['variation'])

                );

                if (key_exists('regular_price', $variation))

                    $updates['_regular_price'] = $variation['regular_price'];

                if (key_exists('regular_price', $variation))

                    $updates['_price'] = $variation['regular_price'];

                if (key_exists('rrp_price', $variation))

                    $updates['_rrp_price'] = $variation['rrp_price'];

                if (key_exists('stock', $variation)) {

                    $updates['_stock'] = $variation['stock'];

                    if ($updates['_stock'] === 0)

                        $updates['_stock_status'] = 'outofstock';

                    else

                        $updates['_stock_status'] = 'instock';

                }

                if (key_exists('ean', $variation))

                    $updates['_ean'] = $variation['ean'];

                if (key_exists('weight', $variation))

                    $updates['_weight'] = $variation['weight'];

                if (key_exists('length', $variation))

                    $updates['_length'] = $variation['length'];

                if (key_exists('width', $variation))

                    $updates['_width'] = $variation['width'];

                if (key_exists('height', $variation))

                    $updates['_height'] = $variation['height'];

                foreach ($updates as $update_key => $update)

                    if (get_post_meta($var_id, $update_key)) update_post_meta($var_id, $update_key, $update); else add_post_meta($var_id, $update_key, $update);

                $image_dir = $image_dir . '/' . $var_sku;

                if (!has_post_thumbnail($var_id) && file_exists($image_dir))

                    $this->update_product_images($image_dir, $var_id);

                if ($add_var) {

                    wp_set_object_terms($parent_id, array($variation['variation']), $var_tax, true);

                    WC_Product_Variable::sync($parent_id);

                    WC_Abstract_Legacy_Product::sync_attributes( $parent_id, true );

                }

            }

        } // End

    }

    private function update_product_images($image_dir, $id)
    {

        $scanned_directory = array_diff(scandir($image_dir), array('..', '.'));

        trigger_error(json_encode($scanned_directory));

        $this->import_count = $scanned_directory;

        $x = 0;

        $uploaded_images = [];

        foreach ($scanned_directory as $image) {

            $image_path = $image_dir . '/' . $image;

            if (@is_array(getimagesize($image_path))) {

                $image = upload_image_as_attachment($image_path, $id, get_the_title($id));

                if ($image) $uploaded_images[] = $image;

            }

        }

        if ($uploaded_images)

            foreach ($uploaded_images as $image_key => $uploaded_image) {

                $x++;

                if ($x === 1 && !has_post_thumbnail($id)) set_post_thumbnail($id, $uploaded_image);

                else {

                    $gallery = get_post_meta($id, '_product_image_gallery', true);

                    $gallery = explode(',', $gallery);

                    $gallery[] = $uploaded_image;

                    update_post_meta($id, '_product_image_gallery', implode(',', $gallery));

                }

            }

    }

}