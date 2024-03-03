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
        $con = $this->consumer->listenStream('telepon', 'kontak', 'tambah');
        $con->handle(function($msg) {

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
                echo "\nPesan diterima, id: " . print_r($d['id'], true) . "\n";
                echo "New kontak has been created\n\n"; // Tampilkan di CLI saja
            } else {
                // Jika insert gagal
                echo "\nPesan diterima, id: " . print_r($d['id'], true) . "\n";
                echo "Failed to create new kontak\n\n"; // Tampilkan di CLI saja
            }

        });
    }
}