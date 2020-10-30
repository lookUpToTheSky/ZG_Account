<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\Source;
use Storage;
use Input;

class SourceListController extends Controller
{
    //上传文件
    public function uploadSource(Request $request) {
        // $user = Input::all();
        if(!$request->file('file')) {
            return response()->json(array("code"=> -1, "msg" => "上传失败！未发现文件"));
        } 
        $fileSize = $request->file('file')->getSize()/1024;
        if($fileSize > 1024) {
            $fileSize = $fileSize/1024;
            $unit = 'M';
            if($fileSize > 10) {
                return response()->json(array("code"=> -1, "msg" => "上传失败！限制文件大小10M"));
            }
        }else{
            $unit = 'kb';
        }
        $size = round($fileSize, 2) . $unit;
        $fileName = $request->file('file')->getClientOriginalName();
        $index = strrpos($fileName, '.');
        $type = substr($fileName, $index);
        $name = substr($fileName, 0, $index);
        $newName = md5(date("Y-m-d H:i:s") . $fileName) . $type;
        $file = $request->file('file')->move('bim', $newName);
        $url ='/bim/' . $newName;
        $array = array('source_type' => $type, 
            'source_url' =>$url,
            'source_name' => $name,
            'source_size' => $size,
            'upload_user' => '',
            'upload_time' => date('Y:m:d H:i:s')
        );
        //上传的头像字段avatar是文件类型
        $source = Source::insert($array);
        if ($source) {
            return response()->json(array("code"=> 200,"data" => $url , "msg" => "上传成功！"));
        }
        return response()->json(array("code"=> -1, "msg" => "上传失败！"));
    }
    //获取文件列表
    public function getSourceList(Request $request) {
        //上传的头像字段avatar是文件类型
        $source = Source::get();  
        foreach($source as $key => $value) {
            $source[$key]->source_url = $_SERVER["REQUEST_SCHEME"].'://'. $_SERVER["SERVER_NAME"] . $source[$key]->source_url;
        }
        if ($source) {
            return response()->json(array("code"=> 200,"data" => $source , "msg" => "获取成功！"));
        }
        return response()->json(array("code"=> -1, "msg" => "无文件！"));
    }
    //下载模型
   public function downloadSource() {
        $sourceId = Input::get('source_id');
        $source = Source::where(["source_id" => $sourceId])->select('source_url', 'source_name')->get();
        return response()->download(realpath(base_path('public')). $source['0']->source_url);
    }
    //删除文件
    public function deleteSource() {
        $sourceId = Input::get('source_id'); 
        $source = Source::where(["source_id" => $sourceId])->delete();
        return response()->json(array("code"=> 200, "msg" => "文件删除成功！"));
    }
}
