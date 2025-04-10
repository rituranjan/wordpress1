<?php
namespace Reetech;

class DatabaseManager {
    public static function fetch_items() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'account_item_master';
        $query = $wpdb->prepare("
            SELECT 
                CAST(group_id AS UNSIGNED) as group_id,
                CAST(item_id AS UNSIGNED) as item_id,
                item_value, 
                item_text,
                isActive,
                created_date,
                updated_date
            FROM $table_name 
            WHERE isActive = %d
            ORDER BY item_id ASC
        ", 1);

        $results = $wpdb->get_results($query, ARRAY_A);

        return array_map([__CLASS__, 'format_item'], $results ?: []);
    }

    private static function format_item($item) {
        return [
            'group_id' => (int)$item['group_id'],
            'item_id' => (int)$item['item_id'],
            'item_value' => (string)$item['item_value'],
            'item_text' => (string)$item['item_text'],
            'isActive' => (bool)$item['isActive'],
            'created_date' => self::format_date($item['created_date']),
            'updated_date' => self::format_date($item['updated_date'])
        ];
    }

    private static function format_date($date_string) {
        return $date_string ? \DateTime::createFromFormat('Y-m-d H:i:s', $date_string) : null;
    }
} 
