<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class SpeechController extends Controller
{
    public function index()
    {
        return view('speech');
    }

    public function speak(Request $request)
{
    $text = urlencode($request->input('text'));
    $apiKey = '966e2aba7fe940b9aa92818856faf610'; // sua key

    // Monta a URL completa manualmente (sem Http::get)
    $url = "http://api.voicerss.org/?key={$apiKey}&hl=pt-br&c=MP3&f=16khz_16bit_stereo&src={$text}";

    // Faz a requisição pura com file_get_contents (sem interferência do Laravel)
    $audioData = file_get_contents($url);

    // Verifica se o retorno é erro
    if (str_contains($audioData, 'ERROR')) {
        return response()->json(['error' => $audioData]);
    }

    // Cria pasta se não existir
    $audioDir = public_path('audio');
    if (!file_exists($audioDir)) {
        mkdir($audioDir, 0777, true);
    }

    // Salva o áudio
    $outputPath = $audioDir . '/output.mp3';
    file_put_contents($outputPath, $audioData);

  return response()->json(['audio_url' => url('audio/output.mp3')]);
}
}
