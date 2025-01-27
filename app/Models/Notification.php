<?php

namespace App\Models;

use CodeIgniter\Model;

class Notification extends Model
{
    protected $table = 'notification';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'message',
        'status',
        'reservation_id'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function getUserNotifications($userId)
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function countUnreadNotifications($userId)
    {
        return $this->where('user_id', $userId)
            ->where('status', 'unread') 
            ->countAllResults();
    }

    public function notifyAdmin($adminId, $message, $reservationId)
{
    $data = [
        'user_id' => $adminId,
        'message' => $message,
        'status' => 'unread', 
        'reservation_id' => $reservationId 
    ];

    return $this->insert($data);
}


public function markNotificationAsRead($notificationId)
    {
        $this->where('id', $notificationId)
             ->set(['status' => 'read'])
             ->update();
    }


}
