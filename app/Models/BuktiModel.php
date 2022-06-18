<?php

namespace App\Models;

use CodeIgniter\Model;

class BuktiModel extends Model
{
    protected $table = 'tbl_bukti';
    protected $useTimestamps = true;
    protected $allowedFields = ['id', 'pengaduan_id', 'img_satu', 'img_dua', 'img_tiga', 'updated_at', 'deleted_at', 'row_status'];

    public function getBukti($pengaduan_id)
    {
        $this->where('pengaduan_id', $pengaduan_id);
        return $this->get()->getRowArray();
    }

    public function soft_delete($pengaduan_id)
    {
        $builder = $this->table('tbl_bukti');
        $builder->set('row_status', 0);
        $builder->set('deleted_at', date('Y-m-d H:i:s'));
        $builder->where('pengaduan_id', $pengaduan_id);
        $builder->update();
    }
}
