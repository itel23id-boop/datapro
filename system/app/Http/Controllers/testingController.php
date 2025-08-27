<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DiDom\Document;

class testingController extends Controller
{
    private function curi()
	{
		$url = 'https://kursdollar.net/real-time/USD/';
		$document = new Document($url, true);

		$kurs = $document->find('td')[3];

		// var_dump($kurs);

		return $kurs;
	}
    public function store()
    {
        $rupiah = str_replace(' ', '', $this->curi()->text());
		$rupiah = doubleval($rupiah);
		$parameter_dollar = 0.1;
		$convert = $parameter_dollar * $rupiah;
		$hasil = "===========================================\n".
		"[+] Status => ".$this->curi()->text()."/USD\n".
		"===========================================\n".
		"[-] Jumlah USD : $parameter_dollar".
		"===========================================\n".
		"[=] Convert ".$parameter_dollar." Dollar => Rp.".$convert."\n".
		"===========================================\n";
		return $hasil;
    }
}
