<?php

namespace App\Controllers;
use App\Models\M_p;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
date_default_timezone_set('Asia/Jakarta');
class Home extends BaseController
{
    private function log_activity($activity)
    {
		$model = new M_p();
        $data = [
            'id_user'    => session()->get('id'),
            'activity'   => $activity,
			'timestamp' => date('Y-m-d H:i:s'),
			'delete' => Null
        ];

        $model->tambah('activity', $data);
    }

	private function log_activitys($activity, $id)
    {
		$model = new M_p();
        $data = [
            'id_user'    => $id,
            'activity'   => $activity,
			'timestamp' => date('Y-m-d H:i:s'),
			'delete' => Null
        ];

        $model->tambah('activity', $data);
    }
    public function index()
    {
        $model = new M_p;
    
        // Get all children
        $result = $model->tampil('anak');
        $data['anak'] = $result ? $result : []; // Ensure $data['anak'] is always an array
        $data['totalAnak'] = count($data['anak']); // Count total children
        $this->log_activity('User membuka Dashboard');
        // Get the total number of classes
        $data['kelas'] = $model->tampil('kelas');
        $data['totalKelas'] = count($data['kelas']); // Count total classes
    
        // Get the financial report data
        $data['laporan'] = $model->tampil('laporan_keuangan');
        $currentYear = date('Y'); // Get the current year
        $where = "tahun_diterima = $currentYear AND status_pendaftaran = 'diterima'"; // Correctly format the where clause
        $data['siswaBaru'] = $model->getwherecount('anak', $where); // Get count of new students
    
        $where5 = array('id_setting' => 1);
        $data['setting'] = $model->getwhere('setting', $where5);
        $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->dashboard == 1) {
        echo view('header',$data);
        echo view('menu',$data);
        echo view('dashboard', $data);
        echo view('footer');
        }else{
            return redirect()->to('home/login');
        }
    }
    

	public function login()
	{
        $model = new M_p;
        $where5 = array('id_setting' => 1);
        $data['setting'] = $model->getwhere('setting', $where5);
		// echo view('header');
		// echo view('menu');
		echo view('login',$data);
		// echo view('footer');
	}

    public function generateCaptcha()
{
    // Create a string of possible characters
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $captcha_code = '';
    
    // Generate a random CAPTCHA code with letters and numbers
    for ($i = 0; $i < 6; $i++) {
        $captcha_code .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    // Store CAPTCHA code in session
    session()->set('captcha_code', $captcha_code);
    
    // Create an image for CAPTCHA
    $image = imagecreate(120, 40); // Increased size for better readability
    $background = imagecolorallocate($image, 200, 200, 200);
    $text_color = imagecolorallocate($image, 0, 0, 0);
    $line_color = imagecolorallocate($image, 64, 64, 64);
    
    imagefilledrectangle($image, 0, 0, 120, 40, $background);
    
    // Add some random lines to the CAPTCHA image for added complexity
    for ($i = 0; $i < 5; $i++) {
        imageline($image, rand(0, 120), rand(0, 40), rand(0, 120), rand(0, 40), $line_color);
    }
    
    // Add the CAPTCHA code to the image
    imagestring($image, 5, 20, 10, $captcha_code, $text_color);
    
    // Output the CAPTCHA image
    header('Content-type: image/png');
    imagepng($image);
    imagedestroy($image);
}

    public function aksi_login()
    {
        // Check internet connection
        if (!$this->checkInternetConnection()) {
            // If there is no connection, check the image CAPTCHA
            $captcha_code = $this->request->getPost('captcha_code');
            if (session()->get('captcha_code') !== $captcha_code) {
                session()->setFlashdata('toast_message', 'Invalid CAPTCHA');
                session()->setFlashdata('toast_type', 'danger');
                return redirect()->to('home/login');
            }
        } else {
            // If there is a connection, check Google reCAPTCHA
            $recaptchaResponse = trim($this->request->getPost('g-recaptcha-response'));
            $secret = '6LeKfiAqAAAAAFkFzd_B9MmWjX76dhdJmJFb6_Vi'; // Replace with your Secret Key
            $credential = array(
                'secret' => $secret,
                'response' => $recaptchaResponse
            );
    
            $verify = curl_init();
            curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($verify, CURLOPT_POST, true);
            curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($credential));
            curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($verify);
            curl_close($verify);
    
            $status = json_decode($response, true);
    
            if (!$status['success']) {
                session()->setFlashdata('toast_message', 'Captcha validation failed');
                session()->setFlashdata('toast_type', 'danger');
                return redirect()->to('home/login');
            }
        }
    
        // Continue with the normal login process
        $u = $this->request->getPost('username');
        $p = $this->request->getPost('password');
    
        $where = array(
            'nama' => $u,
            'password' => md5($p),
            'verifikasi_status' => 1
        );
        $model = new M_p;
        $cek = $model->getWhere('user', $where);
    
        if ($cek) {
            $this->log_activitys('User Melakukan Login', $cek->id_user);
            session()->set('nama', $cek->nama);
            session()->set('id', $cek->id_user);
            session()->set('level', $cek->id_level);
            return redirect()->to('home/');
        } else {
            session()->setFlashdata('toast_message', 'Invalid login credentials');
            session()->setFlashdata('toast_type', 'danger');
            return redirect()->to('home/login');
        }
    }
    
    public function checkInternetConnection()
    {
        $connected = @fsockopen("www.google.com", 80);
        if ($connected) {
            fclose($connected);
            return true;
        } else {
            return false;
        }
    }
    
public function pendaftaran(){
	$model = new M_p();
	$paket['paket'] = $model->tampil('paket');
    
	$where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
 
        echo view('header',$data);
        echo view('menu',$data);
		echo view('pendaftaran',$paket);
		echo view('footer');

}

public function aksi_pendaftaran() {
    $model = new M_p();

    // Mengambil data dari request
    $nama = $this->request->getPost('nama');
    $tanggal = $this->request->getPost('tanggal');
    $paket = $this->request->getPost('paket');
    $nama_ortu = $this->request->getPost('nama_ortu');
    $email = $this->request->getPost('email_ortu');
    $no_wa = $this->request->getPost('no_wa');
    $uploadedFile1 = $this->request->getFile('kartukeluarga');  // Ambil file pertama
    $uploadedFile2 = $this->request->getFile('akta'); // Ambil file kedua
    $uploadedFile3 = $this->request->getFile('buktipembayaran'); // Ambil file kedua
    // Data untuk anak
    $data = [
        'id_paket' => $paket,
        'nama_anak' => $nama,
        'tanggal_lahir' => $tanggal,
        'status_pendaftaran' => 'pending',
    ];

    // Data untuk ortu
    $data1 = [
        'username' => $nama_ortu,
        'email' => $email,
        'no_wa'=> $no_wa
    ];

    $data2 = [
        'kategori' => 'Pendaftaran Siswa',
        'pendapatan' => 150000,
        'tanggal_pendapatan' => date('Y-m-d H:i:s'),
    ];

    // Proses upload file pertama
    if ($uploadedFile1 && $uploadedFile1->isValid()) {
        $foto = $uploadedFile1->getName();
        $model->upload($uploadedFile1);  
        $data['kartu_keluarga'] = $foto;  
    }

    // Proses upload file kedua
    if ($uploadedFile2 && $uploadedFile2->isValid()) {
        $foto2 = $uploadedFile2->getName();
        $model->upload($uploadedFile2);  
        $data['akta_lahir'] = $foto2;  
    }

    if ($uploadedFile3 && $uploadedFile3->isValid()) {
        $foto3 = $uploadedFile2->getName();
        $model->upload($uploadedFile3);  
        $data['bukti_pembayaran'] = $foto3;  
    }

    $model->tambah('pendapatan', $data2);

    // Menyimpan data anak dan mendapatkan id_anak
    $model->tambahid('anak', $data);
    $newest_id_anak = $model->insertID();  // Ambil id_anak terbaru

    // Menyimpan data ortu dan mendapatkan id_ortu
    $model->tambahid('pendaftaran', $data1);
    $newest_id_ortu = $model->insertID();  // Ambil id_ortu terbaru

	$where1 = array('id_anak' =>$newest_id_anak);
	$where2 = array('id_ortu' => $newest_id_ortu);
    // Update data anak dengan id_ortu

	$data3 = [
        'id_ortu' => $newest_id_ortu
    ];

	$data4 = [
        'id_anak' => $newest_id_anak
    ];
    $model->edit('anak', $data3, $where1);

    // Update data ortu dengan id_anak
    $model->edit('pendaftaran', $data4, $where2);

    return redirect()->to('home/index');
}


public function spp(){
	$model = new M_p();
    $this->log_activity('User membuka SPP');
    $where1 = array('anak.id_ortu' => session()->get('id'));
$anak['anak'] = $model->getwhere('anak',  $where1);

// Pastikan untuk memeriksa apakah $anak['anak'] tidak kosong dan ambil id_anak
if (!empty($anak['anak'])) {
    // Mengambil id_anak dari elemen pertama
    $id_anak = $anak['anak']->id_anak; // Pastikan ini sesuai dengan nama kolom yang benar
} else {
    // Tangani kasus ketika tidak ada anak
    $id_anak = null; // atau berikan nilai default sesuai kebutuhan
}
$where = array('spp.id_anak' => $id_anak);
$siswa['siswa'] = $model->join2wheres1('anak', 'user','user.id_user = anak.id_ortu','spp','spp.id_anak = anak.id_anak',$where);
   
$siswa['pembayaran_buku'] = $this->filterPembayaran($siswa['siswa'], 'buku');
    $siswa['pembayaran_seragam'] = $this->filterPembayaran($siswa['siswa'], 'seragam');
    $siswa['pembayaran_tahunan'] = $this->filterPembayaran($siswa['siswa'], 'tahunan');
    $siswa['pembayaran_spp'] = $this->filterPembayaran($siswa['siswa'], 'spp');

    // Cek apakah pembayaran buku, seragam, dan tahunan sudah lunas
    $siswa['semua_lunas'] = $this->cekSemuaLunas($siswa);

    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->dashboard == 1) {
    echo view('header',$data);
    echo view('menu',$data);
		echo view('spp',$siswa);
		echo view('footer');
    }else{
        return redirect()->to('home/login');
    }
}

public function StatusSPP(){
	$model = new M_p();
    $this->log_activity('User membuka Status SPP');
$where = array('status' => 'check');
$siswa['siswa'] = $model->join2wheres1('anak', 'user','user.id_user = anak.id_ortu','spp','spp.id_anak = anak.id_anak',$where);
   
$where5 = array('id_setting' => 1);
$data['setting'] = $model->getwhere('setting', $where5);
$where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->data == 1) {
echo view('header',$data);
echo view('menu',$data);
		echo view('statusspp',$siswa);
		echo view('footer');
    }else{
        return redirect()->to('home/login');
    }
}

public function DetailStatus($id){
    $model = new M_p();
    $this->log_activity('User membuka Detail Status SPP');
    $where = array('id_spp' => $id);
    $siswa['siswa'] = $model->join2whererow('anak', 'user','user.id_user = anak.id_ortu','spp','spp.id_anak = anak.id_anak',$where);
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->data == 1) {
    echo view('header',$data);
    echo view('menu',$data);
		echo view('detailstatus',$siswa);
		echo view('footer');
    }else{
        return redirect()->to('home/login');
    }
}

public function aksi_lunas($id) {
    $model = new M_p();
    $where = ['id_spp' => $id];
    
    // Retrieve the SPP data
    $sppData = $model->getwhere('spp', $where);
    
    if (!$sppData) {
        // Handle the case where the SPP record doesn't exist
        return redirect()->to('home/StatusSPP')->with('error', 'Data SPP tidak ditemukan.');
    }

    $harga = $sppData->harga;

    // Prepare the data for updating the SPP status
    $dataUpdate = [
        'status' => 'lunas',
        'tanggal_pembayaran' => date('Y-m-d H:i:s'),
    ];

    // Prepare the data for the pendapatan table
    $dataPendapatan = [
        'kategori' => 'SPP',
        'pendapatan' => $harga,
        'tanggal_pendapatan' => date('Y-m-d H:i:s'),
    ];

    // Update the SPP status
    $model->edit('spp', $dataUpdate, $where);

    // Add the new pendapatan record
    $model->tambah('pendapatan', $dataPendapatan);

    // Use the custom model method to check if there's an existing record in laporan_keuangan
    $currentMonth = date('m'); // Current month
    $currentYear = date('Y'); // Current year
    $existingLaporan = $model->getLaporanByMonthYear($currentMonth, $currentYear);

    if ($existingLaporan) {
        // Update total_pendapatan
        $model->edit('laporan_keuangan', [
            'total_pendapatan' => $existingLaporan->total_pendapatan + $harga,
        ], [
            'id_laporan' => $existingLaporan->id_laporan // Change 'id' to 'id_laporan'
        ]);
    } else {
        // Insert a new record if none exists
        $model->tambah('laporan_keuangan', [
            'total_pendapatan' => $harga,
            'tanggal' => date('Y-m-01'), // Set to the first day of the current month
        ]);
    }
    $this->log_activity('User membayar SPP');
    return redirect()->to('home/StatusSPP')->with('success', 'Pembayaran berhasil diproses.');
}



public function aksi_ditolak($id){
    $model = new M_p();
    $where = array('id_spp' => $id);

            $data = [
                'status' => 'ditolak',
            ];

    $model->edit('spp',$data,$where);
    $this->log_activity('User Pembayaran SPP Siswa Ditolak');
    return redirect()->to('home/StatusSPP');
}
public function filterPembayaran($pembayaran, $untuk) {
    // Filter pembayaran sesuai dengan jenis
    return array_filter($pembayaran, function($item) use ($untuk) {
        return $item->pembayaran_untuk == $untuk;
    });
}

public function cekSemuaLunas($siswa) {
    // Cek apakah semua pembayaran buku, seragam, dan tahunan sudah lunas
    $semua_lunas = true; // Asumsi semua lunas

    // Jika ada satu saja yang belum lunas, maka $semua_lunas menjadi false
    foreach (['pembayaran_buku', 'pembayaran_seragam', 'pembayaran_tahunan'] as $tipe) {
        if (!empty($siswa[$tipe])) {
            foreach ($siswa[$tipe] as $item) {
                if ($item->status != 'lunas') {
                    $semua_lunas = false; 
                    break; // Berhenti jika ditemukan pembayaran yang belum lunas
                }
            }
        }
    }
    return $semua_lunas;
}

public function DataPendaftaran(){
	$model = new M_p();
	$siswa['siswa'] = $model->join('anak','paket','anak.id_paket = paket.id_paket');
    $this->log_activity('User membuka Data Pendaftaran');
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->data == 1) {
    echo view('header',$data);
    echo view('menu',$data);
		echo view('datapendaftaran',$siswa);
		echo view('footer');
    }else{
        return redirect()->to('home/login');
    }
}

public function DetailPendaftaran($id){
	$model = new M_p();
	$where = array('id_anak' => $id);
	$siswa['siswa'] = $model->join1where1row('anak', 'paket','paket.id_paket = anak.id_paket', $where);
	$siswa['paket'] = $model->tampil('paket');	
    $this->log_activity('User membuka Detail Pendaftaran');
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->data == 1) {
    echo view('header',$data);
    echo view('menu',$data);
		echo view('detailpendaftaran',$siswa);
		echo view('footer');
    }else{
        return redirect()->to('home/login');
    }
}

public function diterima($id) {
    $model = new M_p();
    
    // Ambil email orang tua berdasarkan id
    $where = ['anak.id_anak' => $id];
    $siswa['siswa'] = $model->ambilemail('anak', 'pendaftaran', 'pendaftaran.id_ortu = anak.id_ortu', $where); 
    $this->log_activity('User menerima Siswa');
    // Cek apakah email ditemukan
    if (empty($siswa['siswa'])) {
        echo "Email orang tua tidak ditemukan.";
        return;
    }

    // Ambil email orang tua
    $emailOrtu = $siswa['siswa'][0]->email; // Asumsikan email ada di kolom 'email'

    // Load Composer's autoloader
    require ROOTPATH . 'vendor/autoload.php';

    $mail = new PHPMailer(true);
    try {
        // Ambil data pendaftaran
        $wherePendaftaran = ['pendaftaran.id_anak' => $id];
        $datas = $model->getWherearray('pendaftaran', $wherePendaftaran);
        $whereAnak = ['anak.id_anak' => $id];
        $data_anak = $model->getWherearray('anak', $whereAnak);
        $nama_anak = $data_anak['nama_anak'];
        $id_anak = $data_anak['id_anak'];
        $id_paket = $data_anak['id_paket'];
        $whereOrtu = array('id_ortu' => $datas['id_ortu']);
        // Lakukan pemetaan kolom jika nama kolom tidak sama
        if ($datas) {
            $nama_otomatis = '2000' . $datas['id_anak'];
            $password_otomatis = md5($nama_otomatis);
        
            // Data user yang akan disimpan
            $dataUser = [   
            'id_user' => $datas['id_ortu'],
                'nama' => $nama_otomatis,     
                'email' => $datas['email'],
                'no_wa' => $datas['no_wa'],
                'password' => md5($nama_otomatis), 
                'verifikasi_status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'id_level' => 3
            ];

            $dataa = [   
                'tahun_diterima' => date('Y'),
                'status_pendaftaran' => 'diterima'
            ];
            
            $wheres = array('id_anak' => $id_anak);
            // Tambahkan ke tabel user
            $model->tambah('user', $dataUser);
            $model->edit('anak', $dataa, $whereAnak);
            $model->hapus('pendaftaran', $whereOrtu);

            $this->buatspp($id_anak,$id_paket);
            // Kirim email pemberitahuan
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = getenv('EMAIL_USERNAME'); // Mengambil dari variabel lingkungan
            $mail->Password = getenv('EMAIL_PASSWORD'); // Mengambil dari variabel lingkungan
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587; // TLS port

            // Recipients
            $mail->setFrom('zentosph@gmail.com', 'Sekolah ZentoSPH');
            $mail->addAddress($emailOrtu); // Tambah alamat email orang tua

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Status Pendaftaran Siswa';
            $mail->Body = "
                <p>Kabar baik! Pendaftaran anak Anda $nama_anak telah diterima.</p>
                <p>Anda dapat login menggunakan:</p>
                <p>Username: <strong>$nama_otomatis</strong></p>
                <p>Password: <strong>$nama_otomatis</strong></p>
                <p>Silahkan login <a href='".base_url('home/login')."'>disini!</a></p>
            ";

            $mail->send();

            return redirect()->to('home/DataPendaftaran'); 
        } else {
            // Handle jika tidak ada data
            echo "Data tidak ditemukan";
        }
    } catch (Exception $e) {
        echo "Pesan tidak dapat dikirim. Kesalahan Mailer: {$mail->ErrorInfo}";
    }
}

public function ditolak($id) {
    $model = new M_p();
    
    // Ambil email orang tua berdasarkan id
    $where = ['anak.id_anak' => $id];
    $siswa['siswa'] = $model->ambilemail('anak', 'pendaftaran', 'pendaftaran.id_ortu = anak.id_ortu', $where); 
    $this->log_activity('User menolak Siswa');
    // Cek apakah email ditemukan
    if (empty($siswa['siswa'])) {
        echo "Email orang tua tidak ditemukan.";
        return;
    }

    // Ambil email orang tua
    $emailOrtu = $siswa['siswa'][0]->email; // Asumsikan email ada di kolom 'email'

    // Load Composer's autoloader
    require ROOTPATH . 'vendor/autoload.php';

    $mail = new PHPMailer(true);
    try {
        // Ambil data pendaftaran
        $wherePendaftaran = ['pendaftaran.id_anak' => $id];
        $datas = $model->getWherearray('pendaftaran', $wherePendaftaran);
        $whereAnak = ['anak.id_anak' => $id];
        $data_anak = $model->getWherearray('anak', $whereAnak);
        $nama_anak = $data_anak['nama_anak'];
        $id_anak = $data_anak['id_anak'];
        $id_paket = $data_anak['id_paket'];
        $whereOrtu = array('id_ortu' => $datas['id_ortu']);

        // Hapus data
        $model->hapus('anak', $whereAnak);
        $model->hapus('pendaftaran', $whereOrtu);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = getenv('EMAIL_USERNAME'); // Mengambil dari variabel lingkungan
        $mail->Password = getenv('EMAIL_PASSWORD'); // Mengambil dari variabel lingkungan
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // TLS port

        // Recipients
        $mail->setFrom('zentosph@gmail.com', 'Sekolah ZentoSPH');
        $mail->addAddress($emailOrtu); // Tambah alamat email orang tua

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Pemberitahuan Hasil Pendaftaran Siswa';
        $mail->Body = "
            <p>Yth. Orang Tua/Wali dari $nama_anak,</p>
            <p>Dengan sangat menyesal, kami ingin memberi tahu bahwa setelah proses seleksi, kami tidak dapat menerima pendaftaran $nama_anak di sekolah kami untuk tahun ajaran ini.</p>
            <p>Keputusan ini diambil setelah mempertimbangkan berbagai aspek, dan kami menghargai usaha serta minat Anda untuk menjadi bagian dari komunitas sekolah kami.</p>
            <p>Kami berharap yang terbaik bagi $nama_anak di masa depan dan dalam semua usahanya. Terima kasih atas kepercayaan Anda kepada sekolah kami.</p>
            <p>Hormat kami,<br>Sekolah ZentoSPH</p>
        ";

        $mail->send();

        return redirect()->to('home/DataPendaftaran');

    } catch (Exception $e) {
        echo "Pesan tidak dapat dikirim. Kesalahan Mailer: {$mail->ErrorInfo}";
    }
}


public function buatspp($id_anak, $id_paket) {
    $model = new M_p();

    // Tanggal awal masuk sekolah
    $tanggal_awal = strtotime('2024-06-20'); // Sesuaikan tanggal awal
    $tanggal_jatuh_tempo = strtotime('10-' . date('m-Y', $tanggal_awal)); // Set tanggal 10 bulan pertama

    // Ambil data paket berdasarkan id_paket
    $wherePaket = ['id_paket' => $id_paket];
    $paket = $model->getWherearray('paket', $wherePaket);
    $harga_spp = $paket['harga']; // Ambil harga dari paket

    // Jika tanggal masuk sekolah melewati tanggal 10, atur ke bulan depan
    if ($tanggal_awal > $tanggal_jatuh_tempo) {
        $tanggal_jatuh_tempo = strtotime('+1 month', $tanggal_jatuh_tempo);
    }

    // Maksimal 24 bulan
    for ($i = 0; $i < 24; $i++) {
        // Data SPP
        $dataSPP = [
            'id_paket' => $id_paket,
            'id_anak' => $id_anak,
            'tanggal_jatuh_tempo' => date('Y-m-d', $tanggal_jatuh_tempo),
            'pembayaran_untuk' => 'spp',
            'harga' => $harga_spp, // Harga SPP dari paket
            'status' => 'belum'
        ];

        // Tambahkan ke database
        $model->tambah('spp', $dataSPP);

        // Tambah bulan berikutnya
        $tanggal_jatuh_tempo = strtotime('+1 month', $tanggal_jatuh_tempo);
    }

    // Tambah data buku dan seragam (sekali saja, tidak per bulan)
    $pembayaranLain = [
        [
            'id_paket' => $id_paket,
            'id_anak' => $id_anak,
            'tanggal_jatuh_tempo' => date('Y-m-d', $tanggal_awal), // Tanggal awal
            'pembayaran_untuk' => 'buku',
            'harga' => 1500000,
            'status' => 'belum'
        ],
        [
            'id_paket' => $id_paket,
            'id_anak' => $id_anak,
            'tanggal_jatuh_tempo' => date('Y-m-d', $tanggal_awal), // Tanggal awal
            'pembayaran_untuk' => 'seragam',
            'harga' => 1500000,
            'status' => 'belum'
        ]
    ];

    // Cek jenis paket untuk menambahkan pembayaran tahunan
    if ($paket['paket'] === 'Biasa') { // Pastikan 'nama_paket' sesuai dengan kolom di tabel paket
        $pembayaranLain[] = [
            'id_paket' => $id_paket,
            'id_anak' => $id_anak,
            'tanggal_jatuh_tempo' => date('Y-m-d', $tanggal_awal), // Tanggal awal
            'pembayaran_untuk' => 'tahunan',
            'harga' => 1200000,
            'status' => 'belum'
        ];
    }

    // Tambahkan ke database
    foreach ($pembayaranLain as $data) {
        $model->tambah('spp', $data);
    }
}


public function bayar($id){
    $model = new M_p();
    $where = array('id_spp' => $id);
    $this->log_activity('User membuka Bayar SPP');
    $data['spp']  = $model->getWhere('spp', $where);
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->dashboard == 1) {
    echo view('header',$data);
    echo view('menu',$data);
    echo view('bayar',$data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function aksi_bayar() {
    $model = new M_p();
    $bukti = $this->request->getFile('buktibayar');
    $id = $this->request->getPost('id');

    if ($bukti && $bukti->isValid() && !$bukti->hasMoved()) {
        // Validasi bahwa file adalah PDF
        if ($bukti->getExtension() == 'pdf') {
            $foto = $bukti->getName();
            $model->uploadspp($bukti);  // Pastikan upload file sudah sesuai dengan function uploadspp di model

            $where = array('id_spp' => $id);
            $data = [
                'status' => 'check',
                'bukti_pembayaran' => $foto
            ];
            $model->edit('spp', $data, $where);
            return redirect()->to('home/spp')->with('success', 'Pembayaran berhasil.');
        } else {
            // Redirect dengan pesan error jika bukan PDF
            return redirect()->back()->with('error', 'File yang diunggah harus berformat PDF.');
        }
    } else {
        $data = [
            'status' => 'check',
        ];
        $where = array('id_spp' => $id);
        $model->edit('spp', $data, $where);
        return redirect()->to('home/spp')->with('success', 'Pembayaran berhasil tanpa upload bukti.');
    }
}

public function Tugas() {
    $model = new M_p();
    
    // Check user level
    $userLevel = session()->get('level');
    
    if ($userLevel == 1) {
        // Level 1: Fetch all classes
        $data['kelas'] = $model->tampil('kelas'); // Fetch all classes
    } else {
        // Other levels: Fetch classes for the specific wali kelas
        $where = array('id_wali_kelas' => session()->get('id'));
        $data['kelas'] = $model->getWhere('kelas', $where); // Fetch specific classes
    }
    
    $this->log_activity('User membuka Tugas');
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->apps == 1) {

    echo view('header',$data);
    echo view('menu',$data);
    echo view('tugas', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}


public function aksi_tugas() {
    $model = new M_p();
    
    // Mendapatkan data dari form
    $tugas = $this->request->getFile('tugas'); // File PDF tugas
    $deskripsi = $this->request->getPost('deskripsi');
    $subject = $this->request->getPost('mengenai'); 
    $tanggal = $this->request->getPost('tanggal'); 
    
    // Mendapatkan ID kelas dari session atau hidden input
    $idKelas = $this->request->getPost('id_kelas');

    // Menentukan siswa berdasarkan level pengguna
    if (session()->get('level') == 1) {
        $kirimKe = $this->request->getPost('kirim_ke'); // Menerima pilihan kirim
        
        if ($kirimKe === 'semua') {
            $siswa['siswa'] = $model->join1('anak', 'user', 'anak.id_ortu = user.id_user');
        } elseif ($kirimKe === 'per_kelas') {
            $where = array('anak.id_kelas' => $this->request->getPost('id_kelas')); // Ambil id_kelas jika per kelas
            $siswa['siswa'] = $model->join1where1('anak', 'user', 'anak.id_ortu = user.id_user', $where);
        } else {
            return redirect()->back()->with('error', 'Pilih metode pengiriman tugas.');
        }
    } else {
        // Level 2, ambil siswa berdasarkan id_kelas dari session
        $where = array('anak.id_kelas' => $idKelas);
        $siswa['siswa'] = $model->join1where1('anak', 'user', 'anak.id_ortu = user.id_user', $where);

    }

    // Menginisialisasi PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Konfigurasi SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = getenv('EMAIL_USERNAME');
        $mail->Password = getenv('EMAIL_PASSWORD');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Informasi pengirim
        $mail->setFrom('zentosph@gmail.com', 'Sekolah ZentoSPH'); 

        // Melampirkan file tugas (PDF) menggunakan model
        $filePath = $model->uploadtugas($tugas); // Pindahkan file dan dapatkan path
        if ($filePath) {
            $mail->addAttachment($filePath); // Menambahkan file lampiran ke email
        }

        // Mengirim email ke setiap orang tua yang terdaftar
        $emailList = [];
        foreach ($siswa['siswa'] as $row) {
            $emailOrtu = $row->email; // Mengakses properti sebagai objek
            $nama_anak = $row->nama_anak; // Mengakses properti sebagai objek
            $mail->addAddress($emailOrtu);
            $emailList[] = $emailOrtu;
            // Konten email
            $mail->isHTML(true);
            $mail->Subject = 'Pemberitahuan Tugas: ' . $subject;
            $mail->Body = "
                <p>Yth. Orang Tua/Wali $nama_anak,</p>
                <p>Ada tugas baru mengenai <strong>$subject</strong> yang perlu diselesaikan oleh $nama_anak.</p>
                <p>Deskripsi tugas: <br> $deskripsi </p>
                <p>Kumpul sebelum tanggal: <br> $tanggal </p>
                <p>Silakan lihat lampiran untuk rincian lebih lanjut.</p>
            ";

            // Mengirim email
            $mail->send();
            $mail->clearAddresses(); // Reset address agar tidak berulang ke penerima sebelumnya
        }

        return redirect()->to('home/Tugas')->with('success', 'Tugas berhasil dikirim.');

    } catch (Exception $e) {
        return redirect()->back()->with('error', "Pesan tidak dapat dikirim. Kesalahan: {$mail->ErrorInfo}");
    }
}





public function kelas(){
    $model = new M_p();
    // Ambil data kelas berdasarkan id_wali_kelas dari sesi
$where = array('id_wali_kelas' => session()->get('id'));
$data['kelas'] = $model->tampilwhere('kelas', $where);
$this->log_activity('User membuka Kelas');
// Jika data kelas ditemukan, ambil id_kelas
if (!empty($data['kelas'])) {
    $id_kelas = $data['kelas'][0]->id_kelas; // Asumsi hanya ada satu kelas untuk wali kelas tersebut

    // Hitung jumlah siswa di tabel anak berdasarkan id_kelas
    $where_anak = array('id_kelas' => $id_kelas);
    $data['jumlah_siswa'] = $model->tampilcount('anak', $where_anak);
} else {
    $data['jumlah_siswa'] = 0; // Jika tidak ada kelas ditemukan, set jumlah siswa ke 0
}

$where5 = array('id_setting' => 1);
$data['setting'] = $model->getwhere('setting', $where5);
$where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->apps == 1) {
echo view('header',$data);
echo view('menu',$data);
    echo view('kelas',$data);
    echo view('footer');  
 }else{
    return redirect()->to('home/login');
}
}

public function absen($id_kelas) {
    $model = new M_p();
    $where = array('anak.id_kelas' => $id_kelas);
    $where1 = array('tanggal' => date("Y-m-d"));
    $data['kelas'] = $model->join1where2('anak', 'absen','absen.id_anak = anak.id_anak',$where, $where1);
    $where2 = array('id_kelas' => $id_kelas);
    $data['absen'] = $model->tampilwhere2('absen', $where2,$where1);
    $tanggalHariIni = date("Y-m-d"); // Dapatkan tanggal hari ini
    $this->log_activity('User membuka Absen');
    // Cek apakah sudah ada catatan absensi untuk hari ini untuk kelas ini
    $existingAbsen = $model->checkAttendanceExists($id_kelas, $tanggalHariIni);

    if ($existingAbsen == 0) {
        // Jika tidak ada catatan absensi, tambahkan catatan untuk semua siswa

        // Dapatkan semua siswa dalam kelas ini
        $students = $model->tampilwhere('anak', $where);

        // Siapkan data absensi untuk setiap siswa
        $attendanceData = [];
        foreach ($students as $student) {
            $attendanceData[] = [
                'id_anak' => $student->id_anak,
                'id_kelas' => $id_kelas,
                'tanggal' => $tanggalHariIni,
                'status_absen' => null // Status default
            ];
        }

        // Masukkan semua catatan absensi ke dalam tabel 'absen'
        if (!empty($attendanceData)) {
            $insertSuccess = $model->tambahBatch('absen', $attendanceData);
            if (!$insertSuccess) {
                // Tampilkan pesan error jika gagal menambah data
                echo "Gagal menambah data absensi.";
            }
        }
    }
return redirect()->to('home/absensi/'.$id_kelas);
    // Tampilkan tampilan
    
}

public function absensi($id_kelas){
    $model = new M_p();
    $where = array('anak.id_kelas' => $id_kelas);
    $where1 = array('tanggal' => date("Y-m-d"));
    $data['kelas'] = $model->join1where2('anak', 'absen','absen.id_anak = anak.id_anak',$where, $where1);
    $where2 = array('id_kelas' => $id_kelas);
    $data['absen'] = $model->tampilwhere2('absen', $where2,$where1);
    $tanggalHariIni = date("Y-m-d"); // Dapatkan tanggal hari ini
    $this->log_activity('User melakukan Absensi');
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->apps == 1) {
    echo view('header',$data);
    echo view('menu',$data);
    echo view('absen', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}
public function addAttendanceOrReason()
{
    // Load model
    $Model = new M_p();

    // Retrieve data from the request
    $id_anak = $this->request->getPost('id_anak');
    $status = $this->request->getPost('status');
    $alasan = $this->request->getPost('alasan'); // Optional reason

    // Validate input
    if (empty($id_anak) || empty($status)) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'ID anak dan status diperlukan.']);
    }

    // Prepare data for saving
    $data = [
        'id_anak' => $id_anak,
        'status_absen' => $status,
        'alasan' => $status === 'tidak hadir' ? $alasan : null, // Save reason if not present
        'tanggal' => date('Y-m-d'), // Save current attendance date
    ];
    
    $where = array('id_anak' => $id_anak, 'tanggal' => date('Y-m-d'));
    $existingRecord = $Model->tampilwhere2Row('absen', $where);


    // If data exists, update it
    if ($existingRecord) {
        $where3 = array('id_absen' => $existingRecord['id_absen']);
        $Model->edit('absen', $data, $where3);
    } else {
        // Insert new data if none exists
        $Model->tambah('absen', $data);
    }

    // Return success response
    return $this->response->setJSON(['status' => 'success', 'message' => 'Data absensi berhasil disimpan.']);
}



public function logout()
{
    $this->log_activity('User Logout');
    session()->destroy();
    return redirect()->to('home/login');
}

public function Penilaian(){
    $model = new M_p();
    $where1 = array('id_wali_kelas'=> session()->get('id'));
    $data['id_kelas'] = $model->getwhere('kelas',$where1);
    if (session()->get('level') == 2) {
    $where = array('id_kelas' => $data['id_kelas']->id_kelas);
    $data['kelas'] = $model->tampilwhere('anak', $where);
    }else{
        $data['kelas'] = $model->tampil('anak');
    }
    $this->log_activity('User membuka Penilaian');
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->apps == 1) {
    echo view('header',$data);
    echo view('menu',$data);
    echo view('siswakelas', $data);
    echo view('footer');
 }else{
    return redirect()->to('home/login');
}
}

public function NilaiSiswa($id){
    $model = new M_p();
    $where1 = array('id_anak'=> $id);
    $data['anak'] = $model->getwhere('anak',$where1);
    $data['mapel'] = $model->tampil('mapel');
    $this->log_activity('User membuka Nilai Siswa');
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->apps == 1) {
    echo view('header',$data);
    echo view('menu',$data);
    echo view('nilaisiswa', $data);
    echo view('footer');
    
 }else{
    return redirect()->to('home/login');
}
}

public function DetailNilai($id){
    $model = new M_p();
    $where1 = array('id_anak'=> $id);
    $data['anak'] = $model->getwhere('anak',$where1);
    $data['mapel'] = $model->join2where1('nilai', 'anak','nilai.id_siswa = anak.id_anak','mapel','nilai.id_mapel = mapel.id_mapel',$where1);
    $this->log_activity('User membuka Detail Nilai Siswa');
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->apps == 1) {
    echo view('header',$data);
    echo view('menu',$data);
    echo view('detailnilai', $data);
    echo view('footer');
    // print_r($data['mapel']);
}else{
    return redirect()->to('home/login');
}
}

public function aksi_t_nilai(){
    $model = new M_p();
    $id = $this->request->getPost('id');
    $mapel = $this->request->getPost('mapel');
    $tipenilai = $this->request->getPost('tipe_nilai');
    $nilai = $this->request->getPost('nilai');
    $tahun = $this->request->getPost('tahun_ajaran');
    $this->log_activity('User menambahkan Nilai Siswa');
    // Loop through each mapel and nilai
    foreach ($mapel as $index => $mapelId) {
        $data = [
            'id_siswa' => $id,
            'id_mapel' => $mapelId,
            'tipe_nilai' => $tipenilai, 
            'nilai' => $nilai[$index], 
            'tahun_ajaran' => $tahun
        ];

        // Save each entry
        $model->tambah('nilai', $data);
    }

    return redirect()->to('home/Penilaian');
}

public function Raport($id){
    $model = new M_p();
    $where1 = array('id_anak'=> $id);
    $data['anak'] = $model->getwhere('anak',$where1);
    $data['mapel'] = $model->join3where1('nilai', 'anak','nilai.id_siswa = anak.id_anak','mapel','nilai.id_mapel = mapel.id_mapel','kelas','kelas.id_Kelas = anak.id_kelas',$where1);
    $this->log_activity('User melihat Raport Siswa');
    // echo view('header');
    // echo view('menu');
    echo view('raport', $data);
    // echo view('footer');
    // print_r($data['mapel']);
}

public function laporan(){
    $this->log_activity('User membuka Laporan');
    $model = new M_p();
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->data == 1) {
    echo view('header',$data);
    echo view('menu',$data);
    echo view('laporan', );
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}
public function laporanKeuangan() {
    $model = new M_p();

    // Tentukan rentang tanggal, misalnya dari form input
    $startDate = $this->request->getPost('start_date');
    $endDate = $this->request->getPost('end_date');

    // Ambil data laporan keuangan
    $data['laporan'] = $model->getLaporanKeuangan($startDate, $endDate);
    $this->log_activity('User membuka Laporan Keuangan');
    // Tambahkan $startDate dan $endDate ke array $data
    $data['startDate'] = $startDate;
    $data['endDate'] = $endDate;

    // Tampilkan halaman laporan
    // echo view('header');
    // echo view('menu');
    echo view('laporan_keuangan', $data);
    // echo view('footer');
    // print_r($data);
}

public function laporanexcel() {
    $model = new M_p();

    // Tentukan rentang tanggal dari parameter URL (GET request)
    $startDate = $this->request->getGet('start_date');
    $endDate = $this->request->getGet('end_date');

    // Ambil data laporan keuangan
    $data['laporan'] = $model->getLaporanKeuangan($startDate, $endDate);
    $this->log_activity('User membuka Laporan Keuangan');
    // Tambahkan $startDate dan $endDate ke array $data
    $data['startDate'] = $startDate;
    $data['endDate'] = $endDate;

    // Tampilkan halaman laporan
    echo view('laporan_keuangan_excel', $data);
}

public function laporanpdf() {
    $model = new M_p();

    // Tentukan rentang tanggal dari parameter URL (GET request)
    $startDate = $this->request->getGet('start_date');
    $endDate = $this->request->getGet('end_date');
    $this->log_activity('User membuka Laporan Keuangan');
    // Ambil data laporan keuangan
    $data['laporan'] = $model->getLaporanKeuangan($startDate, $endDate);
    
    // Tambahkan $startDate dan $endDate ke array $data
    $data['startDate'] = $startDate;
    $data['endDate'] = $endDate;

    // Tampilkan halaman laporan
    echo view('laporan_keuangan_pdf', $data);
}

public function User(){
    $model = new M_p();
    $where = array('deleted' => Null);
    $data['user'] = $model->tampilwhere('user', $where);
    $this->log_activity('User membuka User');
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->data == 1) {
    echo view('header',$data);
    echo view('menu',$data);
    echo view('user', $data);
    echo view('footer');
    // print_r($data['mapel']);
}else{
    return redirect()->to('home/login');
}
}

public function euser($id){
    $model = new M_p();
    $where = array('id_user' => $id);
    $data['user'] = $model->getwhere('user', $where);
    $this->log_activity('User membuka Edit User');
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->data == 1) {
    echo view('header',$data);
    echo view('menu',$data);
    echo view('euser', $data);
    echo view('footer');
    // print_r($data['mapel']);
}else{
    return redirect()->to('home/login');
}
}

public function aksi_euser()
{
    $model = new M_p();
    
    $nama = $this->request->getPost('nama');
    $email = $this->request->getPost('email');
    $no_wa = $this->request->getPost('no_wa');
    $id_level = $this->request->getPost('id_level');
    $id = $this->request->getPost('id'); // ID user yang sedang diedit
    $this->log_activity('User mengedit User');
    // Kondisi untuk where clause
    $where = ['id_user' => $id];
    
    // Log aktivitas
    // $this->log_activity('User Mengupdate Data user');

        // Data tanpa foto yang diunggah
        $data = [
            'nama' => $nama,
            'email' => $email,
            'no_wa' => $no_wa,
            'id_level' => $id_level,
            'update_by' => session()->get('id'),
            'update_at' => date('Y-m-d H:i:s')
        ];
    
    
    // Update data ke tabel user
    $model->edit('user', $data, $where);
    
    // Redirect kembali ke halaman user
    return redirect()->to('home/User');
}


public function RecycleUser(){
    $model = new M_p();
    $where = "deleted is not null";
    $data['user'] = $model->tampilwhere('user', $where);
    $this->log_activity('User membuka Recycle User');
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->recyclebin == 1) {
    echo view('header',$data);
    echo view('menu',$data);
    echo view('user', $data);
    echo view('footer');
    // print_r($data['mapel']);
}else{
    return redirect()->to('home/login');
}
}
public function Tambah_User(){
    $model = new M_p();
    $data['levels'] = $model->tampil('level');
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->data == 1) {
    echo view('header',$data);
    echo view('menu',$data);
    echo view('t_user', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function aksi_t_user() {
    $model = new M_p();
    
    // Ambil data dari form
    $nama = $this->request->getPost('nama');
    $email = $this->request->getPost('email');
    $no_wa = $this->request->getPost('no_wa');
    $level = $this->request->getPost('level');
    $gaji_bulanan = $this->request->getPost('gaji_bulanan');
    $tunjangan = $this->request->getPost('tunjangan');
    $tanggal_mulai_kerja = $this->request->getPost('tanggal_mulai_kerja');
    $this->log_activity('User menambahkan User');
    // Data untuk tabel user
    $dataUser = [
        'nama' => $nama,
        'email' => $email,
        'no_wa' => $no_wa,
        'id_level' => $level,
        'password' => md5('sph')
    ];
    
    // Tambahkan data ke tabel user
    $model->tambah('user', $dataUser);
    $model->tambah('user_backup', $dataUser);
    // Jika level 2, tambahkan juga ke tabel guru
    if ($level == 2) {
        // Dapatkan id_user terbaru dari tabel user
        $id_user = $model->getLastInsertedId('user'); // Anda perlu membuat metode getLastInsertedId() di model M_p
        
        // Data untuk tabel guru
        $dataGuru = [
            'id_user' => $id_user,
            'nama' => $nama,
            'email' => $email,
            'gaji_bulanan' => $gaji_bulanan,
            'tunjangan' => $tunjangan,
            'tanggal_mulai_kerja' => $tanggal_mulai_kerja,
        ];
        
        // Tambahkan data ke tabel guru
        $model->tambah('guru', $dataGuru);
    }
    // print_r($dataGuru);
    // print_r($dataUser);
    // Redirect ke halaman Penilaian
    return redirect()->to('home/User');
}

public function otomatisGajiGuru() {
    $model = new M_p(); // Assume M_p is the model for database interactions

    // Get all teachers
    $guruList = $model->tampil('guru'); // Use the tampil method
    $this->log_activity('User mengaji User');
    foreach ($guruList as $guru) {
        // Calculate salary for the current month based on gaji_bulanan and tunjangan
        $totalGaji = $guru->gaji_bulanan + $guru->tunjangan;

        // Data for pengeluaran table
        $dataPengeluaran = [
            'id_guru' => $guru->id_guru, // Add the id_guru here
            'kategori_pengeluaran' => 'Gaji Guru',
            'pengeluaran' => $totalGaji,
            'tanggal_pengeluaran' => date('Y-m-d H:i:s'), // Current date and time
        ];

        // Add salary record to the pengeluaran table
        $model->tambah('pengeluaran', $dataPengeluaran);

        // Get the current month and year
        $currentMonth = date('m'); // Current month
        $currentYear = date('Y'); // Current year
        
        // Use your model method to check for an existing laporan_keuangan record
        $existingLaporan = $model->getLaporanByMonthYear($currentMonth, $currentYear);

        if ($existingLaporan) {
            // Update total_pengeluaran
            $model->edit('laporan_keuangan', [
                'total_pengeluaran' => $existingLaporan->total_pengeluaran + $totalGaji,
            ], [
                'id_laporan' => $existingLaporan->id_laporan // Change this to id_laporan
            ]);
        } else {
            // Insert a new record if none exists
            $model->tambah('laporan_keuangan', [
                'total_pengeluaran' => $totalGaji,
                'tanggal' => date('Y-m-01'), // Set to the first day of the current month
            ]);
        }
    }

    return redirect()->to('home/Guru');
}


public function Guru(){
    $model = new M_p();
    $data['user'] = $model->tampil('guru');
    $this->log_activity('User membuka Guru');

    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->data == 1) {
    echo view('header',$data);
    echo view('menu',$data);
    echo view('guru', $data);
    echo view('footer');
    // print_r($data['mapel']);
}else{
    return redirect()->to('home/login');
}
}

public function sduser($id)
{
        $model = new M_p;
        $where = array('id_user' => $id);
        $this->log_activity('User Soft Delete User');
        $model->softdelete('user', 'deleted', date('Y-m-d H:i:s'), $where);
        // $this->log_activity('User Soft Delete Data Keranjang');
        return redirect()->to('home/user/');
}


public function sdlog($id)
{
        $model = new M_p;
        $where = array('id_user' => $id);
        $this->log_activity('User Soft Delete Log');
        $model->softdelete('log', 'delete', date('Y-m-d H:i:s'), $where);
        // $this->log_activity('User Soft Delete Data Keranjang');
        return redirect()->to('home/loguser');
}

public function rslog($id)
{
        $model = new M_p;
        $where = array('id_user' => $id);
        $this->log_activity('User Restore Log');
        $model->softdelete('log', 'delete', Null, $where);
        // $this->log_activity('User Soft Delete Data Keranjang');
        return redirect()->to('home/Recycleuserlog');
}

public function rsuser($id)
{
        $model = new M_p;
        $where = array('id_user' => $id);
        $this->log_activity('User Restore User');
        $model->softdelete('user', 'deleted', Null, $where);
        // $this->log_activity('User Soft Delete Data Keranjang');
        return redirect()->to('home/RecycleUser/');
}

public function resetpassword($id)
{
        $model = new M_p;
        $where = array('id_user' => $id);
        $this->log_activity('User Reset Password User');
        $model->softdelete('user', 'password', md5('sph'), $where);
        // $this->log_activity('User Soft Delete Data Keranjang');
        return redirect()->to('home/User');
}

public function undo_user($id)
{
    $model = new M_p();

    // Get the current data from the barang table
    $currentData = $model->getWherearray('user', ['id_user' => $id]);

    // Get the backup data from the barang_backup table
    $backupData = $model->getWherearray('user_backup', ['id_user' => $id]);

    // Restore product data from backup
    $this->log_activity('User Restore Update User');
    $model->restoreProduct('user_backup', 'id_user', $id);

    return redirect()->to('home/User');
}

public function loguser(){
    $model = new M_p();
    $where1 = array('activity.delete' => '0');
	$data['log'] = $model->join1where1('activity','user','activity.id_user = user.id_user',$where1);
    $data['users'] = $model->tampil('user');
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->website == 1) {
    echo view('header',$data);
    echo view('menu',$data);
    echo view('loguser', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function filteruserlog() {
    $model = new M_p(); // Make sure to replace with your actual model for logs
    $idUser = $this->request->getGet('id_user'); // Get the selected user ID from the query string

    // Fetch users for the filter dropdown
    $data['users'] = $model->tampil('user'); // Adjust this method based on how you retrieve users

    // Get logs based on user filter
    if ($idUser) {
        $where = array('activity.id_user' => $idUser, 'activity.delete' => Null);
        $data['log'] = $model->join1where1('activity','user','activity.id_User = user.id_user',$where); // Method to get logs for a specific user
    } else {
        $data['log'] = $model->join1('activity','user','activity.id_User = user.id_user'); // Fetch all logs if no user is selected
    }
    $data['logss'] = $model->join1('activity','user','activity.id_User = user.id_user'); // Fetch all logs if no user is selected
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->website == 1) {
    echo view('header',$data);
    echo view('menu',$data);
    echo view('loguser', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function setting(){
    $model = new M_p();
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->website == 1) {
    echo view('header',$data);
    echo view('menu',$data);
    echo view('setting', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function aksi_edit_website()
{
    // Load the model that interacts with your settings
    $model = new M_p(); // Replace M_p with the actual model name

    // Retrieve the settings from the database
    $where5 = array('id_setting' => 1);
    $setting = $model->getwhere('setting',$where5); // Assuming you have a method to get current settings

    // Get the name from the request
    $name = $this->request->getPost('name');

    $icon = $this->request->getFile('icon');
    $menu = $this->request->getFile('menu');
    $login = $this->request->getFile('login');

    // Array to hold image names
    $images = [];

    // Check and upload icon
    if ($icon && $icon->isValid()) {
        $images['icon'] = $icon->getName();
        $model->uploadimages($icon); // Call uploadimages from the model
    } else {
        // Keep the existing icon name if no new file is uploaded
        $images['icon'] = $setting->icon;
    }

    // Check and upload menu image
    if ($menu && $menu->isValid()) {
        $images['menu'] = $menu->getName();
        $model->uploadimages($menu); // Call uploadimages from the model
    } else {
        // Keep the existing menu image name if no new file is uploaded
        $images['menu'] = $setting->menu;
    }

    // Check and upload login image
    if ($login && $login->isValid()) {
        $images['login'] = $login->getName();
        $model->uploadimages($login); // Call uploadimages from the model
    } else {
        // Keep the existing login image name if no new file is uploaded
        $images['login'] = $setting->login;
    }

    // Update the settings in the database with the new image names and the new name
    $model->updateSettings($name, $images['icon'], $images['menu'], $images['login']); // Corrected parameter usage

    return redirect()->to('home/setting'); // Redirect after processing
}

public function menu(){
    $model = new M_p();
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->website == 1) {
    $data['menus'] = $model->tampil('menu');
    echo view('header',$data);
    echo view('menu',$data);
    echo view('managemenu', $data);
    echo view('footer');
    // print_r($data['menus']);
}else{
    return redirect()->to('home/login');
}
}

public function updateMenuVisibility()
{
    $model = new M_p();
    // Validate and process form submission
    $id_menu = $this->request->getPost('id_menu');
    
    $menuItems = ['recyclebin', 'apps', 'dashboard', 'website', 'data'];
    $dataToUpdate = [];

    foreach ($menuItems as $item) {
        $dataToUpdate[$item] = $this->request->getPost($item . '_level1') ? 1 : 0;
        $dataToUpdate[$item] = $this->request->getPost($item . '_level2') ? 1 : 0;
        $dataToUpdate[$item] = $this->request->getPost($item . '_level3') ? 1 : 0;
    }

    // Update the visibility in the model
    $this->$model->updateMenuVisibility($id_menu, $dataToUpdate);

    return redirect()->to('/home/manageMenus')->with('message', 'Menu visibility updated successfully.');
}


public function updateMenuVisibilityAjax()
{
    // Get data from the AJAX request
    $menu = $this->request->getPost('menu'); // e.g., 'data', 'dashboard'
    $level = $this->request->getPost('level'); // e.g., 1, 2, 3
    $visibility = $this->request->getPost('visibility'); // 1 or 0

    // Logging the data received from AJAX request
    log_message('debug', 'Received data from AJAX - Menu: ' . $menu . ', Level: ' . $level . ', Visibility: ' . $visibility);

    // Prepare data for the update
    $updateData = [$menu => $visibility];
    $whereCondition = ['level' => $level];

    // Logging the prepared data for the update
    log_message('debug', 'Update Data: ' . json_encode($updateData));
    log_message('debug', 'Where Condition: ' . json_encode($whereCondition));

    // Initialize the model
    $menuModel = new M_p();

    // Call the model method to update the menu visibility
    $result = $menuModel->updateMenuVisibility('menu', $updateData, $whereCondition);

    // Check if the update was successful and log the result
    if ($result) {
        log_message('debug', 'Menu visibility updated successfully.');
        return $this->response->setJSON(['status' => 'success', 'message' => 'Menu visibility updated successfully.']);
    } else {
        log_message('error', 'Failed to update menu visibility.');
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update menu visibility.']);
    }
}

public function aturkelas(){
    $model = new M_p();
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        if ($data['menu']->website == 1) {
    $where = array('id_kelas' => Null);
    $data['kelas'] = $model->tampilwhere('anak', $where);
    $data['kelazz'] = $model->tampil('kelas');
    echo view('header',$data);
    echo view('menu',$data);
    echo view('aturkelas', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function aksi_aturkelas()
{
    $model = new M_p();

    $id_anak = $this->request->getPost('id_anak');
    $id_kelas = $this->request->getPost('id_kelas');

    // Log input data
    log_message('info', 'Received data: id_anak: {0}, id_kelas: {1}', [$id_anak, $id_kelas]);

    // Validate inputs
    if (empty($id_anak) || empty($id_kelas)) {
        log_message('error', 'Data tidak lengkap: id_anak: {0}, id_kelas: {1}', [$id_anak, $id_kelas]);
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Data tidak lengkap.'
        ]);
    }

    // Prepare data for the update
    $updateData = [
        'id_kelas' => $id_kelas
    ];

    // Call the edit method from your model
    $result = $model->edit('anak', $updateData, ['id_anak' => $id_anak]);

    if ($result) {
        log_message('info', 'Kelas berhasil diupdate for id_anak: {0}', [$id_anak]);
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Kelas berhasil diupdate!'
        ]);
    } else {
        log_message('error', 'Gagal mengupdate kelas for id_anak: {0}', [$id_anak]);
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal mengupdate kelas. Pastikan id_anak dan id_kelas benar.'
        ]);
    }
}





}
