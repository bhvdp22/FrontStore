<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $type
 * @property string $title
 * @property string $message
 * @property string $icon
 * @property string|null $action_url
 * @property string $recipient_type
 * @property int $recipient_id
 * @property bool $is_read
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Notification extends Model
{
    protected $fillable = [
        'type', 'title', 'message', 'icon', 'action_url',
        'recipient_type', 'recipient_id',
        'is_read', 'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Scope: get notifications for a seller
     */
    public function scopeForSeller($query, int $sellerId)
    {
        return $query->where('recipient_type', 'seller')
                     ->where('recipient_id', $sellerId);
    }

    /**
     * Scope: get notifications for admin
     */
    public function scopeForAdmin($query, int $adminId)
    {
        return $query->where('recipient_type', 'admin')
                     ->where('recipient_id', $adminId);
    }

    /**
     * Scope: get all admin notifications (for any admin)
     */
    public function scopeForAllAdmins($query)
    {
        return $query->where('recipient_type', 'admin');
    }

    /**
     * Scope: unread only
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Mark this notification as read
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
