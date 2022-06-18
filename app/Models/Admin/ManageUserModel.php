<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class ManageUserModel extends Model
{
    protected $table = 'tbl_user';
    protected $useTimestamps = TRUE;
    protected $allowedFields = ['nama', 'username', 'email', 'user_image', 'user_ktp', 'user_level', 'is_active', 'created_at', 'updated_at', 'deleted_at', 'row_status'];

    protected $column_order = [null, null, 'nama', 'is_active', null];
    protected $column_search = ['nama'];
    protected $order = ['created_at' => 'desc'];

    protected $column_order_uu = [null, null, 'nama', null, null];
    protected $column_search_uu = ['nama'];
    protected $order_uu = ['created_at' => 'desc'];

    function __construct()
    {
        parent::__construct();
        $this->dt = $this->db->table($this->table)->select('id, username, user_image, nama, is_active, user_level')->where('row_status', 1);
        $this->uv = $this->db->table($this->table)->select('id, username, user_image, nama, is_active, user_level, user_ktp')->where(['row_status' => 1, 'is_active' => 0]);
    }

    public function getUser($username = false)
    {
        $this->orderBy('user_level', 'ASC');
        if ($username == false) {
            return $this->where(['row_status' => 1])->get()->getResultArray();
        }
        return $this->where(['username' => $username])->first();
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

    private function _get_datatables_query_uu()
    {
        $i = 0;
        foreach ($this->column_search_uu as $item) {
            if (isset($_POST['search']['value'])) {
                if ($i === 0) {
                    $this->uv->groupStart();
                    $this->uv->like($item, $_POST['search']('value'));
                } else {
                    $this->uv->orLike($item, $_POST['search']['value']);
                }
                if (count($this->column_search_uu) - 1 == $i)
                    $this->uv->groupEnd();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->uv->orderBy($this->column_order_uu[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->uv->orderBy(key($order), $order[key($order)]);
        }
    }
    function get_datatables_uu()
    {
        $this->_get_datatables_query_uu();
        if (isset($_POST['length' != -1]))
            $this->uv->limit($_POST['length'], $_POST['start']);
        $query = $this->uv->get();
        return $query->getResult();
    }
    function count_filtered_uu()
    {
        $this->_get_datatables_query_uu();
        return $this->uv->countAllResults();
    }

    public function count_all()
    {
        $tbl_storage = $this->db->table($this->table);
        return $tbl_storage->countAllResults();
    }
}
