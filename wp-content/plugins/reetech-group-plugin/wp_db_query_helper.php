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
        $table_group_master = $this->wpdb->prefix . 'account_group_master'; // Adds WordPress prefix      

        $table_account_entities = $this->wpdb->prefix . 'account_entities'; // Adds WordPress prefix
        $table_account_item_master = $this->wpdb->prefix . 'account_item_master'; // Adds WordPress prefix
        $table_group_master = $this->wpdb->prefix . 'account_group_master'; // Adds WordPress prefix      
      
      
        // $sql="SELECT CAST(g_master.group_id AS UNSIGNED) AS id, CAST(i_master.item_id AS UNSIGNED) AS item_id, g_master.group_name AS type, 
        // i_master.item_value AS Name, 0 AS `value` FROM `tbl_account_item_master` i_master INNER JOIN `tbl_account_group_master` g_master 
        // ON i_master.group_id = g_master.group_id UNION ALL SELECT CAST(t.EntityID AS UNSIGNED) AS id, 1 AS item_id, t.`Type`, t.`Name`,
        //  0 AS `value` FROM `tbl_account_entities` t UNION ALL SELECT CAST(g_master.group_id AS UNSIGNED) AS id,
        //   CAST(tax.id AS UNSIGNED) AS item_id, g_master.group_name AS type, tax.Name, tax.tax AS `value` FROM tbl_account_tax tax 
        //   INNER JOIN `tbl_account_group_master` g_master ON tax.group_id = g_master.group_id;"

        // echo $sql;


        $sql="SELECT * FROM getMaster;";


    
                
        return $this->execute_query(
            $this->wpdb->prepare($sql, 4),
            $output_type
        );
    }
}