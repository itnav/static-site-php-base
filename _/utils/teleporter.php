<?php

/**
 * @var array<
 *      string,
 *      array{
 *          'value': string,
 *          'is_active': bool,
 *          'key_map': array<string,true>
 *      }
 *  >
 */
$teleporter_map = array();

/**
 * @return array<
 *      string,
 *      array{
 *          'value': string,
 *          'is_active': bool,
 *          'key_map': array<string,true>
 *      }
 *  >
 */
function get_teleporter_map() {
    global $teleporter_map;
    return $teleporter_map;
}

/**
 * @return array{
 *      'value': string,
 *      'is_active': bool,
 *      'key_map': array<string,true>
 *  }
 */
function create_teleporter() {
    return array(
        'value' => '',
        'is_active' => false,
        'key_map' => array()
    );
}

/**
 * @param string $name
 * @return string
 */
function create_teleporter_anchor($name) {
    return "<!--teleporter:$name-->";
}

/**
 * @param string $name
 * @return string
 */
function create_teleported_anchor($name) {
    return "<!--teleported:$name-->";
}

/**
 * @param string $name
 * @param array{
 *      'is_active'?: bool,
 *  } $options
 */
function teleporter($name, $options = array()) {
    global $teleporter_map;

    if (empty($teleporter_map[$name])) {
        $teleporter_map[$name] = create_teleporter();
    }

    $teleporter_map[$name]['is_active'] = isset($options['is_active']) && is_bool($options['is_active'])
        ? $options['is_active']
        : true;

    echo create_teleporter_anchor($name);
}

/**
 * @param string $name
 * @param string $value
 * @param array{
 *      'key'?: string
 * } $options
 */
function teleport($name, $value, $options) {
    global $teleporter_map;

    // 初めてテレポートする場合、テレポーターを作成する
    if (empty($teleporter_map[$name])) {
        $teleporter_map[$name] = create_teleporter();
    }

    // テレポーターが存在し、キーが指定されている場合、重複確認する
    if (isset($options['key']) && is_string($options['key'])) {
        $key = $options['key'];
        if (!empty($teleporter_map[$name]['key_map'][$key])) {
            return;
        }
        $teleporter_map[$name]['key_map'][$key] = true;
    }

    $teleporter_map[$name]['value'] .= $value;
}

/**
 * @param string $name
 */
function enable_teleporter($name) {
    global $teleporter_map;

    if (isset($teleporter_map[$name]['is_active'])) {
        $teleporter_map[$name]['is_active'] = true;
    }
}

/**
 * @param string $name
 */
function disable_teleporter($name) {
    global $teleporter_map;

    if (isset($teleporter_map[$name]['is_active'])) {
        $teleporter_map[$name]['is_active'] = false;
    }
}

/**
 * @return bool
 */
function setup_teleporter() {
    return ob_start();
}

/**
 * @param string|string[]|null $name
 * @param array{
 *      'hidden_anchor'?: bool
 * } $options
 */
function flush_teleporter($name = null, $options = array()) {
    global $teleporter_map;

    // 名前の指定がない場合は全てのテレポーターを対象にする
    $names = $name === null
        ? array_keys($teleporter_map)
        : (is_array($name) ? $name : array($name));

    // 有効なテレポーターのみを抽出
    $names = array_filter($names, function ($name) use ($teleporter_map) {
        return !empty($teleporter_map[$name]['is_active']) && !empty($teleporter_map[$name]['value']);
    });

    // 有効なテレポーターがない場合は何も実行しない
    if (empty($names)) {
        return;
    }

    // オプションを整形
    $hidden_anchor = isset($options['hidden_anchor']) && $options['hidden_anchor'] === true;

    // これまでの HTML 出力を取得し、変ん数に格納後クリアする
    $html = ob_get_clean();

    // テレポートを実行
    foreach ($names as $name) {
        $teleporter = $teleporter_map[$name];
        $html = str_replace(
            create_teleporter_anchor($name),
            $hidden_anchor
                ? $teleporter['value']
                : create_teleported_anchor($name) . $teleporter['value'],
            $html
        );
    }

    // テレポート後の HTML を再出力する
    echo $html;
}

/**
 * @param array<string,string> $attr
 * @return string
 */
function format_teleporter_attr($attr) {
    $result = '';
    foreach ($attr as $key => $value) {
        if ($value === null) continue;
        if (is_bool($value)) $value = $value ? 'true' : 'false';
        $result = $value
            ? $result .= " $key=\"$value\""
            : $result .= " $key";
    }
    return $result;
}

/**
 * @param string $name
 * @param string $tag
 * @param array{
 *      'key'?: string,
 *      'attr'?: array<string,string>
 *      'content'?: string,
 * } $options
 */
function teleport_tag($name, $tag, $options = array()) {
    $attr = isset($options['attr']) && is_array($options['attr'])
        ? format_teleporter_attr($options['attr'])
        : '';

    $content = isset($options['content']) && is_string($options['content'])
        ? $options['content']
        : '';

    teleport($name, "<$tag$attr>$content</$tag>", $options);
}

/**
 * @param string $name
 * @param string $url
 * @param array{
 *      'key'?: string,
 *      'attr'?: array<string,string>
 *      'content'?: string,
 * } $options
 */
function teleport_style($name, $url, $attr = array(), $options = array()) {
    if (!isset($options['key'])) {
        $options['key'] = $url;
    }

    $attr = isset($options['attr']) && is_array($options['attr'])
        ? $attr + $options['attr']
        : $attr;

    $content = isset($options['content']) && is_string($options['content'])
        ? $options['content']
        : '';

    $attr = format_teleporter_attr(array(
        'rel' => 'stylesheet',
        'href' => $url
    ) + $attr);

    teleport($name, "<link$attr>$content</link>", $options);
}

/**
 * @param string $name
 * @param string $url
 * @param array<string,string> $attr
 * @param array{
 *      'key'?: string,
 *      'attr'?: array<string,string>
 *      'content'?: string,
 * } $options
 */
function teleport_script($name, $url, $attr = array(), $options = array()) {
    if (isset($options['key'])) {
        $options['key'] = $url;
    }

    $attr = isset($options['attr']) && is_array($options['attr'])
        ? $attr + $options['attr']
        : $attr;

    $content = isset($options['content']) && is_string($options['content'])
        ? $options['content']
        : '';

    $attr = format_teleporter_attr(array(
        'src' => $url
    ) + $attr);

    teleport($name, "<script$attr>$content</script>", $options);
}
