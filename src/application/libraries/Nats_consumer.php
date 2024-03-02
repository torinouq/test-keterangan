<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Basis\Nats\Message\Payload;

class Nats_consumer {
    private $nc;
    private $stream;
    private $consumer;

    public function __construct() {
        $this->nats_config = new Nats_config();
        $this->nc = $this->nats_config->getClient();
        $this->stream = $this->nc->getApi();
    }

    public function listenStream(string $streamName, string $consumerName, string $subject) {
        $s = $this->stream->getStream($streamName);

        $this->consumer = $s->getConsumer($consumerName);
        
        $this->consumer->getConfiguration()->setSubjectFilter($streamName . '.' . $subject);
        
        return $this->consumer;
        // return $this->consume->create();
        // Mendengarkan pesan
        // Method `handle` akan mendengarkan pesan dan memanggil callback dengan setiap pesan yang diterima
        // $this->consumer->handle(function($msg) {
        //     // Proses pesan yang diterima
        //     // return $msg;
        //     $data = json_decode($msg, true);
        //     // $d = [
        //     //     'id' => $data['id'],
        //     //     'nama' => $data['nama'],
        //     //     'nomor' => $data['nomor']
        //     // ];
            
        //     // Logika pemrosesan lebih lanjut, misalnya menyimpan data ke database, dll.
        //     echo "Pesan diterima: " . print_r($data['nama'], true) . "\n";

        //     // Acknowledge pesan setelah diproses untuk menghindari pengiriman ulang
        //     // $msg->ack();
        // });
    }
}