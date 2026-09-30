<?php

namespace WBZX\Zapal\core\services\translate;

class TranslateInstaller
{
    static function run(){
            global $wpdb;
            $table = self::$db->prefix . 'zapal_translations';
            $charset_collate = self::$db->get_charset_collate();
            $sql = "
            CREATE TABLE IF NOT EXISTS `{$table}` (
                `translation_key` CHAR(36) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
                `lang` VARCHAR(16) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
                `post_id` BIGINT UNSIGNED NOT NULL,
    
                PRIMARY KEY (`translation_key`, `lang`),
                UNIQUE KEY `uq_post_id` (`post_id`)
            ) ENGINE=InnoDB {$charset_collate};
        ";
            $wpdb->query($sql);
            self::$inited = true;

    }
}