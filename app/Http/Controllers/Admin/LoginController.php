<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use Input;
use Session;
class LoginController extends Controller
{
    //登录
    public function login() {
		$input = Input::all();
		if(Session::get('code') != $input['code']) {
			return  response()->json(array("code"=> -1,"data"=> null,"error"=> "code","msg"=>"验证码不正确！"));
		}
		$admin = Admin::where('admin_name', $input['admin_name']) ->first();
		if(!$admin) {
			return  response()->json(array("code"=> -1,"data"=>null,"error"=> "admin_name", "msg"=>"用户名不存在！"));
		}
		if($admin['admin_password'] !== $input['admin_password']) {
			return  response()->json(array("code"=> -1,"data"=>null,"error"=> "admin_password","msg"=>"密码不正确！"));
		}
		$admin->token = Session::get('_token');
		Session::put('admin_name', $input['admin_name']);
		return  response()->json(array("code"=> 200,"data"=>$admin,"msg"=>"登录成功！"));
	}
	//退出登录
	public function logout() {
		$info = Session::put('admin_name', null);
		return view('admin.login');
	}
	// 验证码
    public function code() {
		$arr1 = [0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9];
		$arr2 = range('a', 'z');
		$arr = array_merge($arr1,$arr2);
		shuffle($arr);
		$data = implode($arr);
		$image = imagecreatetruecolor(50,30);
		$color = imagecolorallocate($image,225,225,225);
		imagefill($image, 0, 0,  $color);
		$code='';
		$codeLen = 4;
		for($i = 0; $i < $codeLen; $i++){
			$size = 5;
			$color = imagecolorallocate($image, rand(100,200), rand(50,80), rand(50,225));
			$content = substr($data ,rand(0,strlen($data)-1),1);
			$code .= $content;
			$x = ($i * 100 / 10 ) + rand(3,6);
			$y = rand(6,10);
			imagestring($image, $size, $x, $y, $content, $color);
		}
		Session::put('code', $code);
		//干扰点
		for($i = 0; $i <50; $i++){
			$color = imagecolorallocate($image, rand(50,200), rand(50,200), rand(50,200));
			imagesetpixel($image, rand(1,99), rand(1,29), $color);
		}
		//干扰线
		// for($i = 0; $i < 2; $i++){
		// 	$color = imagecolorallocate($image, rand(90,190), rand(80,220), rand(80,220));
		// 	imageline($image, rand(1,99), rand(1,29), rand(1,99), rand(1,29), $color);
		// }
		header("Content-type:image/png");
		imagepng($image);
		imagedestroy($image);
	}
}
