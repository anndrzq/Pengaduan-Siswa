<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\UserModel;
use App\Models\PengaduanModel;

class User extends BaseController
{
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->pengaduanModel = new PengaduanModel();
    }

    public function index()
    {
        $data = [
            'user' => $this->user,
            'title' => 'My Profile',
            'data' => $this->userModel->find($this->user['id']),
            'jml' => $this->pengaduanModel->hitungPengaduan($this->user['id']) // Jumlah pengaduan
        ];

        return view('user/profile/index', $data);
    }

    public function getProfile()
    {
        if ($this->request->isAjax()) {
            $id = $this->request->getPost('id');
            $data = $this->userModel->getUserLogin($id);
            echo json_encode($data);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan.');
        }
    }

    public function ubah_profile()
    {
        $userData = $this->request->getPost();
        $data = $this->userModel->getUserLogin($userData['id']);

        $rule_username = ($userData['username'] == $data['username'] ? 'required' : 'required|is_unique[tbl_user.username]');
        $rule_email = ($userData['email'] == $data['email'] ? 'required|valid_email' : 'required|valid_email|is_unique[tbl_user.email]');

        $rules = [
            'user_image' => [
                'rules' => 'max_size[user_image,1024]|is_image[user_image]|mime_in[user_image,image/jpg,image/jpeg,image/png,image/svg]',
                'errors' => [
                    'max_size' => 'Upload foto dengan ukuran maksimal 1MB.',
                    'is_image' => 'File yang anda upload bukan gambar.',
                    'mime_in' => 'Format yang diizinkan: jpg, jpeg, png, svg.'
                ]
            ],
            'nama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama wajib diisi.'
                ]
            ],
            'username' => [
                'rules' => $rule_username,
                'errors' => [
                    'required' => 'Username wajib diisi.',
                    'is_unique' => 'Username sudah ada.'
                ]
            ],
            'email' => [
                'rules' => $rule_email,
                'errors' => [
                    'required' => 'Email wajib diisi.',
                    'is_unique' => 'Email sudah terdaftar.',
                    'valid_email' => 'Email harus valid.'
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password wajib diisi.',
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            $errors = [
                'user_image' => $this->validation->getError('user_image'),
                'nama' => $this->validation->getError('nama'),
                'username' => $this->validation->getError('username'),
                'email' => $this->validation->getError('email'),
                'password' => $this->validation->getError('password'),
            ];

            $data = [
                'status' => FALSE,
                'errors' => $errors
            ];

            echo json_encode($data);
        } else {
            $user_image = $this->request->getFile('user_image');

            if ($user_image->getError() !== 4) {
                $namaFile = $user_image->getRandomName();
                $user_image->move('img/profile', $namaFile);
                if ($data['user_image'] != 'default.svg') {
                    unlink('img/profile/' . $data['user_image']);
                }
            } else {
                $namaFile = FALSE;
            }

            $password = strip_tags($this->request->getPost('password'));
            if (password_verify($password, $this->user['password'])) {
                $this->userModel->saveUserProfile($userData, $namaFile);
            } else {
                $errors = [
                    'password' => 'Password yang anda masukkan salah.'
                ];

                $data = [
                    'status' => FALSE,
                    'errors' => $errors
                ];

                echo json_encode($data);
            }
        }
    }

    public function ubah_password()
    {
        $rules = [
            'current-password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password lama wajib diisi.'
                ]
            ],
            'new-password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Password baru wajib diisi.',
                    'min_length' => 'Password minimal 8 karakter.'
                ]
            ],
            'confirm-password' => [
                'rules' => 'required|matches[new-password]',
                'errors' => [
                    'required' => 'Konfirmasi password wajib diisi.',
                    'matches' => 'Konfirmasi password tidak sesuai.'
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            $errors = [
                'current-password' => $this->validation->getError('current-password'),
                'new-password' => $this->validation->getError('new-password'),
                'confirm-password' => $this->validation->getError('confirm-password'),
            ];

            $data = [
                'status' => FALSE,
                'errors' => $errors
            ];

            echo json_encode($data);
        } else {
            $currentPass = strip_tags($this->request->getPost('current-password'));
            $newPass = strip_tags($this->request->getPost('new-password'));

            $userData = $this->userModel->getUserLogin($this->user['id']);
            $userPass = $userData['password'];

            if (!password_verify($currentPass, $userPass)) {
                $data = [
                    'status' => FALSE,
                    'type' => 'verify',
                    'msg' => 'Password lama salah.'
                ];

                echo json_encode($data);
            } else {
                if ($currentPass == $newPass) {
                    $data = [
                        'status' => FALSE,
                        'type' => 'verify',
                        'msg' => 'Password baru tidak boleh sama dengan password lama.'
                    ];

                    echo json_encode($data);
                } else {
                    $fix_pass = password_hash($newPass, PASSWORD_DEFAULT);

                    $this->userModel->save([
                        'id' => $this->user['id'],
                        'password' => $fix_pass
                    ]);

                    $data = [
                        'status' => TRUE,
                        'msg' => 'Password berhasil diperbarui.'
                    ];

                    echo json_encode($data);
                }
            }
        }
    }
}
