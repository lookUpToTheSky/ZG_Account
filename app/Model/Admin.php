<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    //用户模型
    // 1.关联的表
    public $table = 'admin_list';
    // 2.主键
    public $primaryKey = 'user_id';
    //3.允许批量操作字段
    public $fillable = ['user_name', 'user_phong', 'user_email', 'user_password'];
    // 4.是否维护created_at, updata_at字段
    public $timestamps = false;

}
