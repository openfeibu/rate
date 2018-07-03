<?php
namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Validator;
use JWTAuth;

class AuthenticateController extends ApiController
{
    protected $guard = 'api';

    use AuthenticatesUsers;

    public function __construct()
    {
        //$this->middleware('auth:api', ['except' => ['login']]);
        $this->middleware('token')->except([
            'login'
        ]);
    }

    public function username()
    {
        User::create([
           'name' => "csair",
            "email" => "csair@csair.com",
            "password" => bcrypt('123456')
        ]);
        return 'phone';
    }

    public function login(Request $request)
    {
        // 验证规则，由于业务需求，这里我更改了一下登录的用户名，使用手机号码登录
        $rules = [
            'name'   => [
                'required',
                'exists:users',
            ],
            'password' => 'required|string|min:6|max:20',
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {
            $request->request->add([
                'errors' => $validator->errors()->toArray(),
                'code' => 401,
            ]);
            return $this->sendFailedLoginResponse($request);
        }
        $credentials = $request->only('name', 'password');
        // 使用 Auth 登录用户，如果登录成功，则返回 201 的 code 和 token，如果登录失败则返回

        return ($token = Auth::guard('api')->attempt($credentials))
            ? $this->status("认证成功",['token' => $token],200)
            : $this->failed('认证失效',401);
    }
    protected function sendFailedLoginResponse(Request $request)
    {
        $msg = $request['errors'];
        $code = $request['code'];
        return $this->setStatusCode($code)->failed($msg);
    }
    public function me()
    {
        return Auth::guard('api')->user();
    }
}