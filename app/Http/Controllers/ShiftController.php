<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Shift;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param $start_time
     * @param $end_time
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $this->authorize('list_shifts', Shift::class);
        if( $request->start_time ) {
           $start = Carbon::parse($request['start_time'])->toDateTimeString();
        }
        else {
            $start = Carbon::now()->toDateTimeString();

        }
        if( $request->end_time ) {
           $end = Carbon::parse($request['end_time'])->toDateTimeString();
        }
        
       if(Auth::User()->role === 'manager') {       
            $shifts = Shift::where('start_time', '>=', $start)
            ->orderBy('start_time', 'asc');
            if( $request->end_time ) {
                $end = Carbon::parse($request['end_time'])->toDateTimeString();
                $shifts->where('end_time', '<=', $end);
            }
        }
        else if (Auth::User()->role === 'employee') {
            $shifts = Auth::User()->shifts()->with('manager')->where('start_time', '>=', $start)
            ->orderBy('start_time', 'asc');
            if( $request->end_time ) {
                $end = Carbon::parse($request['end_time'])->toDateTimeString();
                $shifts->where('end_time', '<=', $end);
            }
        }
        return $shifts->get();
    }

    /**
     * Display a listing of the employees working with user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
     public function get_shiftmates(Request $request) {
        
        $shifts =  Auth::User()->shifts()->get();
        $employees = [];
        foreach($shifts as $shift) {
            $matching_shifts = Shift::where('start_time', '>=', $shift->start_time)
            ->where('end_time', '<=', $shift->end_time)->get();
            foreach($matching_shifts as $shift) {
                if (!in_array($shift->employee, $employees) && $shift->employee->id !== Auth::id())
                {
                    $employees[] = $shift->employee; 
                }
            }
        }
        return $employees;

     }

     /**
     * Display a listing of the hours worked by the user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
     public function get_hours(Request $request) {
        
        $hours = 0;
         if( $request->week_start ) {
            $start = Carbon::parse($request['week_start']);
         }
         else {
             $start = Carbon::now();
         }

        
         $start->hour = 0;
         $start->minute = 0;

        $shifts = Auth::User()->shifts()
        ->where('start_time', '>=', $start->toDateTimeString())
        ->where('end_time', '<=', $start->addWeek()->toDateTimeString())->get();
         
        foreach($shifts as $shift) {
            $hours = $hours + $shift->start_time->diffInHours($shift->end_time, false); 
        }

         return $hours;

     }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'exists:users,id',
            'break' => 'numberic|min:0',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        // $role = Role::where('name', $request['role'])->first();

        $shift = Shift::create([
            'manager_id' => Auth::id(),
            'break' => $request['break'],
            'start_time' => Carbon::parse($request['start_time'])->toDateTimeString(),
            'end_time' => Carbon::parse($request['end_time'])->toDateTimeString(),
        ]);

        if( $request['employee_id']) {
            $employee = User::where('id', $request['employee_id'])->first();
            $employee->shifts()->save($shift);
        }

        return $shift;
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shift $shift)
    {
        $this->validate($request, [
            'employee_id' => 'exists_or_zero:users,id',
            'break' => 'numberic|min:0',
            'start_time' => 'date|after:now',
            'end_time' => 'date|after:start_time',
        ]);
        if( $request['break']) {
            $shift->break = $request['break'];
        }
        if( $request['start_time']) {
            $shift->start_time = Carbon::parse($request['start_time'])->toDateTimeString();
        }
        if( $request['end_time']) {
            $shift->end_time = Carbon::parse($request['end_time'])->toDateTimeString();
        }
        if( $request['employee_id'] !== null ) {
            if( $request['employee_id'] === 0 ) {
                $shift->employee_id = null;
            }
            else {
                $employee = User::where('id', $request['employee_id'])->first();
                $employee->shifts()->save($shift);
            }
        }
        $shift->save();
        return $shift;
    }

}
