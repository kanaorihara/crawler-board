<?php
/**
 * Created by PhpStorm.
 * User: kana
 * Date: 2017/02/15
 * Time: 0:06
 */

?>
<!DOCTYPE>
<html lang="ja">
<head>
    <link href="css/common.css" rel="stylesheet" type="text/css">
</head>
<body>
    <h1>今期のアニメ一覧</h1>
    <form action="{{ route('detail') }}" method="get" enctype="multipart/form-data">
    <table>
        <thead>
            <tr>
                <th>タイトル</th>
                <th>放送時間</th>
                <th>ストーリー</th>
                <th>スタッフ</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($programs as $program)
            <tr>
                <td>{{ $program['title'] }}</td>
                <td>{{ $program['air_time'] }}</td>
                <td>{{ $program['story'] }}</td>
                <td>{{ $program['staff'] }}</td>
                <td>@if($program['episodes'])<button type="submit" name="program_id" value="{{ $program['id'] }}">各話あらすじ</button>@endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </form>
</body>
</html>