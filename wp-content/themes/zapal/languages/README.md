# Переводы темы ZAPAL

Актуальный набор переводов обновлён 24 октября 2026 года на основе PHP-исходников темы из GitHub `main` (`99b0817`), включая серверный антифлуд и строки динамического JS.
Text domain: `zapal`.

## Файлы

- `zapal.pot` — шаблон всех актуальных строк PHP темы.
- `uk.po`, `uk.mo` — украинский WordPress locale.
- `uk_UA.po`, `uk_UA.mo` — альтернативный украинский locale.
- `ru_RU.po`, `ru_RU.mo` — русский.
- `en_US.po`, `en_US.mo` — английский США.
- `en_GB.po`, `en_GB.mo` — английский Великобритании.

Тема использует `load_theme_textdomain('zapal', get_template_directory() . '/languages')` в `inc/supports.php`.
PHP передаёт динамические строки в `main.js` через `wp_localize_script()`. JS самостоятельно язык не определяет.

ACF-контент, названия и описания продукции переводятся через Polylang/локализованные ACF-поля отдельно от gettext.

Файлы `zapal-ru_RU.*`, `zapal-en_US.*`, `zapal-en_GB.*` в каталоге темы устарели: это имена для *глобального* каталога переводов WordPress (`wp-content/languages/themes`), а не директории темы. Это обновление их не удаляет.
