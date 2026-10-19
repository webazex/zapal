ZAPAL About: welcome-about-us

Проверено против:
- repo HEAD 0046dafa7abf469f094cdb2f41ae02a18431abd0
- актуальной ACF-группы acf-export-2026-10-16 (2).json
- существующего style.css
- последней доступной статической about.html

ACF contract:
- title-h1      WYSIWYG / ZAPAL Heading
- right-subtitle text
- description    textarea
- kicker         text

Поведение:
- title-h1 проходит через WBZX_Zapal_heading_content(), поэтому поддерживает
  разрешённые bold / italic-serif / color / line breaks.
- kicker/right-subtitle/description экранируются как plain text.
- правый note целиком не выводится, если оба поля пустые.
- существующая CSS-структура about-hero сохранена без изменений.

Группа полей в этом шаге не менялась.
