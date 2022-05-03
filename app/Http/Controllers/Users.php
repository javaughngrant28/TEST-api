<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Expense;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Users extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([

            'name'=>'required||min:8',
            'email'=>'required|email',
            'password'=>'required|min:5',
            'confirm_password'=>'required|same:password',
            'in_queue'=>'boolean',

        ]);


        $users = new User;
        $users->name = $request->input('name');
        $users->email = $request->input('email');
        $users->password = Hash::make($request->input('password'));

        if($request->input('in_queue'))
        {
            $users->in_queue = $request->input('in_queue');
        }

        $users->save();


        return response()->json(['message'=>'user was created', 'users'=>$users]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $users
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:5',
        ]);



        if(!$user = User::where('email', $request->input('email'))->first() or !Hash::check($request->input('password'), $user->password))
        {
            return response()->json(['message'=>'incorrect information']);
        }else{


            return response()->json(['message'=>'correct information', 'users'=>$user]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'name'=> 'required',
            'amount'=> 'required',
            'description'=>'required',
        ]);

        $expense->name = $request->name();
        $expense->amount = $request->amount();
        $expense->description = $request->description();
        $expense->save();

        return response()->json([
            'message'=> 'expense updated',
            'expense' => "$expense",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return response()->json([
            'message'=>'It was deleted',
        ]);

    }
}
