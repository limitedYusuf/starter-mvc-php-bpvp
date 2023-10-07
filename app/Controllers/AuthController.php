<?php

class AuthController extends Controller
{
   protected $akun;
   public function __construct()
   {
      $this->akun = $this->model('AkunModel');
   }

   public function index()
   {
      $data = [
         'title' => 'Login Panel',
      ];

      $this->view('login', $data);
   }

   public function login()
   {
      $request = new Request();
      $validation = new Validation();
      $rules = [
         'username' => 'required',
         'password' => 'required',
         'captcha' => 'required|captcha_match',
      ];

      $customMessages = [
         'username:required' => 'Username tidak boleh kosong.',
         'password:required' => 'Password tidak boleh kosong.',
         'captcha:required' => 'Captcha tidak boleh kosong.',
         'captcha:captcha_match' => 'Captcha salah!!!',
      ];
      $validation->setCustomMessages($customMessages);

      $validation->validate($request->postAll(), [], $rules);

      $response = [];

      if ($validation->hasErrors()) {
         $response['status'] = 'error';
         $response['errors'] = $validation->getErrors();
         http_response_code(422);
      } else {
         $info = null;
         $message = null;
         $user = $this->akun->authenticate($request->post('username'), $request->post('password'));
         if($user == false) {
            $info = "failed";
            $message = "Username atau Password tidak benar!";
         } elseif($user == 'disabled') {
            $info = "disabled";
            $message = "Akun anda telah di nonaktifkan :)";
         } else {
            $info = "success";
            $message = "Berhasil login";
         }

         $response['status'] = $info;
         $response['message'] = $message;
         http_response_code(200);
      }

      header('Content-Type: application/json');
      echo json_encode($response);
   }

}