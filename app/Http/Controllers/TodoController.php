<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }
  
/**
*todo list
*/
  public function index(Request $request)
  {
	$todo = Auth::user()->todo()->get();
	return response()->json(['status' => 'success', 'data' => $todo]);
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
      'todo' => 'required',
      'description' => 'required',
      'category' => 'required'
    ]);

	Auth::user()->todo()->Create($request->all());
	
	return response()->json([
                'code' => 200,
                'status' => 'success'
                ]);    
  }
  
  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    $todo = Todo::where('id', $id)->get();
    return response()->json($todo); 
  }
  
  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function edit($id)
  {
    $todo = Todo::where('id', $id)->get();
    return view('todo.edittodo',['todos' => $todo]);
  }
  
  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, $id)
  {
    $this->validate($request, [
      'todo' => 'filled',
      'description' => 'filled',
      'category' => 'filled'
    ]);

    $todo = Todo::find($id);
    if($todo->fill($request->all())->save()){
      return response()->json(['status' => 'success']);
    }
    return response()->json(['status' => 'fail']);
  }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    if(Todo::destroy($id)){
      return response()->json(['status' => 'success']);
    }
  }
}
