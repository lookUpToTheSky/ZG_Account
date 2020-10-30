<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //前台用户模型
    // 1.关联的表
    public $table = 'user_list';
    // 2.主键
    public $primaryKey = 'user_id';
    //3.允许批量操作字段
    public $fillable = ['user_name', 'user_phong', 'user_email', 'user_password', 'api_token'];
    // 4.是否维护created_at, updata_at字段
    public $timestamps = true;

}
