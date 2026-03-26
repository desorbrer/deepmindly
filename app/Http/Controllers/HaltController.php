<?php

namespace App\Http\Controllers;

use App\Models\HaltEntry;
use Illuminate\Http\Request;

class HaltController extends Controller
{

    public function index()
    {
        $entries = HaltEntry::latest()->take(10)->get();

        $avg = HaltEntry::selectRaw('
            AVG(hungry) as hungry,
            AVG(angry)  as angry,
            AVG(lonely) as lonely,
            AVG(tired)  as tired
        ')->first();

        return view('halt-tracker', [
            'avgHungry'     => round($avg->hungry ?? 0),
            'avgAngry'      => round($avg->angry  ?? 0),
            'avgLonely'     => round($avg->lonely ?? 0),
            'avgTired'      => round($avg->tired  ?? 0),
            'totalCheckins' => HaltEntry::count(),
            'recentEntries' => $entries,
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'hungry' => 'required|integer|min:0|max:100',
            'angry'  => 'required|integer|min:0|max:100',
            'lonely' => 'required|integer|min:0|max:100',
            'tired'  => 'required|integer|min:0|max:100',
        ]);

        HaltEntry::create($data);

        return redirect()->back()->with('success', 'Check-in saved!');
    }
}
