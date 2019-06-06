<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\User; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;

class UserController extends Controller 
{
    use ApiResponser;
    public $successStatus = 200;
/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request){ 
        $hasher = app()->make('hash');
 
        $email = $request->input('email');
        $password = $request->input('password');
        $login = User::where('email', $email)->first();
 
        if (!$login) {
            $res['success'] = false;
            $res['message'] = 'Your email or password incorrect!';
            return $this->errorResponse($res, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            if ($hasher->check($password, $login->password)) {
                $api_token = sha1(time());
                $create_token = User::where('id', $login->id)->first();
                if ($create_token) {
                    $res['success'] = true;
                    $res['api_token'] = $create_token->createToken('MyApp')->accessToken;
                    $res['data'] = $login;
                    return response($res);
                }
            } else {
                $res['success'] = true;
                $res['message'] = 'Your email or password incorrect!';
                return $this->errorResponse($res, Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
    }
/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $rules = [
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
        ];

        $this->validate($request,$rules);
        $input = $request->all(); 
                $input['password'] = Hash::make($input['password']);
                $user = User::create($input); 
                $success['token'] =  $user->createToken('MyApp')->accessToken; 
                $success['name'] =  $user->name;

        return response()->json(['success'=>$success], $this->successStatus); 
            }
        /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this->successStatus); 
    } 
}