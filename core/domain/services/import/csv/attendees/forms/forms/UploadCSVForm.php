<?php

namespace EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\forms\forms;

use EE_Admin_File_Uploader_Input;
use EE_Error;
use EE_File_Input;
use EE_Form_Section_HTML;
use EE_Form_Section_Proper;
use EE_Validation_Error;
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
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class UploadCSVForm extends EE_Form_Section_Proper
{
    public function __construct($options_array = array())
    {
        $options_array = array_merge_recursive(
            [
                'subsections' => array(
                    'header' => new EE_Form_Section_HTML(
                        EEH_HTML::h2(esc_html__('Upload CSV  File', 'event_espresso'))
                    ),
                    'instructions' => new EE_Form_Section_HTML(
                        EEH_HTML::p(
                            esc_html__('Upload a CSV (comma-separated-value) file.', 'event_espresso')
                        )
                    ),
                    'file' => new EE_File_Input(
                        [
                            'required' => true
                        ]
                    ),
                    'subform' => new EE_Form_Section_Proper(
                        [
                            'subsections' => [
                                'file2' => new EE_File_Input(),
                            ]
                        ]
                    ),
                )
            ],
            $options_array
        );
        parent::__construct($options_array);
    }

    /**
     * @since $VID:$
     * @return bool|void
     * @throws EE_Validation_Error
     * @throws EE_Error
     */
    protected function _validate()
    {
        parent::_validate();
        $valid_data = $this->valid_data();
        if( ! isset($valid_data['file'])) {
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
        } catch (LogicException $e) {
            $this->add_validation_error($e);
            return;
        } catch (RuntimeException $e) {
            $this->add_validation_error($e);
            return;
        }
    }

    /**
     * @since $VID:$
     * @param $url
     * @return int
     */
    protected function getAttachmentId( $url ) {
        global $wpdb;
        $attachment_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid=%s LIMIT 1", $url));
        return $attachment_id;
    }

}
// End of file UploadCSVForm.php
// Location: EventEspresso\AttendeeImporter\core\domain\services\import\csv\attendees\forms\forms/UploadCSVForm.php
