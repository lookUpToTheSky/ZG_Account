<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    //用户模型
    // 1.关联的表
    public $table = 'source';
    // 2.主键
    public $primaryKey = 'source_id';
    //3.允许批量操作字段
    public $fillable = ['source_name', 'source_url', 'user_type'];
    // 4.是否维护created_at, updata_at字段
    public $timestamps = false;
}
