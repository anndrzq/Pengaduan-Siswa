<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // cek jika sudah login - redirect back
        if (session()->get('logged_in')) {
            return redirect()->back();
        }

        $data = [
            'title' => 'Login Page',
            'validation' => $this->validation
        ];

        return view('auth/index', $data);
    }

    public function validasi_login()
    {
        if (!isset($_POST['btn-submit'])) {
            return redirect()->to('/notfound');
        }

        $rules = [
            'email' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Email atau username wajib diisi.',
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password wajib diisi.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        } else {
            $email =  htmlspecialchars($this->request->getPost('email'));
            $password =  htmlspecialchars($this->request->getPost('password'));

            $user = $this->db->table('tbl_user')
                ->where('username', $email)
                ->orWhere('email', $email)
                ->get()->getRowArray();

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    if ($user['is_active'] == 1) {
                        $user_session = [
                            'logged_in' => TRUE,
                            'id' => $user['id'],
                            'user_level' => $user['user_level'],
                        ];

                        session()->set($user_session);
                        session()->setFlashdata('msg-auth', 'Anda berhasil login, selamat datang.');

                        if ($user_session['user_level'] == 1 || $user_session['user_level'] == 2) {
                            return redirect()->to('/admin/pengaduan');
                        } else {
                            return redirect()->to('/pengaduan');
                        }
                    } else {
                        session()->setFlashdata('msg-failed', 'Akun anda belum aktif.');
                        return redirect()->to('/auth');
                    }
                } else {
                    session()->setFlashdata('msg-failed', 'Login gagal, username / password anda salah!');
                    return redirect()->to('/auth');
                }
            } else {
                session()->setFlashdata('msg-failed', 'Akun tidak terdaftar!');
                return redirect()->to('/auth');
            }
        }
    }

    public function register()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/notfound');
        }

        $data = [
            'title' => 'Register Page',
            'validation' => $this->validation
        ];

        return view('auth/register', $data);
    }

    public function validasi_register()
    {
        if (!isset($_POST['btn-submit'])) {
            return redirect()->to('/notfound');
        }

        $rules = [
            'nama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama wajib diisi.'
                ]
            ],
            'username' => [
                'rules' => 'required|is_unique[tbl_user.username]',
                'errors' => [
                    'required' => 'Username wajib diisi.',
                    'is_unique' => 'Username sudah terpakai. Gunakan username lain'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[tbl_user.email]',
                'errors' => [
                    'required' => 'Email wajib diisi.',
                    'valid_email' => 'Email harus valid.',
                    'is_unique' => 'Email sudah terpakai. Gunakan email lain'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Password wajib diisi.',
                    'min_length' => 'Minimal harus 8 karakter.'
                ]
            ],
            'confirm_password' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password wajib diisi.',
                    'matches' => 'Konfirmasi password tidak sama.'
                ]
            ],
            'user_ktp' => [
                'rules' => 'uploaded[user_ktp]|max_size[user_ktp, 512]|is_image[user_ktp]|mime_in[user_ktp,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'File ktp wajib diisi.',
                    'max_size' => 'Ukuran file ktp tidak boleh lebih dari 512kb.',
                    'is_image' => 'File ktp yang anda upload bukan gambar.',
                    'mime_in' => 'File ktp yang anda upload bukan gambar.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/auth/register')->withInput();
        } else {
            // encrypt password
            $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            // Upload file ktp to spesific folder
            $user_ktp = $this->request->getFile('user_ktp');
            $namaFile = $user_ktp->getRandomName();
            $user_ktp->move('img/ktp', $namaFile);

            $model = new UserModel();
            $model->save([
                'nama' => $this->request->getPost('nama'),
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
                'password' =>  $password,
                'user_ktp' => $namaFile,
                // user_level default 2, is_active default 0
            ]);

            session()->setFlashdata('msg-auth', 'Pendaftaran berhasil. Silahkan menunggu <b>1 x 24 jam</b>, untuk aktivasi akun oleh admin.');
            return redirect()->to('/auth');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth');
    }

    public function notfound()
    {
        return view('auth/error_404');
    }
}
