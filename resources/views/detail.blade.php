<?php
/**
 * Created by PhpStorm.
 * User: kana
 * Date: 2017/02/21
 * Time: 14:32
 */

?>

<!DOCTYPE>
<html lang="ja">
<head>
    <link href="/css/common.css" rel="stylesheet" type="text/css">
</head>

<body>
    <h1>各話あらすじ</h1>
    <form action="{{ route('detail') }}" method="POST" enctype="multipart/form-data">
    <table>
        <thead>
            <tr>
                <th>話数</th>
                <th>タイトル</th>
                <th>放送日</th>
                <th>あらすじ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($episodes as $episode)
            <tr>
                <td>{{ $episode['num'] }}</td>
                <td>{{ $episode['subtitle'] }}</td>
                <td>{{ $episode['date'] }}</td>
                <td class="w-600">{{ $episode['summary'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="comment-area">
        <h2>みんなのコメント</h2>
        @if($comments->count() != 0)
            <ul class="comment-wrap">
            @foreach($comments as $comment)
            <li>{{ $comment['comment'] }}</li>
            @endforeach
            </ul>
        @endif
        <p>コメントを投稿する</p>
        <textarea rows="5" name="comment"></textarea>
    </div>
        <input type="submit" name="submit" value="送信">
        <input type="hidden" name="program_id" value="{{ $program_id }}">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    </form>
</body>

</html>
