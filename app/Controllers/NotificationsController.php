<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\Notification;

class NotificationsController extends BaseController
{
    public function index()
{
    $notificationModel = new Notification();
    $userId = $this->session->get('user_id');
    $notifications = $notificationModel->getUserNotifications($userId);
    
    return view('notification/index', ['notifications' => $notifications]);
}

public function markAsRead($notificationId)
    {
        $notificationModel = new Notification();
        $notificationModel->markNotificationAsRead($notificationId);
        return redirect()->to(base_url('reservations'));
    }

}
