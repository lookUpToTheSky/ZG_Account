<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;
use Input;

class GetUserListController extends Controller
{
    //添加用户 ,增
    public function addUserInfo() {
        $userInfo = Input::except('token');
        $info = User::insert($userInfo);
        return response()->json(array("code"=> 200, "data"=> $info, "msg" => "添加成功！"));
    }
    //删除用户， 删
    public function deleteUserInfo() {
        $userId = Input::get('user_id');
        $info = User::where("user_id", '=', $userId)->delete();
        return response()->json(array("code"=> 200, "data"=> $info, "msg" => "删除成功！"));
    }
    //修改用户信息， 改
    public function editUserInfo() {
        $userInfo = Input::except('token');
        foreach($userInfo as $key => $vlaue) {
            if($vlaue === null) {
                $userInfo[$key] = '';
            }
        };
        $info = User::where("user_id", '=', $userInfo['user_id'])->update($userInfo);
        return response()->json(array("code"=> 200, "data"=> $info, "msg" => "修改成功！"));
    }
    //获取用户列表， 查
    public function getUserList() {
        $userList = User::get();
        return response()->json(array("code"=> 200,"data"=>$userList,"msg"=>"获取成功！"));
    }
}
