<?php
/**
 * Created by PhpStorm.
 * User: mnelson4
 * Date: 07/02/2019
 * Time: 1:18 PM
 */
?>
<div id="<?php esc_attr_e($slug);?>-div" class="ee-import-type-div">
    <h2><?php echo $name;?></h2>
    <img src="<?php esc_attr_e($image_url)?>">
    <p><?php echo $description;?></p>
</div>