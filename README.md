# Static Site PHP Base

PHP で動作する静的サイトのベース

<br />

## 🍀 環境

---

### 必須環境

-   [Docker](https://docker.com)
-   Editor
    -   [VSCode](https://code.visualstudio.com)
    -   [Cursor](https://www.cursor.com)

<br />

## 🎱 開発

---

### 1. Docker の起動

```sh
docker compose up
```

#### Launchd URL (Default)

-   Wordpress: https://localhost:8080

<br />

## 独自 PHP API

---

### `path_resolve`

第１引数のパスを基準にして、第２引数のパスを絶対パスに変換する関数。

```php
// /top/index.php
$path = path_resolve(__DIR__, '../_/styles/global.min.css'); // 👉 /_/styles/global.min.css
```

<br />

### `render`

PHP ファイルをレンダリングする関数。

```php
render('../component.php');
```

第２引数に配列を渡すと、レンダリングされる PHP ファイル内で `$props` として受け取れる。

```php
// page.php
render('../component.php', ['title' => 'タイトル']);
```

```php
// component.php
echo $props['title']; // 👉 タイトル
```

<br />

### `setup_teleporter`

実行することで Teleporter が使用できるようになる関数。

```php
setup_teleporter()
```

基本的に、テレポーターを使用したいページの先頭で実行するのが良い。

```php
<?php setup_teleporter() ?>

<html>
    ...
</html>
```

<br />

### `teleporter`

テレポーターを設置する関数。

```php
<head>
    <?php teleporter('head') ?>
</head>
```

<br />

### `teleport`

テレポーターへ文字列を送信する関数。

```php
teleport('head', '<title>タイトル</title>');
```

また、第３引数のオプションで `key` を渡すことで、重複した送信を防ぐことができる。

```php
// １回目
teleport('head', '<title>Foo</title>', array('key' => 'title'));

// 二回目（`title` キーは既にテレポート済みなので送信されない）
teleport('head', '<title>Bar</title>', array('key' => 'title'));

// 出力 👉 <title>Foo</title>
```

<br />

### `teleport_style`

CSS ファイルのテレポートに特化した関数。

```php
// ローカルファイルの読み込み
teleport_style('head', path_resolve(__DIR__, './style.min.css'));

// 外部ファイルの読み込み
teleport_style('head', 'https://cdn.example.com/style.min.css');
```

第２引数のパスを自動で `key` として扱うため、オプションを設定しなくても重複した読み込みを防ぐことができる。

<br />

### `teleport_script`

JavaScript ファイルのテレポートに特化した関数。

```php
// ローカルファイルの読み込み
teleport_script('head', path_resolve(__DIR__, './script.js'));

// 外部ファイルの読み込み
teleport_script('head', 'https://cdn.example.com/script.js');
```

第２引数のパスを自動で `key` として扱うため、オプションを設定しなくても重複した読み込みを防ぐことができる。

<br />

### `flush_teleporter`

テレポーターをフラッシュする関数。

```php
flush_teleporter();
```
