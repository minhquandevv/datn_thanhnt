<?php

namespace App\Http\Controllers\public;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Worker;

class HomeController extends Controller
{
    public function index()
    {
        $workers = Worker::where('status', 'open')->orderBy('created_at', 'desc')->get();
        return view('public.dashboard', compact('workers'));
    }

    public function show($id)
    {
        $workers = Worker::findOrFail($id);
        return view('public.show', compact('workers'));
    }

}
