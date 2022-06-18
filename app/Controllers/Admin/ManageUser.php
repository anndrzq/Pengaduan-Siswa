<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\ManageUserModel;

class ManageUser extends BaseController
{
    public function __construct()
    {
        $this->ManageUser = new ManageUserModel();
    }

    public function index()
    {
        $data = [
            'user' => $this->user,
            'title' => 'Daftar User',
        ];
        return view('admin/manage-user/index', $data);
    }

    public function dt_users() // datatables
    {
        if ($this->request->getMethod()) {
            if ($this->request->isAjax()) {
                $model = $this->ManageUser;
                $lists = $model->get_datatables();

                $data = [];
                $no = $this->request->getPost("start");

                foreach ($lists as $list) {
                    $no++;

                    $row = [];

                    $row[] = $no;
                    $row[] = "<img src=\"/img/profile/$list->user_image\" width=\"54px\" height=\"54px\" class=\"rounded-circle\">";
                    $row[] = $list->nama;

                    $row[] = $list->is_active == 1 ? "<span class=\"badge badge-success\">Aktif</span>" : "<span class=\"badge badge-danger\">Tidak Aktif</span>";

                    if ($list->user_level == 1) {
                        $row[] = "<a href=\"/admin/manage-user/$list->username\" class=\"btn btn-sm btn-info\">Detail</a>";
                    }

                    $row[] = "<a href=\"/admin/manage-user/$list->username\" class=\"btn btn-sm btn-info\">Detail</a> <button type=\"button\" data-toggle=\"modal\" data-target=\"#modal-hapus\" data-userid=\"$list->id\" class=\"btn btn-sm btn-danger hapus-user\">Hapus</button>";

                    $data[] = $row;
                }
                $output = [
                    "recordTotal" => $model->count_all(),
                    "recordsFiltered" => $model->count_filtered(),
                    "data" => $data
                ];

                echo json_encode($output);
            }
        }
    }

    public function detail($username)
    {
        $data = [
            'user' => $this->user,
            'title' => 'Detail User',
            'data' => $this->ManageUser->getUser($username)
        ];

        if (empty($data['data'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan.');
        }

        return view('admin/manage-user/detail', $data);
    }

    public function user_activation()
    {
        $this->ManageUser->save([
            'id' => $this->request->getPost('id'),
            'is_active' => 1
        ]);

        session()->setFlashdata('msg', 'User berhasil di verifikasi.');
        return redirect()->to('/admin/manage-user');
    }

    public function soft_delete()
    {
        // jika id yang dihapus dari user_level admin, gagalkan!
        $this->ManageUser->save([
            'id' => $this->request->getPost('id'),
            'deleted_at' => date('Y-m-d H:i:s'),
            'row_status' => 0
        ]);

        echo json_encode(['msg' => 'Data berhasil dihapus.']);
    }

    public function user_unverified()
    {
        $data = [
            'user' => $this->user,
            'title' => 'Daftar Users - Belum Terverifikasi',
        ];
        return view('admin/manage-user/un-verified', $data);
    }

    public function dt_users_unverified()
    {
        if ($this->request->getMethod()) {
            if ($this->request->isAjax()) {
                $model = $this->ManageUser;
                $lists = $model->get_datatables_uu();

                $data = [];
                $no = $this->request->getPost("start");

                foreach ($lists as $list) {
                    $no++;

                    $row = [];

                    $row[] = $no;
                    $row[] = "<img src=\"/img/profile/$list->user_image\" width=\"54px\" height=\"54px\" class=\"rounded-circle\">";
                    $row[] = $list->nama;

                    $row[] = "<a href=\"/img/ktp/$list->user_ktp\" target=\"_blank\">Lihat</a>";
                    $row[] = "<a href=\"/admin/manage-user/$list->username\" class=\"btn btn-sm btn-info\">Detail</a> <button type=\"button\" data-toggle=\"modal\" data-target=\"#user-approval\" data-id=\"$list->id\" class=\"btn btn-sm btn-primary btn-approve\">Approve</button>";

                    $data[] = $row;
                }
                $output = [
                    "recordTotal" => $model->count_all(),
                    "recordsFiltered" => $model->count_filtered_uu(),
                    "data" => $data
                ];

                echo json_encode($output);
            }
        }
    }
}
