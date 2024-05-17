<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.plans', [
            'plans' => Plan::get(),
            'user_project' => Auth::user()->selectedProject ?? false
        ]);
    }

    public function list()
    {
        return view('pages.pricing', [
            'plans' => Plan::get(),
            'user_project' => Auth::user()->selectedProject ?? false
        ]);
    }

    public function show(Plan $plan)
    {
        $intent = auth()->user()->selectedProject->createSetupIntent();

        return view("user.subscriptions", compact("plan", "intent"));
    }

    public function subscription(Request $request, PlanService $planService)
    {
        $planService->subscribe($request);
        return redirect(route('user.plans'))->with('success', __('messages.plan_subscribed'));
    }

    public function cancelPlan(PlanService $planService)
    {
        $planService->cancel(Auth::user()->selectedProject);
        return back()->with('success', __('messages.plan_canceled'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
