<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Mahasiswa extends CI_Controller {
    public function index() {
        $data['mahasiswa'] = $this->m_mahasiswa->tampil_data()->result();
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('mahasiswa', $data);
        $this->load->view('templates/footer');
    }

    public function tambah_aksi() {
        $nama = $this->input->post('nama');
        $nim = $this->input->post('nim');
        $tgl_lahir = $this->input->post('tgl_lahir');
        $jurusan = $this->input->post('jurusan');
        $alamat = $this->input->post('alamat');
        $email = $this->input->post('email');
        $no_telp = $this->input->post('no_telp');
        $foto = $_FILES['foto'];

        if ($foto != '') {
            $config['upload_path'] = './assets/foto';
            $config['allowed_types'] = 'jpg|png|jpeg|gif';

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('foto')) {
                echo "Upload Gagal"; die();
            } else {
                $foto = $this->upload->data('file_name');
            }
        }

        $data = array(
            'nama' => $nama,
            'nim' => $nim,
            'tgl_lahir' => $tgl_lahir,
            'jurusan' => $jurusan,
            'alamat' => $alamat,
            'email' => $email,
            'no_telp' => $no_telp,
            'foto' => $foto
        );

        $this->m_mahasiswa->input_data($data, 'tb_mahasiswa');
        $this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  Data Berhasil Ditambahkan!
</div>');
        redirect('mahasiswa/index');
    }

    public function hapus($id) {
        $where = array('id' => $id);
        $this->m_mahasiswa->hapus_data($where, 'tb_mahasiswa');
        $this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  Data Berhasil Dihapus!
</div>');
        redirect('mahasiswa/index');
    }

    public function edit($id) {
        $where = array('id' => $id);
        $data['mahasiswa'] = $this->m_mahasiswa->edit_data($where, 'tb_mahasiswa')->result();
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('edit', $data);
        $this->load->view('templates/footer');
    }

    public function update() {
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');
        $nim = $this->input->post('nim');
        $tgl_lahir = $this->input->post('tgl_lahir');
        $jurusan = $this->input->post('jurusan');
        $alamat = $this->input->post('alamat');
        $email = $this->input->post('email');
        $no_telp = $this->input->post('no_telp');
        

        $data = array(
            'nama' => $nama,
            'nim' => $nim,
            'tgl_lahir' => $tgl_lahir,
            'jurusan' => $jurusan,
            'alamat' => $alamat,
            'email' => $email,
            'no_telp' => $no_telp
        );

        $where = array(
            'id' => $id
        );

        $this->m_mahasiswa->update_data($where, $data, 'tb_mahasiswa');
        $this->session->set_flashdata('message','<div class="alert alert-info alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    Data Berhasil Diupdate!
</div>');
        redirect('mahasiswa/index');
    }

    public function detail($id) {
        $this->load->model('m_mahasiswa');
        $detail = $this->m_mahasiswa->detail_data($id);
        $data['detail'] = $detail;
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('detail', $data);
        $this->load->view('templates/footer');
    }

    public function print() {
        $data['mahasiswa'] = $this->m_mahasiswa->tampil_data("tb_mahasiswa")->result();
        $this->load->view('print_mahasiswa', $data);
    }

    public function pdf() {
        $this->load->library('dompdf_lib');
        $data['mahasiswa'] = $this->m_mahasiswa->tampil_data('tb_mahasiswa')->result();
        $this->load->view('laporan_pdf', $data);
        $html = $this->output->get_output();  
        $this->dompdf_lib->setPaper('A4', 'landscape'); 
        $this->dompdf_lib->loadHtml($html);
        $this->dompdf_lib->render();
        $this->dompdf_lib->stream("laporan_mahasiswa.pdf", array('Attachment' => 0));
    }

    public function excel() {
        $data['mahasiswa'] = $this->m_mahasiswa->tampil_data('tb_mahasiswa')->result();

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("Framework Indonesia")
            ->setLastModifiedBy("Framework Indonesia")
            ->setTitle("Daftar Mahasiswa");

        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setCellValue('A1', 'NO');
        $spreadsheet->getActiveSheet()->setCellValue('B1', 'NAMA MAHASISWA');
        $spreadsheet->getActiveSheet()->setCellValue('C1', 'NIM');
        $spreadsheet->getActiveSheet()->setCellValue('D1', 'TANGGAL LAHIR');
        $spreadsheet->getActiveSheet()->setCellValue('E1', 'JURUSAN');
        $spreadsheet->getActiveSheet()->setCellValue('F1', 'ALAMAT');
        $spreadsheet->getActiveSheet()->setCellValue('G1', 'EMAIL');
        $spreadsheet->getActiveSheet()->setCellValue('H1', 'NO.TELEPON');

        $baris = 2;
        $no = 1;

        foreach ($data['mahasiswa'] as $mhs) {
            $spreadsheet->getActiveSheet()->setCellValue('A' . $baris, $no++);
            $spreadsheet->getActiveSheet()->setCellValue('B' . $baris, $mhs->nama);
            $spreadsheet->getActiveSheet()->setCellValue('C' . $baris, $mhs->nim);
            $spreadsheet->getActiveSheet()->setCellValue('D' . $baris, $mhs->tgl_lahir);
            $spreadsheet->getActiveSheet()->setCellValue('E' . $baris, $mhs->jurusan);
            $spreadsheet->getActiveSheet()->setCellValue('F' . $baris, $mhs->alamat);
            $spreadsheet->getActiveSheet()->setCellValue('G' . $baris, $mhs->email);
            $spreadsheet->getActiveSheet()->setCellValue('H' . $baris, $mhs->no_telp);

            $baris++;
        }

        $filename = "Data_Mahasiswa" . '.xlsx';
        $spreadsheet->getActiveSheet()->setTitle("Data Mahasiswa");

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function search() {
        $keyword = $this->input->post('keyword');
        $data['mahasiswa'] = $this->m_mahasiswa->get_keyword($keyword);
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('mahasiswa', $data);
        $this->load->view('templates/footer');
    }
}