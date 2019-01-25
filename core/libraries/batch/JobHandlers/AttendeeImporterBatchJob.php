<?php

namespace EventEspresso\AttendeeImporter\core\libraries\batch\JobHandlers;

use EED_Attendee_Importer;
use EventEspresso\AttendeeImporter\core\domain\services\commands\ImportCsvRowCommand;
use EventEspresso\core\services\loaders\LoaderFactory;
use EventEspressoBatchRequest\Helpers\BatchRequestException;
use EventEspressoBatchRequest\Helpers\JobParameters;
use EventEspressoBatchRequest\Helpers\JobStepResponse;
use EventEspressoBatchRequest\JobHandlerBaseClasses\JobHandler;
use LogicException;
use RuntimeException;
use SplFileObject;

/**
 * Class AttendeeImporterBatchJob
 *
 * Takes care of breaking up the often big job of importing a CSV file into the DB into smaller steps.
 * Offloads the actual work though to command objects.
 *
 * @package     Event Espresso
 * @author         Mike Nelson
 * @since         $VID:$
 *
 */
class AttendeeImporterBatchJob extends JobHandler
{


    /**
     * Performs any necessary setup for starting the job. This is also a good
     * place to setup the $job_arguments which will be used for subsequent HTTP requests
     * when continue_job will be called
     * @param JobParameters $job_parameters
     * @return JobStepResponse
     * @throws LogicException
     * @throws RuntimeException
     */
    public function create_job(JobParameters $job_parameters)
    {
        $config = EED_Attendee_Importer::instance()->getConfig();
        $file = new SplFileObject($config->file, 'r');
        // Get the header row
        if ($file->eof()) {
            // The file's totally empty. That's whack.
            $job_parameters->set_status(JobParameters::status_error);
            return new JobStepResponse(
                $job_parameters,
                esc_html__('No comma-separated data was retrieved from the CSV file provided.', 'event_espresso')
            );
        }
        $csv_row = $file->fgetcsv();
        $job_parameters->set_extra_data(
            [
                'headers' => $csv_row
            ]
        );
        // Now count the lines
        $file->seek(PHP_INT_MAX);
        $job_parameters->set_job_size($file->key() - 1);
        return new JobStepResponse(
            $job_parameters,
            esc_html__('Beginning import...', 'event_espresso')
        );
    }

    /**
     * Performs another step of the job
     * @param JobParameters $job_parameters
     * @param int $batch_size
     * @return JobStepResponse
     * @throws BatchRequestException
     */
    public function continue_job(JobParameters $job_parameters, $batch_size = 50)
    {



        $command_bus = LoaderFactory::getLoader()->getShared('EventEspresso\core\services\commands\CommandBus');
        // grab the line from the file
        $config = EED_Attendee_Importer::instance()->getConfig();
        $file = new SplFileObject($config->file, 'r');
        // Jump to the line we were at, plus one because we didn't count the header row
        $file->seek($job_parameters->units_processed() + 1);
        $processed_this_batch = 0;
        $column_headers = $job_parameters->extra_datum('headers');
        while (!$file->eof() && $processed_this_batch < $batch_size) {
            $numeric_csv_row = $file->fgetcsv();
            if(is_array($numeric_csv_row) && count($numeric_csv_row) === count($column_headers)) {
                $command_bus->execute(
                    new ImportCsvRowCommand(
                        array_combine(
                            $column_headers,
                            $numeric_csv_row
                        )
                    )
                );
            }
            $processed_this_batch++;
        } ;
        $job_parameters->mark_processed($processed_this_batch);
        if ($job_parameters->units_processed() >= $job_parameters->job_size()) {
            $job_parameters->set_status(JobParameters::status_complete);
        }

        return new JobStepResponse(
            $job_parameters,
            sprintf(
                esc_html__('%1$s rows imported.', 'event_espresso'),
                $batch_size
            )
        );
    }

    /**
     * Performs any clean-up logic when we know the job is completed
     * @param JobParameters $job_parameters
     * @return JobStepResponse
     * @throws BatchRequestException
     */
    public function cleanup_job(JobParameters $job_parameters)
    {
        // TODO: Update ticket and datetime sold counts.
        // But there could be a lot, so just do it for the ones actually affected.
//        EEM_Ticket::instance()->update_tickets_sold();
//        EEM_Datetime::instance()->update_sold();
        return new JobStepResponse(
            $job_parameters,
            esc_html__('Summarizing import...', 'event_espresso')
        );
    }
}
// End of file AttendeeImporterBatchJob.php
// Location: ${NAMESPACE}/AttendeeImporterBatchJob.php
