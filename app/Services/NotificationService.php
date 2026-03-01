<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserNotification;

class NotificationService
{
    public function sendToUser(int $userId, string $title, string $message, ?string $type = null): void
    {
        UserNotification::query()->create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ]);
    }

    public function sendToAllUsers(string $title, string $message, ?string $type = null): void
    {
        $userIds = User::query()->where('role', 'user')->pluck('id');

        $payload = [];
        foreach ($userIds as $userId) {
            $payload[] = [
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($payload)) {
            foreach (array_chunk($payload, 500) as $chunk) {
                UserNotification::query()->insert($chunk);
            }
        }
    }
}
