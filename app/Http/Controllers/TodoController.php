<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\Todo;
use App\User;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index($id) 
    {
        $todos = User::findOrFail($id)->todo()->get();
        return $this->successResponse($todos);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|max:255',
            'content' => 'required|max:255',
            'status' => 'required|max:255|in:0,1'
        ];

        $this->validate($request,$rules);

        $todo = Todo::create($request->all());
        return $this->successResponse($todo);
    }

    public function update(Request $request, $todo)
    {

        $rules = [
            'title' => 'max:255',
            'content' => 'max:255',
            'status' => 'max:255|in:0,1'
        ];

        $this->validate($request,$rules);

        $todo = Todo::findOrFail($todo);
        //return $todo;
        $todo->fill($request->all());
        if ($todo->isClean()) {
            return $this->errorResponse('At least one value must be changed', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $todo->save();    
        return $this->successResponse($todo);  

    }

    public function destroy($todo)
    {
        $todo = Todo::findOrFail($todo);
        $todo->delete();
        return $this->successResponse($todo);
    }

    //
}
