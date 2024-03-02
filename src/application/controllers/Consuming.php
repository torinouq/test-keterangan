<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Consuming extends RESTController {

    private $consumer;

    function __construct()
    {
        parent::__construct();
        $this->consumer = $this->nats_consumer;
    }

    function index_get(){
        $con = $this->consumer->listenStream('KONTAK', 'KONTAK', 'tambah');
        $con->handle(function($msg) {
            // Proses pesan yang diterima
            // return $msg;
            $data = json_decode($msg, true);
            $d = [
                'id' => $data['id'],
                'nama' => $data['nama'],
                'nomor' => $data['nomor']
            ];
            if ($this->kontak->insert($d)) {
                // Jika insert berhasil
                $response_data = [
                    'id' => $data['id'],
                    'nama' => $data['nama'],
                    'nomor' => $data['nomor']
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

            // Logika pemrosesan lebih lanjut, misalnya menyimpan data ke database, dll.
            echo "Pesan diterima: " . print_r($data['nama'], true) . "\n";

            // Acknowledge pesan setelah diproses untuk menghindari pengiriman ulang
            // $msg->ack();
        });
            // $data = json_decode($con, true);
            
            // echo "Pesan diterima: " . print_r($data, true) . "\n";
            // echo "Pesan diterima: " . print_r($data->nama, true) . "\n";
    }
}