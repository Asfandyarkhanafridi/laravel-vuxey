<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SettingController extends Controller
{
    public function edit()
    {
        $settingDate = session('selected_date');

        return view('setting.edit', compact('settingDate'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'selected_date' => 'required|date',
        ]);

        session(['selected_date' => $validatedData['selected_date']]);

        return redirect()->back()->with('message', 'Selected date updated successfully.');
    }

}
