<?php
namespace App\Lib;

use Illuminate\Support\Facades\Response;
class Csv {

  public function __construct() {}

  public function download($list, $header, $filename)
  {
    if (count($header) > 0) {//csvファイルの先頭にカラム名を追加
      array_unshift($list, $header);
    }
    $stream = fopen('php://temp', 'r+b');
    foreach ($list as $row) {//テーブルのレコードを一行ずつファイルにput
        fputcsv($stream, $row);
    }

    rewind($stream);//ファイルポインタの位置を先頭に戻す
    
    $csv = str_replace(PHP_EOL, "\r\n", stream_get_contents($stream));
    $csv = mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');
    $headers = array(
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=$filename",
    );
    return \Response::make($csv, 200, $headers);
  }
}