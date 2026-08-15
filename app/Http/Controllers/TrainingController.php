<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingController extends Controller
{
    /**
     * Admin Training Manager
     */
    public function adminIndex()
    {
        $trainings = Training::withCount('participants')->latest()->paginate(10);
        return view('admin.trainings.index', compact('trainings'));
    }

    /**
     * Admin Store Training
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'trainer_name' => 'required|string|max:100',
            'category' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'description' => 'required|string',
        ]);

        Training::create($request->all());

        return redirect()->route('admin.trainings.index')
            ->with('success', 'Program pelatihan baru berhasil dipublikasikan.');
    }

    /**
     * Employee Training Catalog & Enrolled List
     */
    public function employeeIndex()
    {
        $user = Auth::user();
        $availableTrainings = Training::where('status', '!=', 'completed')
            ->withCount('participants')
            ->latest()
            ->get();

        $myTrainings = TrainingParticipant::with('training')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('employee.trainings.index', compact('availableTrainings', 'myTrainings', 'user'));
    }

    /**
     * Employee Self-Enroll in Training
     */
    public function enroll(Request $request, $id)
    {
        $training = Training::withCount('participants')->findOrFail($id);

        if ($training->participants_count >= $training->capacity) {
            return back()->with('error', 'Kuota peserta untuk pelatihan ini sudah penuh.');
        }

        $existing = TrainingParticipant::where('training_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah terdaftar dalam pelatihan ini.');
        }

        TrainingParticipant::create([
            'training_id' => $id,
            'user_id' => Auth::id(),
            'status' => 'enrolled',
        ]);

        return back()->with('success', "Berhasil mendaftar pada program pelatihan: {$training->title}.");
    }
}
