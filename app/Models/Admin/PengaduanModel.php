<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class PengaduanModel extends Model
{
    protected $table = 'tbl_pengaduan';
    protected $useTimestamps = true;
    protected $allowedFields = ['id', 'status_pengaduan', 'created_at', 'updated_at', 'deleted_at', 'row_status'];

    protected $column_order = [null, 'created_at', 'judul_pengaduan', 'status_pengaduan', null];
    protected $column_search = ['judul_pengaduan'];
    protected $order = ['created_at' => 'desc'];

    protected $column_order_pm = [null, 'created_at', 'judul_pengaduan', null];
    protected $column_search_pm = ['judul_pengaduan'];
    protected $order_pm = ['created_at' => 'desc'];

    protected $column_order_dp = [null, 'created_at', 'judul_pengaduan', null];
    protected $column_search_dp = ['judul_pengaduan'];
    protected $order_dp = ['created_at' => 'desc'];

    protected $column_order_ds = [null, 'created_at', 'judul_pengaduan', null];
    protected $column_search_ds = ['judul_pengaduan'];
    protected $order_ds = ['created_at' => 'desc'];

    function __construct()
    {
        parent::__construct();
        $this->dt = $this->db->table($this->table)->select('id, created_at, judul_pengaduan, status_pengaduan')->where('row_status', 1);
        $this->pm = $this->db->table($this->table)->select('id, created_at, judul_pengaduan, status_pengaduan')->where(['row_status' => 1, 'status_pengaduan' => 1]);
        $this->dp = $this->db->table($this->table)->select('id, created_at, judul_pengaduan, status_pengaduan')->where(['row_status' => 1, 'status_pengaduan' => 2]);
        $this->ds = $this->db->table($this->table)->select('id, created_at, judul_pengaduan, status_pengaduan')->where(['row_status' => 1, 'status_pengaduan' => 3]);
    }

    private function _get_datatables_query()
    {
        $i = 0;
        foreach ($this->column_search as $item) {
            if (isset($_POST['search']['value'])) {
                if ($i === 0) {
                    $this->dt->groupStart();
                    $this->dt->like($item, $_POST['search']('value'));
                } else {
                    $this->dt->orLike($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i)
                    $this->dt->groupEnd();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->dt->orderBy($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->dt->orderBy(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if (isset($_POST['length' != -1]))
            $this->dt->limit($_POST['length'], $_POST['start']);
        $query = $this->dt->get();
        return $query->getResult();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->dt->countAllResults();
    }

    private function _get_datatables_query_pm()
    {
        $i = 0;
        foreach ($this->column_search_pm as $item) {
            if (isset($_POST['search']['value'])) {
                if ($i === 0) {
                    $this->pm->groupStart();
                    $this->pm->like($item, $_POST['search']('value'));
                } else {
                    $this->pm->orLike($item, $_POST['search']['value']);
                }
                if (count($this->column_search_pm) - 1 == $i)
                    $this->pm->groupEnd();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->pm->orderBy($this->column_order_pm[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->pm->orderBy(key($order), $order[key($order)]);
        }
    }
    function get_datatables_pm()
    {
        $this->_get_datatables_query_pm();
        if (isset($_POST['length' != -1]))
            $this->pm->limit($_POST['length'], $_POST['start']);
        $query = $this->pm->get();
        return $query->getResult();
    }
    function count_filtered_pm()
    {
        $this->_get_datatables_query_pm();
        return $this->pm->countAllResults();
    }

    private function _get_datatables_query_dp()
    {
        $i = 0;
        foreach ($this->column_search_dp as $item) {
            if (isset($_POST['search']['value'])) {
                if ($i === 0) {
                    $this->dp->groupStart();
                    $this->dp->like($item, $_POST['search']('value'));
                } else {
                    $this->dp->orLike($item, $_POST['search']['value']);
                }
                if (count($this->column_search_dp) - 1 == $i)
                    $this->dp->groupEnd();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->dp->orderBy($this->column_order_dp[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->dp->orderBy(key($order), $order[key($order)]);
        }
    }
    function get_datatables_dp()
    {
        $this->_get_datatables_query_dp();
        if (isset($_POST['length' != -1]))
            $this->dp->limit($_POST['length'], $_POST['start']);
        $query = $this->dp->get();
        return $query->getResult();
    }
    function count_filtered_dp()
    {
        $this->_get_datatables_query_dp();
        return $this->dp->countAllResults();
    }

    private function _get_datatables_query_ds()
    {
        $i = 0;
        foreach ($this->column_search_ds as $item) {
            if (isset($_POST['search']['value'])) {
                if ($i === 0) {
                    $this->ds->groupStart();
                    $this->ds->like($item, $_POST['search']('value'));
                } else {
                    $this->ds->orLike($item, $_POST['search']['value']);
                }
                if (count($this->column_search_ds) - 1 == $i)
                    $this->ds->groupEnd();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->ds->orderBy($this->column_order_ds[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->ds->orderBy(key($order), $order[key($order)]);
        }
    }
    function get_datatables_ds()
    {
        $this->_get_datatables_query_ds();
        if (isset($_POST['length' != -1]))
            $this->ds->limit($_POST['length'], $_POST['start']);
        $query = $this->ds->get();
        return $query->getResult();
    }
    function count_filtered_ds()
    {
        $this->_get_datatables_query_ds();
        return $this->ds->countAllResults();
    }

    public function count_all()
    {
        $tbl_storage = $this->db->table($this->table);
        return $tbl_storage->countAllResults();
    }
}
