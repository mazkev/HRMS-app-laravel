<?php

namespace App\Http\Controllers;

use App\Models\NotificationLog;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationGatewayController extends Controller
{
    /**
     * Admin Notification Gateway & Dispatcher Simulator
     */
    public function index()
    {
        $employees = User::where('role', 'employee')->orderBy('name')->get();
        $logs = NotificationLog::with('user')->latest()->paginate(15);

        return view('admin.notifications.index', compact('employees', 'logs'));
    }

    /**
     * Send Broadcast / Alert Simulation
     */
    public function send(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'channel' => 'required|in:whatsapp,email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $channel = $request->input('channel');
        $recipient = ($channel === 'whatsapp') ? ($user->phone ?? '08123456789') : $user->email;

        NotificationLog::create([
            'user_id' => $user->id,
            'channel' => $channel,
            'recipient' => $recipient,
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'status' => 'sent',
        ]);

        $channelLabel = ($channel === 'whatsapp') ? 'WhatsApp' : 'Email';
        return redirect()->route('admin.notifications.index')
            ->with('success', "Pesan notifikasi berhasil dikirim melalui gateway {$channelLabel} ke {$user->name} ({$recipient}).");
    }
}
