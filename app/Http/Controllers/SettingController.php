<?php
namespace App\Http\Controllers;
use App\User;
use App\Order;
use App\Option;
use DB;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class SettingController extends Controller
{

    public function option()
    {
        $columns = DB::select("
        select distinct COLUMN_NAME
        from information_schema.columns
        where TABLE_SCHEMA='api'
        ");

        $options = Option::all(['column', 'option']);

        $data = (object)[];
        $data->columns = $columns;

        foreach ($data->columns as $v) {
          $item = array();
          foreach ($options as $k => $w) {
            if ($v->COLUMN_NAME === $w->column) {
              array_push($item, $w->option);
            }
          }
          if (count($item) > 0) {
            $v->item = $item;
          }
        }

        return response()->json([
          'data' => $data,
        ], 200);
    }


    public function optionConfig($id)
    {
        $option = Option::where('column', $id)->get();
        return response()->json([ 'data' => $option], 200 );
    }


    public function optionCreate(Request $request)
    {
        $this->validate($request, [
            'column' => 'required',
            'option' => 'required'
        ]);

        $data = [
            'column' => $request->input('column'),
            'option' => $request->input('option')
        ];

        $option = Option::create($data);
        $statusCode = $option ? 200 : 422;
        return response()->json([
            'data' => $option,
        ], $statusCode );
    }


    public function optionDelete($id)
    {

      $response = array();
      try {
        $option = Option::findOrFail($id);
        $option->delete();
        $statusCode = 200;
        $response['data'] = $option;
      } catch (\Exception $e) {
        $statusCode = 404;
        $response['message'] = "找不到资源";
      }

      return response($response, $statusCode );
    }


    public function optionUpdate(Request $request, $userId)
    {
      try {
        $user = self::userExist($userId);
        $user->update($request->only('name', 'password'));
        $statusCode = 200;
      } catch(\Exception $e) {
        $user = null;
        $statusCode = 404;
      }

      return response([
        "data" => $user,
        "status" => $user ? "success" : "Not found."
      ], $statusCode);
    }


    public function optionGet($id)
    {
      $response = array();
      try {
        $option = Option::findOrFail($id);
        $statusCode = 200;
        $response['data'] = $option;
      } catch (\Exception $e) {
        $statusCode = 404;
        $response['message'] = "找不到资源";
      }

      return response($response, $statusCode );
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
        return response()->json([
                'data' => $user,
            ], $statusCode );
    }


    public function show($orderId)
    {
        try {
            $order = Order::findOrFail($orderId)->first();
            $statusCode = 200;
            return response()->json([ 'data' => $order], $statusCode );
        } catch (Exception $e) {
            $user = null;
            $statusCode = 404;
            return response()->json([ 'message' => $e ], $statusCode );
        }

    }


    public function update(Request $request, $userId)
    {
        try {
            $user = self::userExist($userId);
            $user->update($request->only('name', 'password'));
            $statusCode = 200;
        } catch(\Exception $e) {
            $user = null;
            $statusCode = 404;
        }

        return response([
                "data" => $user,
                "status" => $user ? "success" : "Not found."
            ], $statusCode);
    }


    public function delete($userId)
    {
        try {
            $user = self::userExist($userId);
            $user->delete();
            $statusCode = 200;
        } catch(\Exception $e) {
            $user = null;
            $statusCode = 404;
        }

        return response([
                "data" => $user,
                "status" => $user ? "success" : "Not found."
            ], $statusCode );
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
            User::where('phone', $request->input('phone'))
                        ->update(['remember_token' => $token]);

            return response()->json([
                            'status' => 'success',
                            'data' => $user], 200)
                            ->withHeaders([
                                'Authorization' => $token,
                            ]);
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => '账号密码不匹配'
            ], 401);
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
