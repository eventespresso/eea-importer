<?php

namespace EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\forms;

use EE_Error;
use EE_File_Input;
use EE_Form_Section_HTML;
use EE_Form_Section_Proper;
use EEH_HTML;
use EventEspresso\core\services\request\files\FileSubmissionInterface;
use LogicException;
use RuntimeException;
use SplFileObject;

/**
 * Class UploadCSVForm
 *
 * Description
 *
 * @package        Event Espresso
 * @author         Mike Nelson
 * @since          1.0.0.p
 *
 */
class UploadCsvForm extends EE_Form_Section_Proper
{
    /**
     * @param array $options_array
     * @throws EE_Error
     */
    public function __construct(array $options_array = [])
    {
        $options_array = array_merge_recursive(
            [
                'subsections' => [
                    'header'       => new EE_Form_Section_HTML(
                        EEH_HTML::h2(
                            esc_html__('Upload CSV  File', 'event_espresso')
                            . $options_array['help_tab_link']
                        )
                    ),
                    'instructions' => new EE_Form_Section_HTML(
                        EEH_HTML::p(
                            esc_html__('Upload a CSV (comma-separated-value) file.', 'event_espresso')
                        )
                    ),
                    'file'         => new EE_File_Input(
                        [
                            'required'        => true,
                            'html_label_text' => esc_html__('CSV File', 'event_espresso'),
                            'html_help_text'  => esc_html__(
                                'The CSV file data will be imported from.',
                                'event_espresso'
                            ),
                        ]
                    ),
                ],
            ],
            $options_array
        );
        parent::__construct($options_array);
    }


    /**
     * @return void
     * @throws EE_Error
     * @since 1.0.0.p
     */
    protected function _validate()
    {
        parent::_validate();
        $valid_data = $this->valid_data();
        if (! isset($valid_data['file'])) {
            return;
        }
        $file = $valid_data['file'];
        if (! $file instanceof FileSubmissionInterface) {
            $this->add_validation_error(
                esc_html__('The file provided was not a valid CSV file. Please provide a CSV file.', 'event_espresso')
            );
            return;
        }

        try {
            $file_obj = new SplFileObject($file->getTmpFile(), 'r');
        } catch (LogicException | RuntimeException $e) {
            $this->add_validation_error($e);
            return;
        }
    }


    /**
     * @param $url
     * @return int
     * @since 1.0.0.p
     */
    protected function getAttachmentId($url): int
    {
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid=%s LIMIT 1", $url));
    }
}
// End of file UploadCSVForm.php
// Location: EventEspresso\AttendeeImporter\domain\services\import\csv\attendees\forms\forms/UploadCSVForm.php
