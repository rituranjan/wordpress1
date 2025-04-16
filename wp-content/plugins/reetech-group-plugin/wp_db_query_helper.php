<?php
/**
 * WordPress Database Query Helper Class
 */
class WP_DB_Query_Helper {
    
    /**
     * The global $wpdb object
     * @var wpdb
     */
    private $wpdb;
    
    /**
     * Constructor
     */
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }
    
    /**
     * Execute a custom SQL query and return results
     * 
     * @param string $sql The SQL query to execute
     * @param string $output_type The output format (OBJECT, ARRAY_A, ARRAY_N)
     * @return mixed The query results
     */
    public function execute_query($sql, $output_type = OBJECT) {
        // Prepare the query (helps prevent SQL injection)
        $prepared_sql = $this->wpdb->prepare($sql);
        
        // Execute the query
        $results = $this->wpdb->get_results($prepared_sql, $output_type);
        
        // Check for errors
        if ($this->wpdb->last_error) {
            error_log('Database error: ' . $this->wpdb->last_error);
            return false;
        }
        
        return $results;
    }
    
    /**
     * Get entities from tbl_account_entities where EntityID > 4
     * 
     * @param string $output_type The output format (OBJECT, ARRAY_A, ARRAY_N)
     * @return mixed The query results
     */
    public function get_account_entities($output_type = OBJECT) {
        $table_name = $this->wpdb->prefix . 'account_entities'; // Adds WordPress prefix
        $table_name2 = $this->wpdb->prefix . 'account_item_master'; // Adds WordPress prefix
      
        
        $sql = "SELECT `EntityID` as id, `Type` as type, `Name` 
                FROM `{$table_name}` 
                WHERE EntityID > %d";

        $sql="SELECT CAST(group_id AS UNSIGNED) as id, CAST(item_id AS UNSIGNED) as item_id, '' as type,
         item_value as Name FROM `{$table_name2}` tbl_account_item_master UNION SELECT CAST(EntityID AS UNSIGNED) as id
         , 1 as item_id,`Type` ,`Name` FROM `{$table_name}`";

         
                
        return $this->execute_query(
            $this->wpdb->prepare($sql, 4),
            $output_type
        );
    }
}