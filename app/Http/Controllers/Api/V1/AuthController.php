<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Models\Manager;
use Dingo\Api\Exception\ResourceException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends BaseController
{
    /**
     * @api {post} /authorization 登录(login)
     * @apiDescription 登录(login)
     * @apiGroup Auth
     * @apiPermission none
     * @apiParam {String} mobile      用户名
     * @apiParam {String} password  密码
     * @apiVersion 1.0.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL21vYmlsZS5kZWZhcmEuY29tXC9hdXRoXC90b2tlbiIsImlhdCI6IjE0NDU0MjY0MTAiLCJleHAiOiIxNDQ1NjQyNDIxIiwibmJmIjoiMTQ0NTQyNjQyMSIsImp0aSI6Ijk3OTRjMTljYTk1NTdkNDQyYzBiMzk0ZjI2N2QzMTMxIn0.9UPMTxo3_PudxTWldsf4ag0PHq1rK8yO9e5vqdwRZLY
     *     }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 422 error info
     *     {
     *       "message": "用户名或密码不正确",
     *       "status_code": 422
     *     }
     */
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'mobile'   => 'required',
                'password' => 'required',
            ]
        );

        if ($validator->fails()) {
            throw new ResourceException('输入信息有误', $validator->messages());
        }

        $credentials = $request->only('mobile', 'password');

        // 验证失败返回422
        if (!$token = JWTAuth::attempt($credentials)) {
            $this->response->error('用户名或密码不正确', 422);
        }

        return $this->response->array(compact('token'));
    }

    /**
     * @api {post} /auth/token/new 刷新token(refresh token)
     * @apiDescription 刷新token(refresh token)
     * @apiGroup Auth
     * @apiPermission JWT
     * @apiVersion 1.0.0
     * @apiHeader {String} Authorization 用户旧的jwt-token, value以Bearer开头
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL21vYmlsZS5kZWZhcmEuY29tXC9hdXRoXC90b2tlbiIsImlhdCI6IjE0NDU0MjY0MTAiLCJleHAiOiIxNDQ1NjQyNDIxIiwibmJmIjoiMTQ0NTQyNjQyMSIsImp0aSI6Ijk3OTRjMTljYTk1NTdkNDQyYzBiMzk0ZjI2N2QzMTMxIn0.9UPMTxo3_PudxTWldsf4ag0PHq1rK8yO9e5vqdwRZLY"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         token: 9UPMTxo3_PudxTWldsf4ag0PHq1rK8yO9e5vqdwRZLY.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL21vYmlsZS5kZWZhcmEuY29tXC9hdXRoXC90b2tlbiIsImlhdCI6IjE0NDU0MjY0MTAiLCJleHAiOiIxNDQ1NjQyNDIxIiwibmJmIjoiMTQ0NTQyNjQyMSIsImp0aSI6Ijk3OTRjMTljYTk1NTdkNDQyYzBiMzk0ZjI2N2QzMTMxIn0.eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9
     *     }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "message": "token无效",
     *       "status_code": 400
     *     }
     */
    public function refreshToken()
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
        } catch (TokenExpiredException $e) {
            return $this->response->error('token已过期', $e->getStatusCode());
        } catch (JWTException $e) {
            return $this->response->error('token无效', $e->getStatusCode());
        }

        return $this->response->array(compact('token'));
    }

    /**
     * @api {post} /user 注册(register)
     * @apiDescription 注册(register)
     * @apiGroup Auth
     * @apiPermission none
     * @apiVersion 1.0.0
     * @apiParam {String} user       user[unique]
     * @apiParam {String} password   password
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL21vYmlsZS5kZWZhcmEuY29tXC9hdXRoXC90b2tlbiIsImlhdCI6IjE0NDU0MjY0MTAiLCJleHAiOiIxNDQ1NjQyNDIxIiwibmJmIjoiMTQ0NTQyNjQyMSIsImp0aSI6Ijk3OTRjMTljYTk1NTdkNDQyYzBiMzk0ZjI2N2QzMTMxIn0.9UPMTxo3_PudxTWldsf4ag0PHq1rK8yO9e5vqdwRZLY
     *     }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 422 error info
     *     {
     *       "message": "注册新用户失败",
     *       "errors": {
     *         "user": [
     *           "该用户名已被他人注册"
     *         ]
     *       },
     *       "status_code": 422
     *     }
     */
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->input(),
            [
                'user'     => 'required|alpha_num|unique:manager',
                'password' => 'required|min:6',
            ]
        );

        if ($validator->fails()) {
            throw new StoreResourceFailedException('注册新用户失败', $validator->messages());
        }

        $user     = $request->get('user');
        $password = $request->get('password');

        $attributes = [
            'user'     => $user,
            'password' => app('hash')->make($password),
        ];

        $user = Manager::create($attributes);

        if ($user) {
            $this->response->error('注册新用户失败', 422);
        }

        // 用户注册事件
        $token = JWTAuth::fromUser($user);

        return $this->response->array(compact('token'));
    }
}
