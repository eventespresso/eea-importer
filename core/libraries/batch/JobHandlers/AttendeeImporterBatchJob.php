<?php

namespace EventEspresso\AttendeeImporter\core\libraries\batch\JobHandlers;

use EED_Attendee_Importer;
use EventEspresso\core\services\commands\attendee\ImportCsvRowCommand;
use EventEspresso\core\services\loaders\LoaderFactory;
use EventEspressoBatchRequest\Helpers\BatchRequestException;
use EventEspressoBatchRequest\Helpers\JobParameters;
use EventEspressoBatchRequest\Helpers\JobStepResponse;
use EventEspressoBatchRequest\JobHandlerBaseClasses\JobHandler;
use EventEspressoBatchRequest\JobHandlerBaseClasses\JobHandlerInterface;
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
        // The CSV file was already uploaded, so just count its lines.
        $config = EED_Attendee_Importer::instance()->getConfig();
        $file = new SplFileObject($config->file, 'r');
        $file->seek(PHP_INT_MAX);
        $job_parameters->set_job_size($file->key() + 1);
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


        $job_parameters->mark_processed($batch_size);
        if ($job_parameters->units_processed() >= $job_parameters->job_size()) {
            $job_parameters->set_status(JobParameters::status_complete);
        }
        $command_bus = LoaderFactory::getLoader()->getShared('EventEspresso\core\services\commands\CommandBus');
        // grab the line from the file
        $config = EED_Attendee_Importer::instance()->getConfig();
        $file = new SplFileObject($config->file, 'r');
        $file->seek($job_parameters->units_processed());
        $processed_this_batch = 0;
        do {
            $csv_row = $file->fgetcsv();
            $command_bus->execute(
                new ImportCsvRowCommand(
                    $csv_row,
                    $config
                )
            );
        } while (!$file->eof() && $processed_this_batch++ <= $batch_size);

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
    }
}
// End of file AttendeeImporterBatchJob.php
// Location: ${NAMESPACE}/AttendeeImporterBatchJob.php
