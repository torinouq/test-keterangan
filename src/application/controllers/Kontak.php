<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Kontak_model $kontak
 * @property Nats_consumer $nats_consumer
 */


use chriskacerguis\RestServer\RestController;

class Kontak extends RESTController {

    private $stream;

    function __construct()
    {
        parent::__construct();
        // Membuat klien NATS
        $this->client = $this->nats_config->getClient();
        $this->consumer = $this->nats_consumer;
    }

    function index_get() {
        $id = $this->uri->segment(2);
        if($id === null)
        {
            $kontak = $this->kontak->get_all();
        } else {
            $kontak = $this->kontak->get_by_id($id);
        }

        if($kontak)
        {
        // $con = $this->consumer->listenStream('KONTAK', 'KONTAK', 'telepon.tambah');
        // $con->handle(
        //     function ($message){
        //         $d = $message['nama'];
        //     }
        // );
        $this->response([
                'status' => true,
                'data' => $kontak
            ], RESTController::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'id not found'
            ], RESTController::HTTP_NOT_FOUND);
        }

    }


    public function index_post()
    {
        $id = $this->post('id');
        $nama = $this->post('nama');
        $nomor = $this->post('nomor');
    
        if (!empty($nama) && !empty($nomor)) {
            $data = [
                'id' => $id,
                'nama' => $nama,
                'nomor' => $nomor
            ];
    
            if ($this->kontak->insert($data)) {
                // Jika insert berhasil
                $kontak_id = $this->db->insert_id();
                $response_data = [
                    'id' => $kontak_id,
                    'nama' => $nama,
                    'nomor' => $nomor
                ];
                $this->response([
                    'status' => true,
                    'message' => 'New kontak has been created',
                    'data' => $response_data
                ], RESTController::HTTP_CREATED);
            } else {
                // Jika insert gagal
                $this->response([
                    'status' => false,
                    'message' => 'Failed to create new kontak'
                ], RESTController::HTTP_BAD_REQUEST);
            }
        } else {
            // Jika input tidak lengkap
            $this->response([
                'status' => false,
                'message' => 'Nama dan nomor tidak boleh kosong'
            ], RESTController::HTTP_BAD_REQUEST);
        }
    }
    

    public function index_put($id)
    {
        // $id = $this->put('id');
        $data = [
            'nama' => $this->put('nama'),
            'nomor' => $this->put('nomor')
        ];

        $this->kontak->update($id, $data);
            $this->response([
                'status' => true,
                'message' => 'data kontak has been updated'
            ], RESTController::HTTP_OK);
        
    }

    public function index_delete($id)
    {

        $this->kontak->delete($id);
        $this->response([
            'status' => true,
            'id' => $id,
            'message' => 'deleted'
        ], RESTController::HTTP_OK);
    }
}    
