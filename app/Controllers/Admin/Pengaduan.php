<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;

use App\Models\Admin\PengaduanModel;
use App\Models\BuktiModel;

class Pengaduan extends BaseController
{
	public function __construct()
	{
		$this->pengaduan = new PengaduanModel();
		$this->bukti = new BuktiModel();
	}

	public function approval($id)
	{
		$status = $this->request->getVar('status_pengaduan');
		$new_status = $status + 1;

		$this->pengaduan->save([
			'id' => $id,
			'status_pengaduan' => $new_status
		]);

		if ($this->request->isAjax()) {
			$data = [
				'status' => TRUE,
				'msg' => 'Status pengaduan berhasil diperbarui.'
			];

			echo json_encode($data);
		} else {
			session()->setFlashData('msg', 'Status pengaduan berhasil diubah.');
			return redirect()->to('/admin/pengaduan/' . $id);
		}
	}

	public function soft_delete($id)
	{
		$this->bukti->soft_delete($id);

		$this->pengaduan->save([
			'id' => $id,
			'deleted_at' => date('Y-m-d H:i:s'),
			'row_status' => 0
		]);

		session()->setFlashdata('msg', 'Data berhasil dihapus.');
		return redirect()->to('/admin/pengaduan');
	}

	public function detail($id)
	{
		$data = [
			'user' => $this->user,
			'title' => 'Detail Pengaduan',
			'data' => $this->pengaduan->find($id),
			'bukti' => $this->bukti->getBukti($id),
		];

		if (empty($data['data'])) {
			throw new \CodeIgniter\Exceptions\PageNotFoundException('Pengaduan tidak ditemukan.');
		}

		return view('admin/pengaduan/detail_pengaduan', $data);
	}

	public function index()
	{
		$data = [
			'user' => $this->user,
			'title' => 'Daftar Semua Pengaduan',
		];

		return view('admin/pengaduan/index', $data);
	}

	public function dt_pengaduan()
	{

		if ($this->request->getMethod() == "post") {
			if ($this->request->isAjax()) {
				$model = $this->pengaduan;
				$lists = $model->get_datatables();

				$data = [];
				$no = $this->request->getPost("start");

				foreach ($lists as $list) {
					$no++;

					$row = [];

					$row[] = $no;
					$row[] = $list->created_at;
					$row[] = $list->judul_pengaduan;
					$row[] = ($list->status_pengaduan == 1 ? '<span class="badge-primary p-1 rounded-sm">baru</span>' : ($list->status_pengaduan == 2 ? '<span class="badge-success p-1 rounded-sm">proses</span>' : '<span class="badge-info p-1 rounded-sm">selesai</span>'));
					$row[] = "<a href=\"/admin/pengaduan/$list->id\" class=\"btn btn-sm btn-info\">Detail</a>";

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

	public function pengaduan_masuk()
	{
		$data = [
			'user' => $this->user,
			'title' => 'Daftar Pengaduan - Masuk',
		];

		return view('admin/pengaduan/masuk', $data);
	}

	public function dt_pengaduan_masuk()
	{
		if ($this->request->getMethod()) {
			if ($this->request->isAjax()) {
				$model = $this->pengaduan;
				$lists = $model->get_datatables_pm();

				$data = [];
				$no = $this->request->getPost("start");

				foreach ($lists as $list) {
					$no++;

					$row = [];

					$formDelete =  "" . form_open('/admin/pengaduan/' . $list->id, ['class' => 'd-inline']) . csrf_field() .
						"<input type=\"hidden\" name=\"_method\" value=\"DELETE\"><button onclick=\"return confirm('yakin?')\" type=\"submit\" class=\"dropdown-item\">Delete</button>" . form_close() . "";

					$formUpdate =  "" . form_open('/admin/pengaduan/' . $list->id, ['class' => 'd-inline']) . csrf_field() .
						"<input type=\"hidden\" name=\"_method\" value=\"PUT\"><input type=\"hidden\" name=\"status_pengaduan\" value=\"$list->status_pengaduan\"><button onclick=\"return confirm('yakin?')\" type=\"submit\" class=\"dropdown-item\">Proses</button>" . form_close() . "";

					$btnAction = "<a class=\"btn btn-primary dropdown-toggle\" href=\"#\" role=\"button\" id=\"dropdownMenuLink\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
					Action
				</a>
					<div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuLink\">
					<a href=\"/admin/pengaduan/$list->id\" class=\"dropdown-item\">Detail</a>
					" . $formDelete . $formUpdate . "
				</div>";

					$row[] = $no;
					$row[] = date('d M Y', strtotime($list->created_at));
					$row[] = $list->judul_pengaduan;
					$row[] = $btnAction;

					$data[] = $row;
				}
				$output = [
					"recordTotal" => $model->count_all(),
					"recordsFiltered" => $model->count_filtered_pm(),
					"data" => $data
				];

				echo json_encode($output);
			}
		}
	}

	public function pengaduan_diproses()
	{
		$data = [
			'user' => $this->user,
			'title' => 'Daftar Pengaduan - Sedang Diproses'
		];

		return view('admin/pengaduan/diproses', $data);
	}

	public function dt_pengaduan_diproses()
	{
		if ($this->request->getMethod()) {
			if ($this->request->isAjax()) {
				$model = $this->pengaduan;
				$lists = $model->get_datatables_dp();

				$data = [];
				$no = $this->request->getPost("start");

				foreach ($lists as $list) {
					$no++;

					$row = [];

					$formDelete =  "" . form_open('/admin/pengaduan/' . $list->id, ['class' => 'd-inline']) . csrf_field() .
						"<input type=\"hidden\" name=\"_method\" value=\"DELETE\"><button onclick=\"return confirm('yakin?')\" type=\"submit\" class=\"dropdown-item\">Delete</button>" . form_close() . "";

					$formUpdate =  "" . form_open('/admin/pengaduan/' . $list->id, ['class' => 'd-inline']) . csrf_field() .
						"<input type=\"hidden\" name=\"_method\" value=\"PUT\"><input type=\"hidden\" name=\"status_pengaduan\" value=\"$list->status_pengaduan\"><button onclick=\"return confirm('yakin?')\" type=\"submit\" class=\"dropdown-item\">Selesaikan</button>" . form_close() . "";

					$btnAction = "<a class=\"btn btn-primary dropdown-toggle\" href=\"#\" role=\"button\" id=\"dropdownMenuLink\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
					Action
				</a>
					<div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuLink\">
					<a href=\"/admin/pengaduan/$list->id\" class=\"dropdown-item\">Detail</a>
					" . $formDelete . $formUpdate . "
				</div>";

					$row[] = $no;
					$row[] = date('d M Y', strtotime($list->created_at));
					$row[] = $list->judul_pengaduan;
					$row[] = $btnAction;

					$data[] = $row;
				}
				$output = [
					"recordTotal" => $model->count_all(),
					"recordsFiltered" => $model->count_filtered_dp(),
					"data" => $data
				];

				echo json_encode($output);
			}
		}
	}

	public function pengaduan_diselesaikan()
	{
		$data = [
			'user' => $this->user,
			'title' => 'Daftar Pengaduan - Diselesaikan',
		];

		return view('admin/pengaduan/diselesaikan', $data);
	}

	public function dt_pengaduan_diselesaikan()
	{
		if ($this->request->getMethod()) {
			if ($this->request->isAjax()) {
				$model = $this->pengaduan;
				$lists = $model->get_datatables_ds();

				$data = [];
				$no = $this->request->getPost("start");

				foreach ($lists as $list) {
					$no++;

					$row = [];

					$formDelete =  "" . form_open('/admin/pengaduan/' . $list->id, ['class' => 'd-inline']) . csrf_field() .
						"<input type=\"hidden\" name=\"_method\" value=\"DELETE\"><button onclick=\"return confirm('yakin?')\" type=\"submit\" class=\"dropdown-item\">Delete</button>" . form_close() . "";

					$btnAction = "<a class=\"btn btn-primary dropdown-toggle\" href=\"#\" role=\"button\" id=\"dropdownMenuLink\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
					Action
				</a>
					<div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuLink\">
					<a href=\"/admin/pengaduan/$list->id\" class=\"dropdown-item\">Detail</a>
					" . $formDelete . "
				</div>";

					$row[] = $no;
					$row[] = date('d M Y', strtotime($list->created_at));
					$row[] = $list->judul_pengaduan;
					$row[] = $btnAction;

					$data[] = $row;
				}
				$output = [
					"recordTotal" => $model->count_all(),
					"recordsFiltered" => $model->count_filtered_ds(),
					"data" => $data
				];

				echo json_encode($output);
			}
		}
	}
}
