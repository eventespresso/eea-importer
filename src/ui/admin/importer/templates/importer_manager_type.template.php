<?php
/**
 * @var string $steps_url
 * @var string $slug
 * @var string $image_url
 * @var string $name
 * @var string $description
 */
?>
<a href="<?php echo esc_url_raw($steps_url);?>">
<div id="<?php esc_attr_e($slug);?>-div" class="ee-import-type-div" style="display:flex">
    <div class="ee-import-type-image-div">
        <img alt="import file type" src="<?php esc_attr_e($image_url)?>">
    </div>
    <div class="ee-import-type-text-div">
        <h2><?php echo esc_html($name);?></h2>
        <p><?php echo esc_html($description);?></p>
    </div>
</div>
</a>
