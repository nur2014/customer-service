<?php
namespace App\Library;

use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class WebNotification
{
    public static function sendNotification($menuUrl, $message, $recipientId, $senderId, $component_id)
    {
        Log::info("Web notification started.");
        Log::info("message: {$message}, recipientId: {$recipientId} senderId: {$senderId}");
        return self::saveNotification($menuUrl, $message, $recipientId, $senderId, $component_id);
    }

    private static function saveNotification($menuUrl, $message, $recipientId, $senderId, $component_id)
    {
        try {
            
            $notificationModel = new Notification();

            $notificationModel->message = $message;
            $notificationModel->recipient_id = $recipientId;
            $notificationModel->sender_id = (int)$senderId;
            $notificationModel->url = $menuUrl;
            $notificationModel->component_id = $component_id;
            $notificationModel->read_status = 1;

            $notificationModel->save();
            return $notificationModel;

        } catch (\Exception $ex) {
            Log::info("Failed to save notification in db. Error: {$ex->getMessage()}");

            return [
                'success' => false,
                'message' => "Failed to save notification."
            ];
        }
    }
}
