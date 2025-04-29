<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Copy;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActionController extends Controller
{

    /**
     * Action Controller 
     * 
     * make return or borrow 
     * 
     */
    public function handle(Request $request, string $action)
    {
        return match ($action) {
            'borrow' => $this->borrow($request),
            'return' => $this->returnCopy($request),
            default => response()->json(['message' => 'Invalid action'], 400),
        };
    }

    public function borrow(Request $request)
    {
        $validated = $request->validate([
            'copy_id' => 'required|exists:copies,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $copy = Copy::findOrFail($validated['copy_id']);

        if ($copy->availability !== 'available') {
            return response()->json(['message' => 'Copy not available.'], 422);
        }

        $borrowing = Borrowing::create([
            'user_id' => $validated['user_id'],
            'copy_id' => $copy->id,
            'borrowed_at' => now(),
            'due_date' => Carbon::now()->addDays(14),
        ]);

        $copy->update(['availability' => 'borrowed']);

        return response()->json(['message' => 'Book borrowed.', 'borrowing' => $borrowing]);
    }

    public function returnCopy(Request $request)
    {
        $validated = $request->validate([
            'borrowing_id' => 'required|exists:borrowings,id',
        ]);

        $borrowing = Borrowing::findOrFail($validated['borrowing_id']);

        $borrowing->update(['returned_at' => now()]);
        $borrowing->copy->update(['availability' => 'available']);

        return response()->json(['message' => 'Book returned.']);
    }
}
