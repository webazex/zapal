<?php
use WBZX\zapal\core\services\translate\Translate;
final class TranslatePermalink {

    private static function registerTag():void {
        add_rewrite_tag('%lang%', '([a-z]{2})');
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
}