<?php

require '/opt/vendor/autoload.php';

function postNippoCount() {
    // 月曜から金曜日のみ実行
    if (date('w') === '0' || date('w') === '6') {
        return;
    }

    // 昨日の日付を取得
    $target_ymd = date('Ymd', strtotime('yesterday'));
    if (date('w') === '1') {
        // 実行日が月曜日の場合は3日前(金曜日)の日付を取得
        $target_ymd = date('Ymd', strtotime('-3 day'));
    }
    error_log('$target_ymd:'.$target_ymd);
    $text = getStargazers($target_ymd);
    $url = 'https://hooks.slack.com/services/' . getenv('SLACK_HOOK_TOKEN');
    $client = new \GuzzleHttp\Client();
    $client->post($url,[
        'body' => json_encode([
            'channel' => '#nippo',
            'username' => '日報カウンター',
            'text' => $text,
            'icon_emoji' => ':esadori:',
        ]),
    ]);
    return '';
}

/**
 * @param string $ymd
 * @return string
 */
function getStargazers($ymd): string
{
    $target_ymd = $ymd ? substr($ymd, 0, 4).'/'.substr($ymd, 4, 2).'/'.substr($ymd, 6, 2) : date('Y/m/d', strtotime('yesterday'));
    $posts = getPosts($target_ymd);

    $posts_count = count($posts);
    $wip_count = 0;
    $stargazers = '';
    foreach($posts as $post) {
        if ($post->wip) {
            $wip_count++;
        }
        $stargazers .= '><'.$post->url.'|'.$post->name.'>'.PHP_EOL;
        for ($i = 0; $i < $post->stargazers_count; $i++) {
            $stargazers .= '>:star: '.$post->stargazers[$i]->user->name.($post->stargazers[$i]->body ? ' ＜'.str_replace(PHP_EOL, '', $post->stargazers[$i]->body) : '').PHP_EOL;
        }
        $stargazers .= '>'.PHP_EOL;
    }

    $wip_text = '';
    if ($wip_count > 0) {
        $wip_text = '(WIP '.$wip_count.'件)';
    }

    $text = $target_ymd . 'の日報はありませんよー';
    if ($posts_count > 0) {
        $text = $target_ymd . 'の日報は' . $posts_count . '件'.$wip_text.'でした。';
    }

    return $text.($stargazers ? PHP_EOL.$stargazers : '');
}

/**
 * @param string $target_ymd
 * @return mixed
 */
function getPosts($target_ymd)
{
    $client = new \GuzzleHttp\Client();
    $url = 'https://api.esa.io/v1/teams/' . getenv('ESA_TEAM_NAME') . '/posts';
    $res = $client->get($url,[
        'query'=>[
            'access_token' => getenv('ESA_ACCESS_TOKEN'),
            'q' => 'in:日報/'.$target_ymd,
            'include' => 'stargazers',
            'per_page' => 100,
        ],
    ]);
    $body = json_decode($res->getBody());
    return $body->posts;
}

