<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\PengaduanModel;
use App\Models\BuktiModel;

class Pengaduan extends BaseController
{
    public function __construct()
    {
        $this->pengaduan = new PengaduanModel();
        $this->bukti = new BuktiModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'user' => $this->user,
            'title' => 'Daftar Pengaduan Saya',
            'data' => $this->pengaduan->where(['user_id' => $this->user['id'], 'row_status' => 1])->orderBy('created_at', 'DESC')->findAll()
        ];
        return view('user/pengaduan/index', $data);
    }

    public function soft_delete($id)
    {
        $this->bukti->soft_delete($id); // update deleted_at, row_status

        $this->pengaduan->save([
            'id' => $id,
            'deleted_at' => date('Y-m-d H:i:s'),
            'row_status' => 0
        ]);

        session()->setFlashdata('msg', 'Data berhasil dihapus.');
        return redirect()->to('/pengaduan');
    }

    public function detail($id)
    {
        $data = [
            'user' => $this->user,
            'title' => 'Detail pengaduan',
            'data' =>  $this->pengaduan->find($id),
            'bukti' => $this->bukti->getBukti($id),
        ];

        if (empty($data['data'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        return view('user/pengaduan/detail', $data);
    }

    public function tambah()
    {
        $data = [
            'user' => $this->user,
            'title' => 'Tambah Pengaduan Baru',
            'validation' => $this->validation
        ];
        return view('user/pengaduan/tambah_pengaduan', $data);
    }

    public function tambah_pengaduan()
    {
        $rules = [
            'judul_pengaduan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Perihal pengaduan wajib diisi.'
                ]
            ],
            'isi_pengaduan' => [
                'rules' => 'required|min_length[30]',
                'errors' => [
                    'required' => 'Isi pengaduan wajib diisi.',
                    'min_length' => 'Minimal 30 karakter.'
                ]
            ],
            'images' => [
                'rules' => 'uploaded[images.0]|max_size[images,1024]|is_image[images]|mime_in[images,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Satu file wajib ada.',
                    'max_size' => 'Anda mengupload file yang melebihi ukuran maksimal.',
                    'is_image' => 'Anda mengupload file yang bukan gambar.',
                    'mime_in' => 'Anda mengupload file yang bukan gambar.'
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/pengaduan/tambah')->withInput();
        }

        // JIKA LOLOS VALIDASI > CHECKING FILE
        $images = $this->request->getFileMultiple('images');
        $jumlahFile = count($images); // jumlah file yang di upload

        if ($jumlahFile > 3) { // jika jumlah file melebihi aturan (3)
            session()->setFlashdata('err-files', '<span class="text-danger">Jumlah file yang anda upload melebihi aturan.</span>');
            return redirect()->to('/pengaduan/tambah');
        }

        // checking nama pengadu
        $namaPengadu = $this->request->getPost('nama_pengadu');

        if ($namaPengadu !== $this->user['nama']) {
            if ($namaPengadu !== 'anonym') {
                $namaPengadu = $this->user['nama'];
            }
        }

        $this->db->transBegin(); // Begin DB Transaction

        try {
            $this->pengaduan->save([
                'user_id' => $this->user['id'],
                'nama_pengadu' => $namaPengadu,
                'judul_pengaduan' => $this->request->getPost('judul_pengaduan'),
                'isi_pengaduan' => $this->request->getPost('isi_pengaduan'),
            ]);

            foreach ($images as $i => $img) {
                if ($img->isValid() && !$img->hasMoved()) {
                    $files[$i] = $img->getRandomName();
                }
            }

            $pengaduan_id = $this->pengaduan->insertID(); // last insert id
            $img_dua = (array_key_exists(1, $files) ? $files[1] : null);
            $img_tiga = (array_key_exists(2, $files) ? $files[2] : null);

            $this->bukti->save([
                'pengaduan_id' => $pengaduan_id,
                'img_satu' => $files[0],
                'img_dua' => $img_dua,
                'img_tiga' => $img_tiga,
            ]);

            foreach ($images as $i => $img) {
                if ($img->isValid() && !$img->hasMoved()) {
                    $img->move('uploads', $files[$i]);
                }
            }

            $this->db->transCommit();
        } catch (\Exception $e) {
            $this->db->transRollback();

            // session()->setFlashdata('error-msg', $e->getMessage());
            session()->setFlashdata('error-msg', 'Terjadi kesalahan, data gagal ditambah.');
            return redirect()->to('/pengaduan');
        }

        session()->setFlashdata('msg', 'Pengaduan berhasil ditambah, silahkan menunggu untuk proses approval.');
        return redirect()->to('/pengaduan');
    }

    public function ubah($id)
    {
        $data = [
            'user' => $this->user,
            'title' => 'Ubah Data Pengaduan Saya',
            'data' => $this->pengaduan->find($id),
            'bukti' => $this->bukti->getBukti($id),
            'validation' => $this->validation
        ];

        // cegah id yang tidak jelas
        if (empty($data['data'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        } else {
            // cek jika row_status = 0 | cegah akses form ubah.
            if ($data['data']['row_status'] == 0) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
            } else {
                // cek jika pengaduan sudah diproses tidak bisa diubah lagi
                if ($data['data']['status_pengaduan'] != 1) {
                    throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
                }
            }
        }

        return view('user/pengaduan/ubah_pengaduan', $data);
    }

    public function ubah_pengaduan()
    {
        $rules = [
            'judul_pengaduan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Perihal pengaduan wajib diisi.'
                ]
            ],
            'isi_pengaduan' => [
                'rules' => 'required|min_length[30]',
                'errors' => [
                    'required' => 'Isi pengaduan wajib diisi.',
                    'min_length' => 'Minimal 30 karakter.'
                ]
            ],
            'images' => [
                'rules' => 'max_size[images,1024]|is_image[images]|mime_in[images,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Anda mengupload file yang melebihi ukuran maksimal.',
                    'is_image' => 'Anda mengupload file yang bukan gambar.',
                    'mime_in' => 'Anda mengupload file yang bukan gambar.'
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/pengaduan/ubah/' . $this->request->getPost('id'))->withInput();
        }

        $id = $this->request->getPost('id');
        $namaPengadu = $this->request->getPost('nama_pengadu');

        if ($namaPengadu !== $this->user['nama']) {
            if ($namaPengadu !== 'anonym') {
                $namaPengadu = $this->user['nama'];
            }
        }

        $images = $this->request->getFileMultiple('images');
        $jumlahFile = count($images);

        if ($jumlahFile > 3) {
            session()->setFlashdata('err-files', '<span class="text-danger">Jumlah file yang anda upload melebihi aturan.</span>');
            return redirect()->to('/pengaduan/ubah/' . $id);
        }

        $this->db->transBegin(); // Begin DB Transaction

        try {
            $this->pengaduan->save([
                'id' => $id,
                'user_id' => $this->user['id'],
                'nama_pengadu' => $namaPengadu,
                'judul_pengaduan' => $this->request->getPost('judul_pengaduan'),
                'isi_pengaduan' => $this->request->getPost('isi_pengaduan'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // karena upload file tetap mengembalikan string "" (kosong), jadi kita cek apakah file nya ada yg diupload
            if ($images[0]->getError() !== 4) {
                foreach ($images as $i => $img) {
                    if ($img->isValid() && !$img->hasMoved()) {
                        $files[$i] = $img->getRandomName();
                    }
                }

                // get data bukti
                $bukti = $this->bukti->getBukti($id);

                // hapus file lama
                unlink('uploads/' . $bukti['img_satu']);
                if ($bukti['img_dua'] != null) {
                    unlink('uploads/' . $bukti['img_dua']);
                }
                if ($bukti['img_tiga'] != null) {
                    unlink('uploads/' . $bukti['img_tiga']);
                }

                // update tbl_bukti
                $img_dua = (array_key_exists(1, $files) ? $files[1] : null);
                $img_tiga = (array_key_exists(2, $files) ? $files[2] : null);

                $this->bukti->save([
                    'id' => $this->request->getPost('bukti_id'),
                    'img_satu' => $files[0],
                    'img_dua' => $img_dua,
                    'img_tiga' => $img_tiga,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                // move file baru
                foreach ($images as $i => $img) {
                    if ($img->isValid() && !$img->hasMoved()) {
                        $img->move('uploads', $files[$i]);
                    }
                }
            }

            $this->db->transCommit(); // Commit
        } catch (\Exception $e) {
            $this->db->transRollback(); // Rollback

            session()->setFlashdata('error-msg',  $e->getMessage());
            return redirect()->to('/pengaduan');
        }

        session()->setFlashdata('msg', 'Pengaduan berhasil diubah.');
        return redirect()->to('/pengaduan');
    }
}
