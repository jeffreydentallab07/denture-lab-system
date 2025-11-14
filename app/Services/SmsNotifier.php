<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\CaseOrder;
use App\Models\Pickup;
use App\Models\Delivery;
use App\Models\Appointment;

class SmsNotifier
{
    protected $client;
    protected $apiToken;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiToken = config('services.iprogtech.api_token');
        $this->apiUrl = config('services.iprogtech.api_url');

        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'timeout' => 30,
        ]);
    }

    /**
     * Send SMS notification using iprogtech API
     */
    public function sendSms(string $to, string $message): bool
    {
        try {
            $formattedPhone = $this->formatPhoneNumber($to);

            Log::info('Attempting to send SMS via iprogtech', [
                'to' => $formattedPhone,
                'message_length' => strlen($message),
                'api_url' => $this->apiUrl . '/sms_messages'
            ]);

            $response = $this->client->post('sms_messages', [
                'form_params' => [
                    'api_token' => $this->apiToken,
                    'phone_number' => $formattedPhone,
                    'message' => $message,
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            Log::info('iprogtech SMS sent successfully', [
                'to' => $formattedPhone,
                'response' => $result
            ]);

            return true;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 'N/A';
            $responseBody = $response ? $response->getBody()->getContents() : 'No response body';

            Log::error('iprogtech API Client Error', [
                'to' => $to,
                'status_code' => $statusCode,
                'error' => $e->getMessage(),
                'response_body' => $responseBody
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Failed to send iprogtech SMS - General Error', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Format phone number
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If starts with 0, replace with 63
        if (substr($phone, 0, 1) === '0') {
            $phone = '63' . substr($phone, 1);
        }

        // If starts with +63, remove the +
        if (substr($phone, 0, 3) === '+63') {
            $phone = substr($phone, 1);
        }

        // If doesn't start with 63, add it
        if (substr($phone, 0, 2) !== '63') {
            $phone = '63' . $phone;
        }

        return $phone;
    }

    /**
     * Notify clinic about pickup scheduled
     */
    public function notifyPickupScheduled(Pickup $pickup): bool
    {
        $caseOrder = $pickup->caseOrder;
        $clinic = $caseOrder->clinic;

        if (empty($clinic->contact_number)) {
            Log::warning('SMS not sent: Clinic has no contact number', [
                'clinic_id' => $clinic->clinic_id,
                'pickup_id' => $pickup->pickup_id
            ]);
            return false;
        }

        $caseNo = 'CASE-' . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT);
        $pickupDate = \Carbon\Carbon::parse($pickup->pickup_date)->format('M d, Y');

        $message = "Jeffrey Dental Lab\n\n";
        $message .= "Pickup Scheduled!\n\n";
        $message .= "Case: {$caseNo}\n";
        $message .= "Patient: {$caseOrder->patient->name}\n";
        $message .= "Pickup Date: {$pickupDate}\n";
        $message .= "Address: {$pickup->pickup_address}\n\n";
        $message .= "Our rider will arrive on the scheduled date. Thank you!";

        return $this->sendSms($clinic->contact_number, $message);
    }

    /**
     * Notify clinic about pickup completed
     */
    public function notifyPickupCompleted(Pickup $pickup): bool
    {
        $caseOrder = $pickup->caseOrder;
        $clinic = $caseOrder->clinic;

        if (empty($clinic->contact_number)) {
            return false;
        }

        $caseNo = 'CASE-' . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT);

        $message = "Jeffrey Dental Lab\n\n";
        $message .= "Pickup Confirmed!\n\n";
        $message .= "Case: {$caseNo}\n";
        $message .= "Your case has been successfully picked up and is now on its way to our laboratory.\n\n";
        $message .= "We'll notify you once work begins!";

        return $this->sendSms($clinic->contact_number, $message);
    }

    /**
     * Notify clinic about appointment scheduled
     */
    public function notifyAppointmentScheduled(Appointment $appointment): bool
    {
        $caseOrder = $appointment->caseOrder;
        $clinic = $caseOrder->clinic;

        if (empty($clinic->contact_number)) {
            return false;
        }

        $caseNo = 'CASE-' . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT);
        $technicianName = $appointment->technician->name ?? 'Our technician';
        $estimatedDate = $appointment->estimated_date->format('M d, Y');

        $message = "Jeffrey Dental Lab\n\n";
        $message .= "Work Scheduled!\n\n";
        $message .= "Case: {$caseNo}\n";
        $message .= "Technician: {$technicianName}\n";
        $message .= "Estimated Completion: {$estimatedDate}\n\n";
        $message .= "Your case is now assigned and will be completed soon!";

        return $this->sendSms($clinic->contact_number, $message);
    }

    /**
     * Notify clinic when work starts
     */
    public function notifyWorkStarted(Appointment $appointment): bool
    {
        $caseOrder = $appointment->caseOrder;
        $clinic = $caseOrder->clinic;

        if (empty($clinic->contact_number)) {
            return false;
        }

        $caseNo = 'CASE-' . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT);

        $message = "Jeffrey Dental Lab\n\n";
        $message .= "Work In Progress!\n\n";
        $message .= "Case: {$caseNo}\n";
        $message .= "Our technician has started working on your case.\n\n";
        $message .= "We'll update you once completed!";

        return $this->sendSms($clinic->contact_number, $message);
    }

    /**
     * Notify clinic and patient when work is completed
     */
    public function notifyWorkCompleted(Appointment $appointment): array
    {
        $caseOrder = $appointment->caseOrder;
        $clinic = $caseOrder->clinic;
        $patient = $caseOrder->patient;

        $results = ['clinic' => false, 'patient' => false];

        $caseNo = 'CASE-' . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT);

        // Notify clinic
        if (!empty($clinic->contact_number)) {
            $message = "Jeffrey Dental Lab\n\n";
            $message .= "Work Completed!\n\n";
            $message .= "Case: {$caseNo}\n";
            $message .= "Patient: {$patient->name}\n";
            $message .= "Your case has been completed and is ready for delivery.\n\n";
            $message .= "We'll arrange delivery soon!";

            $results['clinic'] = $this->sendSms($clinic->contact_number, $message);
        }

        // Notify patient
        if (!empty($patient->contact_number)) {
            $message = "Jeffrey Dental Lab\n\n";
            $message .= "Good news!\n\n";
            $message .= "Your dental case has been completed and will be delivered to your clinic soon.\n\n";
            $message .= "Clinic: {$clinic->clinic_name}\n";
            $message .= "Thank you for your patience!";

            $results['patient'] = $this->sendSms($patient->contact_number, $message);
        }

        return $results;
    }

    /**
     * Notify clinic and patient about delivery scheduled
     */
    public function notifyDeliveryScheduled(Delivery $delivery): array
    {
        $appointment = $delivery->appointment;
        $caseOrder = $appointment->caseOrder;
        $clinic = $caseOrder->clinic;
        $patient = $caseOrder->patient;

        $results = ['clinic' => false, 'patient' => false];

        $caseNo = 'CASE-' . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT);
        $deliveryDate = \Carbon\Carbon::parse($delivery->delivery_date)->format('M d, Y');

        // Notify clinic
        if (!empty($clinic->contact_number)) {
            $message = "Jeffrey Dental Lab\n\n";
            $message .= "Delivery Scheduled!\n\n";
            $message .= "Case: {$caseNo}\n";
            $message .= "Delivery Date: {$deliveryDate}\n";
            $message .= "Address: {$clinic->address}\n\n";
            $message .= "Our rider will deliver your completed case on the scheduled date!";

            $results['clinic'] = $this->sendSms($clinic->contact_number, $message);
        }

        // Notify patient
        if (!empty($patient->contact_number)) {
            $message = "Jeffrey Dental Lab\n\n";
            $message .= "Delivery Scheduled!\n\n";
            $message .= "Your dental case will be delivered to your clinic on {$deliveryDate}.\n\n";
            $message .= "Clinic: {$clinic->clinic_name}\n";
            $message .= "You'll be notified when delivered!";

            $results['patient'] = $this->sendSms($patient->contact_number, $message);
        }

        return $results;
    }

    /**
     * Notify clinic and patient when delivery is in transit
     */
    public function notifyDeliveryInTransit(Delivery $delivery): array
    {
        $appointment = $delivery->appointment;
        $caseOrder = $appointment->caseOrder;
        $clinic = $caseOrder->clinic;
        $patient = $caseOrder->patient;

        $results = ['clinic' => false, 'patient' => false];

        $caseNo = 'CASE-' . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT);

        // Notify clinic
        if (!empty($clinic->contact_number)) {
            $message = "Jeffrey Dental Lab\n\n";
            $message .= "Out for Delivery!\n\n";
            $message .= "Case: {$caseNo}\n";
            $message .= "Your case is now on its way and will arrive soon!\n\n";
            $message .= "Thank you for your patience!";

            $results['clinic'] = $this->sendSms($clinic->contact_number, $message);
        }

        // Notify patient
        if (!empty($patient->contact_number)) {
            $message = "Jeffrey Dental Lab\n\n";
            $message .= "Out for Delivery!\n\n";
            $message .= "Your dental case is on its way to your clinic.\n\n";
            $message .= "Clinic: {$clinic->clinic_name}\n";
            $message .= "You'll be notified once delivered!";

            $results['patient'] = $this->sendSms($patient->contact_number, $message);
        }

        return $results;
    }

    /**
     * Notify clinic and patient when delivery is completed
     */
    public function notifyDeliveryCompleted(Delivery $delivery): array
    {
        $appointment = $delivery->appointment;
        $caseOrder = $appointment->caseOrder;
        $clinic = $caseOrder->clinic;
        $patient = $caseOrder->patient;

        $results = ['clinic' => false, 'patient' => false];

        $caseNo = 'CASE-' . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT);

        // Notify clinic
        if (!empty($clinic->contact_number)) {
            $message = "Jeffrey Dental Lab\n\n";
            $message .= "Delivery Completed!\n\n";
            $message .= "Case: {$caseNo}\n";
            $message .= "Patient: {$patient->name}\n";
            $message .= "Your case has been successfully delivered.\n\n";
            $message .= "Please review and approve or request adjustments. Thank you!";

            $results['clinic'] = $this->sendSms($clinic->contact_number, $message);
        }

        // Notify patient
        if (!empty($patient->contact_number)) {
            $message = "Jeffrey Dental Lab\n\n";
            $message .= "Delivery Completed!\n\n";
            $message .= "Your dental case has been delivered to your clinic.\n\n";
            $message .= "Clinic: {$clinic->clinic_name}\n";
            $message .= "Thank you for choosing Jeffrey Dental Lab!";

            $results['patient'] = $this->sendSms($patient->contact_number, $message);
        }

        return $results;
    }

    /**
     * Notify technician when case is approved
     */
    public function notifyCaseApproved(CaseOrder $caseOrder, $technicianId): bool
    {
        $technician = \App\Models\User::find($technicianId);

        if (!$technician || empty($technician->contact_number)) {
            return false;
        }

        $caseNo = 'CASE-' . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT);

        $message = "Jeffrey Dental Lab\n\n";
        $message .= "Case Approved!\n\n";
        $message .= "Case: {$caseNo}\n";
        $message .= "Clinic: {$caseOrder->clinic->clinic_name}\n";
        $message .= "Your work has been approved by the clinic. Great job!\n\n";
        $message .= "Keep up the excellent work!";

        return $this->sendSms($technician->contact_number, $message);
    }

    /**
     * Notify about adjustment requested
     */
    public function notifyAdjustmentRequested(CaseOrder $caseOrder, $technicianId): bool
    {
        $technician = \App\Models\User::find($technicianId);

        if (!$technician || empty($technician->contact_number)) {
            return false;
        }

        $caseNo = 'CASE-' . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT);

        $message = "Jeffrey Dental Lab\n\n";
        $message .= "Adjustment Requested\n\n";
        $message .= "Case: {$caseNo}\n";
        $message .= "Clinic: {$caseOrder->clinic->clinic_name}\n";
        $message .= "The clinic has requested adjustments. Please check the system for details.\n\n";
        $message .= "A new pickup will be scheduled.";

        return $this->sendSms($technician->contact_number, $message);
    }
}
