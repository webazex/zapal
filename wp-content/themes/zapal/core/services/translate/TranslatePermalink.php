<?php

namespace WBZX\zapal\core\services\translate;

class TranslatePermalink
{
    private static array $languages = [];
    private static string $defaultLanguage = 'ua';
    private static string $currentLanguage = '';

    public static function init(){
        $lang_file = get_template_directory() . '/lang.php';
        if(file_exists($lang_file)){
            self::$languages = include $lang_file;
        }
        if(empty(self::$languages)){
            self::$languages = ['ua' => 'uk_UA'];
        }

        self::$currentLanguage = (!empty(get_option('wbzx_zapal_current_lang')) ?
            get_option('wbzx_zapal_current_lang') : self::$defaultLanguage);
        self::registerPermalinks();
    }

    private static function registerPermalinks(){
        add_rewrite_tag('%lang%', '([a-z]{2})');

        add_filter('rewrite_rules_array');
    }

    private static function __setRules(array $rules)
    {
        $lang_keys = array_keys(self::$languages);
        $lang_keys = array_diff($lang_keys, [self::$currentLanguage]);
        if(empty($lang_keys)){
            return $rules;
        }
        $lang_regex = implode('|', $lang_keys);
        $new_rules = [];
        $new_rules['^(' . $lang_regex . ')/?$'] = 'index.php?lang=$matches[1]';
        foreach ($rules as $regex => $rule) {
            $new_regex = '^(' . $lang_regex . ')/' . ltrim($regex, '^');

            if (strpos($rule, '?') !== false) {
                $new_query = str_replace('?', '?lang=$matches[1]&', $rule);
            } else {
                $new_query = $rule . '&lang=$matches[1]';
            }

            $new_rules[$new_regex] = $new_query;
        }

        return $new_rules + $rules;
    }

    private static function __captureLanguages(array $languages){
        // Перехват и определение языка при парсинге URL
        add_filter('request', function($query_vars) {
            // Если в URL есть языковой префикс и он валидный
            if (isset($query_vars['lang']) && array_key_exists($query_vars['lang'], self::$languages)) {
                self::$current_lang = $query_vars['lang'];
            } else {
                // Если префикса нет (зашли на базовый URL), включаем язык из админки
                self::$current_lang = self::$default_lang;
            }

            // Меняем локаль WordPress под определенный язык
            $target_locale = self::$languages[self::$current_lang];
            add_filter('locale', function() use ($target_locale) {
                return $target_locale;
            });

            return $query_vars;
        });
    }
    public static function getLanguages():array {
        return self::$languages;
    }

    public static function getCurrentLanguage():string {
        return self::$currentLanguage;
    }
}