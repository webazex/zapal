<?php
namespace WBZX\Zapal\core\services\translate;
final class TranslatePermalink {

    public static function registerTag(): void
    {
        add_rewrite_tag('%lang%', '([a-z]{2})');
    }

    public static function rewriteRules(array $rules): array
    {
        // позже логика
        return $rules;
    }
    public static function registerRules(): void {
        $languages = array_keys(Translate::getLanguages());
        $langRegex = implode('|', $languages);
        $newRules = [];
        foreach ($languages as $lang ) {
            var_dump($lang);
        }
    }

    public static function resolveRequest(array $queryVars): array {
        if (isset($queryVars['lang'])) {
            Translate::setCurrentLanguage($queryVars['lang']);
        }
        return $queryVars;
    }

    public static function detectCurrentLanguage(): string {
        return ''; //temporary
    }
}