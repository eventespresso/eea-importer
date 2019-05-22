<?php
use EventEspresso\core\domain\Domain;
?>
<p>
    <strong>
        <?php printf(
            esc_html__('Map the CSV File’s Columns to %s Data', 'event_espresso'),
            Domain::brandName()
        );?>
    </strong>
</p>
<p>
    <?php printf(
        esc_html__(
            'On the left are the names of columns found in the CSV file you just uploaded. In each dropdown list are the system questions, custom questions, and other %s data that can be populated from the cell’s value.',
            'event_espresso'
        ),
        Domain::brandName()
    ); ?>
</p>
<p>
    <?php esc_html_e('Only the First Name and Email system questions must be mapped, all others are optional. You can also leave columns unmapped if you like.', 'event_espresso'); ?>
</p>