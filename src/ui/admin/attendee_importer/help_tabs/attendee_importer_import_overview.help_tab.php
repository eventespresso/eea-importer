<p><?php esc_html_e('Before Using the Event Espresso 4 Attendee Importer', 'event_espresso'); ?></p>
<p>
    <strong><?php esc_html_e('Backup', 'event_espresso');?></strong>
    <?php esc_html_e("Always make sure you have a complete database backup. If you import data improperly, or there is an error, this is the best way to undo the import.", 'event_espresso'); ?>
</p>
<p>
    <strong><?php esc_html_e('Setup The Event in Event Espresso', 'event_espresso');?></strong>
    <?php esc_html_e("You must first create the event and ticket you wish to import the attendees to. Currently, only importing to a single event's ticket is supported.", 'event_espresso'); ?>
</p>
<p>
    <strong><?php esc_html_e('Prepare CSV File', 'event_espresso');?></strong>
    <?php esc_html_e("Create your Comma-Separated-Value (CSV) file. The first row should be column headers, and each row subsequent represents a registration.", 'event_espresso'); ?>
</p>
<p>
    <?php esc_html_e("You can give any name you want to the column headers. But there must be a column for:", 'event_espresso'); ?>
</p>
<ul>
    <li><?php esc_html_e('First Name', 'event_espresso');?></li>
    <li><?php esc_html_e('Email', 'event_espresso');?></li>
</ul>
<p><?php esc_html_e('You can also add columns for any of the following:', 'event_espresso');?></p>
<ul>
    <li><?php esc_html_e('Any other system questions, like Last Name, Address, City, State, Country, Zip, or Phone. (Please note: for state you can use the state’s name, abbreviation, or database ID; for country you can use its name or ISO2 or ISO3 code)', 'event_espresso'); ?></li>
    <li><?php esc_html_e('Any custom questions. The column header’s text does not need to match the custom question. For checkbox or multi-select questions, separate the different answer values with a pipe "|". Eg "', 'event_espresso'); ?></li>
    <li><?php esc_html_e('The payment amount, if the registrations are for a paid ticket.', 'event_espresso'); ?></li>
</ul>