<?php

namespace App\Http\Controllers;

use App\Models\Bonus;
use App\Models\User;
use Illuminate\Http\Request;

class BonusController extends Controller
{
    // Show all bonuses
    public function index()
    {
        $bonuses = Bonus::with('user')->get(); // Load related user data
        return view('admin.bonus.index', compact('bonuses'));
    }

    // Show form to create new bonus
    public function create()
    {
        $users = User::all(); // Get all users for the dropdown
        return view('admin.bonus.create', compact('users'));
    }

    // Store new bonus
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure the user exists
            'name' => 'required|string|max:255',
            'bonus' => 'required|numeric|between:0,999.99',
            'given_date' => 'required|date', // Ensure the given_date is a valid date
        ]);

        Bonus::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'bonus' => $request->bonus,
            'given_date' => $request->given_date,
        ]);

        return redirect()->route('admin.bonuses.index')->with('success', 'Bonus qo‘shildi');
    }

    // Show one bonus
    // public function show(Bonus $bonus)
    // {
    //     return view('admin.bonus.show', compact('bonus'));
    // }

    // Show edit form
    public function edit(Bonus $bonus)
    {
        $users = User::all(); // Get all users for the dropdown
        return view('admin.bonus.edit', compact('bonus', 'users'));
    }

    // Update bonus
    public function update(Request $request, Bonus $bonus)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure the user exists
            'name' => 'required|string|max:255',
            'bonus' => 'required|numeric|between:0,999.99',
            'given_date' => 'required|date', // Ensure the given_date is a valid date
        ]);

        $bonus->update([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'bonus' => $request->bonus,
            'given_date' => $request->given_date,
        ]);

        return redirect()->route('admin.bonuses.index')->with('success', 'Bonus yangilandi');
    }

    // Delete bonus
    public function destroy(Bonus $bonus)
    {
        $bonus->delete();
        return redirect()->route('admin.bonuses.index')->with('success', 'Bonus o‘chirildi');
    }
}
