<?php

namespace App\Http\Controllers;
use Closure;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CashRegister;

class CashRegisterController extends Controller
{
public function index(){
    return response()->json(CashRegister::all());
}
public function show(CashRegister $cashRegister){
    return response()->json( $cashRegister);
}
public function store(Request $request){
    $request->validate([
        'status'=>'required|in:active,inactive',
    ]);
    $cashRegister=CashRegister::create(['status'=>$request->status]);
    return response()->json(['message'=>'cash register created successfully','cash_register'=>$cashRegister]);

}
public function update(Request $request,CashRegister $cash_register){
    $request->validate([
        'status'=>'required|in:active,inactive',
    ]);
    $cash_register->update(['status'=>$request->status]);
    return response()->json(['message'=>'cash register updated successfully ','cash_register'=>$cash_register]);
}
public function destroy(CashRegister $cash_register){
    $cash_register->delete();
    return response()->json(['message'=>'cash register deleted successfully']);
}

}
