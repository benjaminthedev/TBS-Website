<?php


class import_products
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

        $this->current_import_dir = $this->import_dir . '/' . date('d-m-Y-h-i-s');

        if (!file_exists($this->current_import_dir)) mkdir($this->current_import_dir, 0775, true);

        $this->file_path = $this->current_import_dir . '/' . basename($import["name"]);

        $this->csv = $this->current_import_dir . '/import.csv';

        if ($this->proccess_zip() && file_exists($this->csv)) $this->start_import();

        else $this->import_count = 'Unzip failed or import.csv doesn\'t exist';
    }

    private function proccess_zip()
    {

        $zip = new ZipArchive;

        $folder = $this->current_import_dir . '/' . basename($this->import["name"], '.zip');

        move_uploaded_file($this->import["tmp_name"], $this->file_path);

        if ($zip->open($this->file_path) === TRUE) {

            $zip->extractTo($this->current_import_dir);

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


        $products = $this->proccess_products($products);


        $this->i = 0;

        $this->u = 0;


        foreach ($products as $product) if (key_exists('child-reference', $product)) $this->crud_product($product);
    }

    private function proccess_products($products)
    {

        $products_processed = array();

        foreach ($products as $key => $product) {

            foreach ($product as $i => $attr) {

                $i = sanitize_title($i);

                $attr = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i", '<$1$2>', $attr);

                $products_processed[$key][$i] = $attr;

            }

            // Process Product Cats
            $categories = explode(',', $products_processed[$key]['categories']);

            foreach ($categories as $cat_key => $category) $categories[$cat_key] = explode(' > ', $category);

            $products_processed[$key]['categories'] = $categories;

        }
        return $products_processed;
    }


    private function crud_product($product)
    {

        $sku = $product['child-reference'];

        $title = $product['child-product-title'];

        $content = $product['product-description'];

        $product_subtitle = $product['product-subtitle'];

        $image_dir = $this->current_import_dir . '/' . $sku;

        $id = wc_get_product_id_by_sku($sku);

        if ($id) {

            $this->u++;

            $my_post = [
                'ID' => $id,
                'post_title' => iconv('ISO-8859-1', 'UTF-8', $title),
                'post_content' => iconv('ISO-8859-1', 'UTF-8', $content),
            ];

            $id = wp_update_post($my_post);

        } else {

            $this->i++;

            $id = wp_insert_post([
                'post_title' => iconv('ISO-8859-1', 'UTF-8', $title),
                'post_type' => 'product',
                'post_content' => iconv('ISO-8859-1', 'UTF-8', $content),
                'post_status' => 'publish',
            ], true);

        }


        update_field('product_subtitle', $product_subtitle, $id);

        $this->update_product_meta($product, $id);

        $this->update_product_tags($product, $id);

        $this->update_product_categories($product, $id);

        if (file_exists($image_dir))
            $this->update_product_images($image_dir, $id);

        $this->import_count = [
            'imported' => $this->i,
            'updated' => $this->u
        ];

    }

    private function update_product_meta($product, $id)
    {
        $updates = [
            '_sku' => $product['child-reference'],
            '_regular_price' => $product['price-inc-vat'],
            '_price' => $product['price-inc-vat'],
            '_rrp_price' => $product['rrp-price-inc-vat'],
            '_manage_stock' => 'yes',
            '_stock' => $product['stock-value'],
            '_ean' => $product['ean'],
            '_weight' => $product['weight-in-kgs'],
            '_length' => $product['depth'],
            '_width' => $product['width'],
            '_height' => $product['height'],
            '_yoast_wpseo_metadesc' => $product['meta-description'],
            '_yoast_wpseo_metakeywords' => $product['meta-keywords'],
            '_yoast_wpseo_title' => $product['meta-description']
        ];

        foreach ($updates as $update_key => $update)

            if (get_post_meta($id, $update_key)) update_post_meta($id, $update_key, $update); else add_post_meta($id, $update_key, $update);

    }

    private function update_product_tags($product, $id)
    {

        $tags = [
            'product_colour' => $product['colour'],
            'product_size' => $product['size'],
            'product_brand' => $product['brand'],
            'product_age_group' => $product['age-group'],
            'product_formulation' => $product['formulation'],
            'product_skin_type' => $product['skin-type'],
            'product_hazardous_goods' => $product['hazardous-goods'],
            'product_gender' => $product['gender'],
            'product_spf' => $product['spf'],
            'product_fragrance_name' => $product['name'],
        ];

        foreach ($tags as $tag_key => $tag) {

            $terms = [];

            if ($tag) {

                $tag_array = explode(',', $tag);

                foreach ($tag_array as $tag_item) {

                    $term_exists = term_exists($tag_item, $tag_key);

                    if (!$term_exists) $term = wp_insert_term($tag_item, $tag_key);

                    else $term = $term_exists;


                    if (is_wp_error($term)) $term_id = $term->error_data['term_exists'];

                    else $term_id = $term['term_id'];

                    $term = get_term((int)$term_id, $tag_key);

                    if (is_wp_error($term)) {

                        print_object($term);

                    } else

                        $terms[] = $term->slug;

                }

                if ($terms) wp_set_post_terms($id, $terms, $tag_key, true);
            }
        }

        if ($product['hazardous-goods'] === 'yes')

            wp_set_post_terms(get_the_ID(), 6280, 'product_cat');
    }

    private function update_product_categories($product, $id)
    {

        foreach ($product['categories'] as $split_categories) {

            $cat_parent = 0;

            $terms = [];

            foreach ($split_categories as $category) {

                if ($category) {

                    $term_exists = term_exists(sanitize_title($category), 'product_cat');

                    if (!$term_exists) $term = wp_insert_term($category, 'product_cat', [
                        'parent' => $cat_parent
                    ]);

                    else $term = $term_exists;

                    if (is_wp_error($term)) $term_id = $term->error_data['term_exists'];

                    else $term_id = $term['term_id'];

                    $cat_parent = $term_id;

                    $terms[] = $term_id;

                }
            }

            if ($terms)

                wp_set_post_terms($id, $terms, 'product_cat', true);
        }

    }

    private function update_product_images($image_dir, $id)
    {

        $scanned_directory = array_diff(scandir($image_dir), array('..', '.'));


        $this->import_count = $scanned_directory;

        $x = 0;

        $uploaded_images = [];

        foreach ($scanned_directory as $image) {

            $image_path = $image_dir . '/' . $image;

            if (@is_array(getimagesize($image_path))) {

                trigger_error($image_path);

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