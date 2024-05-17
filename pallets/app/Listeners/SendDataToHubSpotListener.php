<?php

namespace App\Listeners;

use App\Events\HubSpotProcessedEvent;
use Exception;
use HubSpot\Client\Crm\Associations\Model\BatchInputPublicAssociation;
use HubSpot\Client\Crm\Contacts\Model\SimplePublicObjectInputForCreate;
use HubSpot\Factory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use function PHPUnit\Framework\exactly;

class SendDataToHubSpotListener
{
    /**
     * Handle the event.
     */
    public function handle(HubSpotProcessedEvent $event): void
    {
        $lead = $event->lead;
        $leadProductConfiguration = $event->leadProductConfiguration;

        $hubspot = Factory::createWithAccessToken(config('services.hubspot.api_key'));

        try {
            $newContactInputAssociation = new SimplePublicObjectInputForCreate([
                'properties' => [
                    'email' => $lead->email,
                    'firstname' => $lead->first_name,
                    'lastname' => $lead->last_name,
                    'phone' => $lead->phone,
                    'company' => $lead->company,
                    'jobtitle' => $lead->job_title,
                    ## Need to ask if the contacts has that properties
//                [
//                    'local_distributor' => $lead->local_distributor
//                ],
                ]
            ]);

            $createdContact = $hubspot->crm()->contacts()->basicApi()->create($newContactInputAssociation);
        } catch (Exception $e) {
            $callback = json_decode($e->getResponseObject());
            if ($callback->category === 'CONFLICT') {
                $messageParts = explode(' ', $callback->message);
                $existingId = end($messageParts);
            }
        }

        $contactId = $existingId ?? $createdContact['id'];
        $lead->update(['hs_contact_id' => $contactId]);
        $customObjectId = $this->createPalletizerCustomObject($hubspot, $leadProductConfiguration);

        $hubspot->crm()->associations()->v4()->basicApi()->createDefault(
            config('services.hubspot.palletizer_custom_object_id'),
            $customObjectId,
            '0-1',
            $contactId
        );
    }

    public function createPalletizerCustomObject($hubspot, $leadProductConfiguration)
    {
        $customObjectService = $hubspot->crm()->objects()->basicApi();
        $newCustomObject = [
            'properties' => [
                'lead_id' => $leadProductConfiguration->id,
                'gripper_id' => $leadProductConfiguration->tool->name,
                'product_infeed_id' => $leadProductConfiguration->replacementInfeedPosition?->id ?? $leadProductConfiguration->infeedPosition->id,
                'left_pallet_position_id' => $leadProductConfiguration->replacementLeftPosition?->name ?? $leadProductConfiguration->leftPosition->name,
                'right_pallet_position_id' => $leadProductConfiguration->replacementRightPosition?->name ?? $leadProductConfiguration->rightPosition->name,
                'system_pallet_height' => intval($leadProductConfiguration->system_pallet_height),
                'product_name' => $leadProductConfiguration->product_name,
                'product_type_id' => intval($leadProductConfiguration->productType->name),
                'product_length' => intval($leadProductConfiguration->product_length),
                'product_width' => intval($leadProductConfiguration->product_width),
                'product_height' => intval($leadProductConfiguration->product_height),
                'product_infeed_rate' => $leadProductConfiguration->product_infeed_rate,
                'pallet_length' => intval($leadProductConfiguration->pallet_length),
                'pallet_width' => intval($leadProductConfiguration->pallet_width),
                'pallet_height' => intval($leadProductConfiguration->pallet_height),
                'robot_id' => $leadProductConfiguration->robot->name,
                'request_customization' => $leadProductConfiguration->request_customization,
                'total_price' => $leadProductConfiguration->total_price
            ]
        ];

        $createdCustomObject = $customObjectService->create(config('services.hubspot.palletizer_custom_object_id'), $newCustomObject);
        $leadProductConfiguration->update(['hs_custom_object_palletizer_id' => $createdCustomObject['id']]);

        return $createdCustomObject['id'];
    }
}
