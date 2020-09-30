    <div id="poststuff" class="clearfix">
        <div class="postbox-container">
            <div class="meta-box-sortables ui-sortable">
                <div class="postbox">
                    <h2 class="hndle ui-sortable-handle"><span>Import Products</span></h2>
                    <div class="inside">
                        <p>
                            Import your products. Upload a completed ZIP package with the correct structure.
                        </p>

                        <p>
                            <a href="<?php echo get_template_directory_uri() . '/assets/downloads/import.zip' ?>" class="button button-primary button-large">Download Standard Example CSV</a>
                            <a href="<?php echo get_template_directory_uri() . '/assets/downloads/importvar.zip' ?>" class="button button-primary button-large">Download Variation Example CSV</a>
                        </p>


                        <form action='' method='post' enctype='multipart/form-data' style="margin-bottom: 1rem">
                            <label for="import">Import Standard Product ZIP File</label>
                            <input type="file" name="import_product" id="import" required accept=".zip">
                            <input class="button button-primary button-large" type="submit" value="Import Standard"
                                   name="submit">
                        </form>


                        <form action='' method='post' enctype='multipart/form-data'>
                            <label for="import">Import Variation Product ZIP File</label>
                            <input type="file" name="import_product" id="import" required accept=".zip">
                            <input class="button button-primary button-large" type="submit" value="Import Variation"
                                   name="submit">
                        </form>

                        <?php
                        if (isset($_POST['submit'])) {
                            $upload_results = "";
                            if (!isset($_FILES)) {
                                $upload_results .= "No files uploaded";
                            }
                            if ($upload_results == "") {
                                $import = $_FILES["import_product"];
                                $import = $_POST['submit'] === 'Import Standard' ? new import_products($import) : new import_product_variation($import);

                               if(is_array($import->import_count))
                                   echo '<p>Import complete!</p>';
                               else echo '<p><b>Error: </b>' . $import->import_count . '</p>';

                            }
                        }

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


