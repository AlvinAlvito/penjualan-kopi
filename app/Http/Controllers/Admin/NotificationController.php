<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService) {}

    public function index()
    {
        $notifications = UserNotification::query()->with('user')->latest()->paginate(20);
        $users = User::query()->where('role', 'user')->orderBy('name')->get();

        return view('admin.notifications.index', compact('notifications', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'type' => ['nullable', 'string', 'max:50'],
            'target' => ['required', 'in:all,single'],
            'user_id' => ['nullable', 'required_if:target,single', 'integer', 'exists:users,id'],
        ]);

        if ($data['target'] === 'all') {
            $this->notificationService->sendToAllUsers($data['title'], $data['message'], $data['type'] ?? null);
        } else {
            $this->notificationService->sendToUser((int) $data['user_id'], $data['title'], $data['message'], $data['type'] ?? null);
        }

        return back()->with('success', 'Notifikasi berhasil dikirim.');
    }
}
