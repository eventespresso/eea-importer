<?php
/**
 * Created by PhpStorm.
 * User: mnelson4
 * Date: 07/02/2019
 * Time: 1:18 PM
 */
?>
<a href="<?php echo esc_url($steps_url);?>">
<div id="<?php esc_attr_e($slug);?>-div" class="ee-import-type-div" style="display:flex">
    <div class="ee-import-type-image-div">
        <img src="<?php esc_attr_e($image_url)?>">
    </div>
    <div class="ee-import-type-text-div">
        <h2><?php echo $name;?></h2>
        <p><?php echo $description;?></p>
    </div>
</div>
</a>