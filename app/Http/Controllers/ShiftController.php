<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function index()
    {
        return response()->json(Shift::with('user')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'cash_register_id' => 'required|exists:cash_registers,id',
            'start_time' => 'required|date_format:Y-m-d H:i:s'
        ]);

        $shift = Shift::create([
            'user_id' => Auth::id(),
            'cash_register_id' => $request->cash_register_id,
            'start_time' => $request->start_time
        ]);

        return response()->json(['message' => 'Shift started successfully', 'shift' => $shift]);
    }

    public function update(Request $request, Shift $shift)
    {
        $request->validate(['end_time' => 'required|date_format:Y-m-d H:i:s']);
        $shift->update(['end_time' => $request->end_time]);
        return response()->json(['message' => 'Shift ended', 'shift' => $shift]);
    }
}
