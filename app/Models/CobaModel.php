<?php

namespace App\Models;

use CodeIgniter\Model;

class CobaModel extends Model
{
    protected $table = 'tbl_notif';
    protected $useTimestamps = true;
    protected $allowedFields = ['id', 'nama', 'created_at', 'updated_at', 'deleted', 'deleted_at'];
}
