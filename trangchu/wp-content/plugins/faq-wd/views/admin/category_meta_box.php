<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">

    <!-- Display tabs-->
    <ul id="<?php echo $taxonomy; ?>-tabs" class="category-tabs">
        <li class="tabs"><a href="#<?php echo $taxonomy; ?>-all" tabindex="3"><?php echo $tax->labels->all_items; ?></a></li>
        <li class="hide-if-no-js"><a href="#<?php echo $taxonomy; ?>-pop" tabindex="3"><?php _e('Most Used'); ?></a></li>
    </ul>

    <!-- Display taxonomy terms -->
    <div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
        <ul id="<?php echo $taxonomy; ?>checklist" data-wp-lists ="list:<?php echo $taxonomy ?>" class="categorychecklist form-no-clear">
            <?php
            foreach ($terms as $term) {
                $id = $taxonomy . '-' . $term->term_id;
                ?>
                <li class="selectit" id="<?php echo $id; ?>">
                    <label class="selectit">
                        <input type="checkbox" id="in-<?php echo $id; ?>" name="<?php echo $name; ?>" <?php echo $term->checked; ?> value="<?php echo htmlspecialchars($term->name); ?>" />
                        <?php echo $term->name; ?>
                        <br/>
                    </label>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>


    <!-- Display popular terms -->
    <div id="<?php echo $taxonomy; ?>-pop" class="tabs-panel" style="display: none;">
        <ul id="<?php echo $taxonomy; ?>checklist-pop" class="categorychecklist form-no-clear" >
            <?php
            foreach ($popular as $term) {
                $id = 'popular-' . $taxonomy . '-' . $term->term_id;
                ?>
                <li id="<?php echo $id; ?>"><label class="selectit">
                        <label>
                            <input type="checkbox" id="in-<?php echo $id; ?>" <?php echo $term->checked; ?>  value="<?php echo $term->term_id ?>" />
                            <?php echo $term->name; ?>
                            <br />
                        </label>
                </li>
                <?php
            }
            ?>

        </ul>
    </div>

</div>