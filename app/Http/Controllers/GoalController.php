<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GoalController extends Controller
{
    
    /**
     * Display the specified goal.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $goals = Goal::where('user_id', $id)
                    ->get(['id','goal_name', 'goal_description', 'days_completed', 'current_date']);

        return response()->json($goals);
    }

    /**
     * Create a new goal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $data = $request->validate([
            'goal_name' => 'required|string',
            'goal_description' => 'required|string',
        ]);

        $data['current_date'] = Carbon::now()->format('Y-m-d');
        $data['user_id'] = $id;

        $goal = Goal::create($data);
        return response()->json(['message' => 'User created successfully'], 201);
    }


    /**
     * Check a goal.
     */
    public function checkGoal($id)
    {
        $goal = Goal::findOrFail($id);

        if ($goal->days_completed == 0) {
            $goal->days_completed += 1;
            $goal->current_date = Carbon::now()->format('Y-m-d');
            $goal->start_date = $goal->current_date;
            $goal->end_date = Carbon::parse($goal->start_date)->addDays(21);
        } elseif ($goal->days_completed > 0 && Carbon::parse($goal->current_date)->addDay(1)->isSameDay(Carbon::now())) {
            $goal->days_completed += 1;
            $goal->current_date = Carbon::now()->format('Y-m-d');
            $goal->start_date = $goal->current_date;
            $goal->end_date = Carbon::parse($goal->start_date)->addDays(21);
        } elseif ($goal->days_completed > 0 && $goal->current_date != Carbon::now()->subDay()->format('Y-m-d')) {
            $goal->days_completed = 1;
            $goal->current_date = Carbon::now()->format('Y-m-d');
            $goal->start_date = $goal->current_date;
            $goal->end_date = Carbon::parse($goal->start_date)->addDays(21);
        } elseif ($goal->days_completed > 0 && $goal->current_date == Carbon::now()->subDay()->format('Y-m-d')) {
            $goal->days_completed += 1;
            $goal->current_date = Carbon::now()->format('Y-m-d');
        }

        if ($goal->days_completed == 20) {
            $this->destroy($id);
        } else {
            $goal->save();
        }

        return response()->json($goal);
    }


    /**
     * Remove the specified goal from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $goal = Goal::findOrFail($id);
        $goal->delete();
        return response()->json(null, 204);
        
    }
}
