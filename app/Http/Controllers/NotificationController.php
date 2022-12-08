<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\NotificationEvent;
use App\Models\Notification;
use App\Library\NotificationSender;
use App\Library\NotificationSenderCustom;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{

    public function notifications(Request $request)
    {
        $recipient_id = isset($request->recipient_id) ? $request->recipient_id : user_id();

        $query = Notification::where('recipient_id', $recipient_id )->where('read_status', 1)->orderBy('created_at', 'desc');

        if ($request->component_id) {
            $query->where('component_id', $request->component_id);
        }

        $records = $query->paginate(5);
        
        return response([
            'data' => $records,
            'total' => Notification::where('recipient_id', user_id())->where('read_status', 1)->count()
        ]);
    }

    public function receivedNotifications(Request $request)
    {
        $recipient_id = isset($request->recipient_id) ? $request->recipient_id : user_id();

        $query = Notification::where([
                                'recipient_id'=> $recipient_id,
                                'read_status'=> 1,
                            ])->orderBy('created_at', 'desc');

        if ($request->component_id) {
            $query->where('component_id', $request->component_id);
        }

        $records = $query->paginate(request('per_page', config('app.per_page')));

        return response([
            'success' => true,
            'data' => $records,
            'total' => Notification::where('recipient_id', $recipient_id)->where('read_status', 1)->count()
        ]);
    }

    /**
     * notification seen
     */
    public function notificationSeen ($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response([
                'success'   => false,
                'data'      => []
            ]);
        }

        $notification->read_status = 2;
        $notification->update();

        $recipient_id = user_id();

        $records = Notification::where([
                'recipient_id'=> $recipient_id,
                'read_status'=> 1,
            ])->orderBy('created_at', 'desc')
            ->paginate(request('per_page', config('app.per_page')));

        return response([
            'success' => true,
            'data' => $records,
            'total' => Notification::where('recipient_id', $recipient_id)->where('read_status', 1)->count()
        ]);

    }

    public function fireEventTest()
    {
        $notification = Notification::find(1);
        // NotificationEvent::dispatch($notification);
        event(new NotificationEvent($notification));
    }

    /**
     * Notification sending
     */
    public function sendNotification(Request $request)
    {   
        try {
            $menuUrl = $request->menu_url;
            $buttonId = $request->button_id;
            $senderId = $request->sender_id;
            $receiverId = $request->receiver_id;
            $messageType = $request->message_type;
            /** Below variables are for only custom */
            $message = $request->message;
            $media = $request->media;
            $recipientTypes = $request->recipient_types;
            $component_id = $request->component_id;

            /** Tmp */
            // $menuUrl = 'http://localhost:8080/agri-marketing-service/market-linkage/report/buyers-information-report';
            // $menuUrl = 'http://localhost:8080/agri-marketing/crop-price-info/price-information/market-commodity-price-list';
            // $buttonId = 3;
            // $receiverId = 444;
            /** end of tmp */
            /** Custom notification test */
            // $receiverId = 444;
            // $messageType = 'custom';
            // $message = "It is test custom message";
            // $media = [1, 2, 3];
            // $recipientTypes = [1, 2];
            // $component_id = 9;
            /** end of custom notification test */
            if (isset($messageType) && strtolower($messageType) === 'custom') {
                $validationResult = $this->basicValidationCustom($message, $media, $recipientTypes, $receiverId); 
            } else {
                $validationResult = $this->basicValidation($menuUrl, $buttonId, $receiverId);
            }
            if (!$validationResult['success']) {
                Log::info("Notification sending failed. Error: {$validationResult['message']}");
                return response([
                    'success' => false,
                    'message' => $validationResult['message']
                ]);
            }
            if ($messageType === 'custom') {
                return NotificationSenderCustom::handleNotification($menuUrl, $message, $media, $recipientTypes, $receiverId, $senderId, $component_id);
            }
            Log::info("From Notification Controller");
            // $menuUrl = self::getMenuMainUri($menuUrl);
            Log::info("From Notification Controller. menu: {$menuUrl}");
            return NotificationSender::handleNotification($menuUrl, $buttonId, $receiverId, $senderId, $component_id);
        } catch (\Exception $ex) {
            Log::info("Notification sending failed. Error: {$ex->getMessage()}");
            return response([
                'success' => false,
                'message' => 'Notification sending failed.'
            ]);
        }
    }

    private function basicValidation($menuUrl, $buttonId, $receiverId) {
        if (empty($menuUrl)) {
            return [
                'success' => false,
                'message' => 'Menu url not provided.'
            ];
        }

        if (!intval($buttonId)) {
            return [
                'success' => false,
                'message' => 'Button ID not provided.'
            ];
        }

        // if (!intval($receiverId)) {
        //     return [
        //         'success' => false,
        //         'message' => 'Receiver ID not provided.'
        //     ];
        // }

        return [
            'success' => true
        ];
    }

    private static function basicValidationCustom($message, $media, $recipientTypes, $receiverId)
    {
        if (empty($message)) {
            return [
                'success' => false,
                'message' => 'Message is not provided'
            ];
        }

        if (empty($media)) {
            return [
                'success' => false,
                'message' => 'Media is not defined'
            ]; 
        }

        if (count($media) === 0) {
            return [
                'success' => false,
                'message' => 'Media is not provided'
            ];
        }

        if (empty($recipientTypes)) {
            return [
                'success' => false,
                'message' => 'Recipient types are not provided'
            ];
        }

        if (count($recipientTypes) === 0) {
            return [
                'success' => false,
                'message' => 'Recipient types are not provided'
            ];
        }

        // if (!intval($receiverId)) {
        //     return [
        //         'success' => false,
        //         'message' => 'Receiver ID not provided.'
        //     ];
        // }

        return [
            'success' => true
        ];
    }

    private static function getMenuMainUri($menuUrl)
    {
        $lastPos = 0;
        $i = 1;
        $uri = '';

        /** this loop will find the third position of / and assign the uri from that position */
        while (($lastPos = strpos($menuUrl, '/', $lastPos))!== false) {
            if ($i === 3) {
                $uri = substr($menuUrl, $lastPos + 1);
                break;
            }

            $lastPos = $lastPos + strlen('/');
            $i++;
        }

        /** This condition will remove the uri query param part */
        if (($qMarkPos = strpos($uri, '?', 0)) !== false) {
            $uri = substr($uri, 0, $qMarkPos);
        }

        return '/' . $uri;
    }
}
