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

  public function columns()
  {
      $response = array();

      try {
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

        $statusCode = 200;
        $response['data'] = $data;
      } catch (\Exception $e) {
        $statusCode = 404;
        $response['message'] = "找不到资源";
      }

      return response()->json($response, $statusCode);
  }

  public function optionAll()
  {
      $response = array();
      try {
        $option = Option::all(['option', 'column']);
        $statusCode = 200;
        $response['data'] = $option;
      } catch (\Exception $e) {
        $statusCode = 404;
        $response['message'] = "找不到资源";
      }
      return response()->json($response, $statusCode);
  }

  public function optionConfig($id)
  {
      $response = array();
      try {
        $option = Option::where('column', $id)->orderBy('id', 'desc')->get(['id', 'option', 'column']);
        $statusCode = 200;
        $response['data'] = $option;
      } catch (\Exception $e) {
        $statusCode = 404;
        $response['message'] = "找不到资源";
      }
      return response()->json($response, $statusCode);
  }

  public function optionCreate(Request $request)
  {
    $response = array();

    $this->validate($request, [
      'column' => 'required',
      'option' => 'required'
    ]);
    $data = [
      'column' => $request->input('column'),
      'option' => $request->input('option')
    ];

    try {
      $option = Option::create($data);
      $statusCode = 200;
      $response['bar'] = true;
    } catch (\Exception $e) {
      $statusCode = 450;
      $response['message'] = "创建失败";
    }

    return response()->json($response, $statusCode);
  }


  public function optionDelete($id)
  {
    $response = array();

    try {
      $option = Option::findOrFail($id);
      $statusCode = 100;
    } catch (\Exception $e) {
      $statusCode = 404;
      $response['message'] = "找不到资源";
    }

    if ($statusCode === 100) {
      try {
        $option->delete();
        $statusCode = 200;
        $response['data'] = $option;
        $response['bar'] = true;
      } catch (\Exception $e) {
        $statusCode = 404;
        $response['message'] = '删除错误';
      }
    }

    return response()->json($response, $statusCode);
  }


  public function optionUpdate(Request $request, $id)
  {
    $response = array();

    try {
      $option = Option::findOrFail($id);
      $statusCode = 100;
    } catch (\Exception $e) {
      $statusCode = 404;
      $response['message'] = "找不到资源";
    }

    if ($statusCode === 100) {
      try {
        $option->option = $request->input('option');
        $option->save();
        $statusCode = 200;
        $response['bar'] = true;
      } catch (\Exception $e) {
        $statusCode = 404;
        $response['message'] = '修改失败';
      }
    }

    return response()->json($response, $statusCode);
  }


  public function optionGet($id)
  {
    $response = array();

    try {
      $option = Option::findOrFail($id, ['id', 'option']);
      $statusCode = 200;
      $response['data'] = $option;
    } catch (\Exception $e) {
      $statusCode = 404;
      $response['message'] = "找不到资源";
    }

    return response()->json($response, $statusCode);
  }

}
