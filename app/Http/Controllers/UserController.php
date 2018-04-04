<?php
namespace App\Http\Controllers;
use App\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;


class UserController extends Controller
{

    public function index()
    {
        return response(['data' => User::all()->toArray()]);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|unique:users',
            'password' => 'required'
        ]);

        $data = [
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password'))
        ];

        $user = User::create($data);
        $statusCode = $user ? 200 : 422;
        return response(
            [
                'data' => $user,
                'status' => $user ? "success" : "error",
            ], $statusCode
        );
    }


    public function show($userId)
    {
        try {
            $user = User::findOrFail($userId);
        } catch (\Exception $e) {
            $user = null;
            $statusCode = 404;
        }
        return response(
            [
                'data' => $user,
                'status' => $user ? "success" : "Not found.",
            ], $statusCode ?? 201
        );
    }


    public function update(Request $request, $userId)
    {
        try {
            $user = self::userExist($userId);
            $user->update($request->only('name', 'password'));
        } catch(\Exception $e) {
            $user = null;
            $statusCode = 404;
        }
        return response(
            [
                "data" => $user,
                "status" => $user ? "success" : "Not found."
            ], $statusCode ?? 200
        );
    }


    public function delete($userId)
    {
        try {
            $user = self::userExist($userId);
            $user->delete();
        } catch(\Exception $e) {
            $user = null;
            $statusCode = 404;
        }
        return response(
            [
                "data" => $user,
                "status" => $user ? "success" : "Not found."
            ], $statusCode ?? 200
        );
    }


    protected static function userExist($id)
    {
        return User::findOrFail($id);
    }


    public function login(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('phone', $request->input('phone'))->first();
        if(Hash::check($request->input('password'), $user->password)){
            $token = base64_encode(str_random(40));
            User::where('phone', $request->input('phone'))->update(['remember_token' => $token]);
            return response()->json(['status' => 'success', 'data' => $user])
                                ->withHeaders([
                                    'Authorization' => $token,
                                    'login' => 'thisi s login'
                                ]);;
        }else{
            return response()->json(['status' => 'fail'],401);
        }
    }


    public function logout($id)
    {
        if(User::where('id', $id)->update(['remember_token' => '']))
        {
            return response()->json(['status' => 'success'], 200);
        }
    }

}
