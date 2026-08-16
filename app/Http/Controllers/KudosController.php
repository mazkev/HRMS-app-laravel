<?php

namespace App\Http\Controllers;

use App\Models\PeerKudos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KudosController extends Controller
{
    /**
     * Peer Kudos Feed & Wall of Fame Leaderboard
     */
    public function index()
    {
        $feed = PeerKudos::with(['sender.department', 'receiver.department'])->latest()->paginate(15);
        $colleagues = User::where('id', '!=', Auth::id())->orderBy('name')->get();

        // Top Recognized Employees (Leaderboard)
        $topEmployees = User::where('role', 'employee')
            ->withCount('kudosReceived')
            ->orderBy('kudos_received_count', 'desc')
            ->take(5)
            ->get();

        return view('kudos.index', compact('feed', 'colleagues', 'topEmployees'));
    }

    /**
     * Send Kudos to Colleague
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id|different:' . Auth::id(),
            'badge_type' => 'required|in:team_player,problem_solver,innovator,customer_hero,leadership',
            'message' => 'required|string|min:5|max:500',
        ]);

        $kudos = PeerKudos::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->input('receiver_id'),
            'badge_type' => $request->input('badge_type'),
            'message' => $request->input('message'),
        ]);

        $receiver = User::find($request->input('receiver_id'));

        return redirect()->route('kudos.index')
            ->with('success', "Apresiasi Kudos berhasil dikirimkan ke {$receiver->name}! 🎉");
    }
}
