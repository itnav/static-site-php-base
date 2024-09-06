<?php

/** 
 * プロジェクトのルートディレクトリ
 */
$project_dir = realpath(__DIR__ . '/../..');

/**
 * ベースパスに対して相対パスを解決し、絶対パスを返却する。
 *
 * @param string $base         ベースパス
 * @param string $path         相対パス
 * @param array<string,string> オプション
 * 
 * @return string 解決された絶対パス
 */
function path_resolve($base, $path, $options = array()) {
    global $project_dir;

    // $base の先頭がプロジェクトディレクトリだった場合削除する
    if (empty($options['leading_project_dir']) && str_starts_with($base, $project_dir)) {
        $base = substr($base, strlen($project_dir));
    }

    // ディレクトリセパレータを正規化（Windowsパスも考慮）
    $base = str_replace('\\', '/', $base);
    $path = str_replace('\\', '/', $path);

    // パスを配列に分割
    $base_parts = explode('/', rtrim($base, '/'));
    $path_parts = explode('/', $path);

    // パスの各部分を処理
    foreach ($path_parts as $part) {
        // 現在のディレクトリの場合、何もしない
        if ($part === '.') {
            continue;

            // 親ディレクトリの場合、ベースの最後の部分を削除
        } elseif ($part === '..') {
            if (!empty($base_parts)) {
                array_pop($base_parts);
            }

            // その他の部分はベースに追加
        } else {
            $base_parts[] = $part;
        }
    }

    // has_base_parts
    $has_base_parts = !empty($base_parts);

    // leading_slash の処理（デフォルトは true）
    $is_leading_slash = !isset($options['leading_slash']) || !!$options['leading_slash'];
    $has_leading_slash = $has_base_parts && $base_parts[0] === '';
    if ($is_leading_slash && !$has_leading_slash) array_unshift($base_parts, '');
    elseif (!$is_leading_slash && $has_leading_slash) array_shift($base_parts);

    // trailing_slash の処理（デフォルトは false）
    $is_trailing_slash = isset($options['trailing_slash']) && !!$options['trailing_slash'];
    $has_trailing_slash = $has_base_parts && end($base_parts) === '';
    if ($is_trailing_slash && !$has_trailing_slash) $base_parts[] = '';
    elseif (!$is_trailing_slash && $has_trailing_slash) array_pop($base_parts);

    // パーツを結合する
    return implode('/', $base_parts);
}
