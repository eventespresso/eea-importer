<?php
namespace EventEspresso\AttendeeImporter\core\libraries\batch\JobHandlers;
use EEM_Ticket;
use EventEspressoBatchRequest\Helpers\BatchRequestException;
use EventEspressoBatchRequest\Helpers\JobParameters;
use EventEspressoBatchRequest\Helpers\JobStepResponse;
use EventEspressoBatchRequest\JobHandlerBaseClasses\JobHandler;

/**
 * Class AttendeeImporterBatchJob
 *
 * Description
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
     * @throws BatchRequestException
     * @return JobStepResponse
     */
    public function create_job(JobParameters $job_parameters)
    {
        // The CSV file was already uploaded, so we can just keep using it.
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
        // TODO: Use commands to...
        // Create an attendee
        // Create a transaction
        // Get a ticket
        // Get an event
        // Create a registration
        // Create answers
        // Create a registration-answer row
        // Create line items
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
