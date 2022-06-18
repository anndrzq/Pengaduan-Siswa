<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaduanModel extends Model
{
    protected $table = 'tbl_pengaduan';
    protected $useTimestamps = true;
    protected $allowedFields = ['user_id', 'nama_pengadu', 'judul_pengaduan', 'isi_pengaduan', 'created_at', 'updated_at', 'deleted_at', 'row_status'];

    public function hitungPengaduan($user_id)
    {
        $this->where('user_id', $user_id);
        return $this->countAllResults();
    }
}
