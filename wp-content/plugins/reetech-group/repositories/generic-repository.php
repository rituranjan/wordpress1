<?php
class WP_Repository {
    protected $table_name;
    protected $primary_key = 'id';
    protected $required_fields = [];
    protected $field_types = [];

    public function __construct($table_name, $config = []) {
        global $wpdb;
        $this->table_name = $wpdb->prefix . $table_name;
        
        if (isset($config['primary_key'])) {
            $this->primary_key = $config['primary_key'];
        }
        
        if (isset($config['required_fields'])) {
            $this->required_fields = $config['required_fields'];
        }
        
        if (isset($config['field_types'])) {
            $this->field_types = $config['field_types'];
        }
    }

    /**
     * Find records by multiple criteria
     * @param array $criteria Associative array of search conditions
     * @return array|object|null Query results
     */
    public function findBy(array $criteria) {
        global $wpdb;
        
        $where = $this->parse_conditions($criteria);
        $query = "SELECT * FROM {$this->table_name} {$where}";
        
        return $wpdb->get_results($query);
    }

    /**
     * Update records by multiple criteria
     * @param array $data Data to update
     * @param array $criteria Update conditions
     * @return int|WP_Error Number of affected rows or error
     */
    public function updateBy(array $data, array $criteria) {
        global $wpdb;

        list($prepared_data, $formats) = $this->prepare_data($data);
        $where_formats = $this->get_condition_formats($criteria);

        $result = $wpdb->update(
            $this->table_name,
            $prepared_data,
            $criteria,
            $formats,
            $where_formats
        );

        if ($result === false) {
            return new WP_Error(
                'db_update_error',
                __('Failed to update records', 'text-domain'),
                $wpdb->last_error
            );
        }

        return $result;
    }

    /**
     * Create new record
     */
    public function create($data, $all = 0) {
        global $wpdb;
        
        $validation = $this->validate_data($data);
        if (is_wp_error($validation)) {
            return $validation;
        }

        list($prepared_data, $formats) = $this->prepare_data($data);

        $result = $wpdb->insert(
            $this->table_name,
            $prepared_data,
            $formats
        );

        if ($result === false) {
            return new WP_Error(
                'db_insert_error',
                __('Failed to create record', 'text-domain'),
                $wpdb->last_error
            );
        }
        if($all==1) {
            return $data;
        }
        return $wpdb->insert_id;
    }

    /**
     * Read records
     */
    public function read($conditions = [], $limit = null, $offset = null) {
        global $wpdb;

        $where = $this->parse_conditions($conditions);
        $query = "SELECT * FROM {$this->table_name} {$where}";

        if ($limit) {
            $query .= $wpdb->prepare(" LIMIT %d", $limit);
            if ($offset) {
                $query .= $wpdb->prepare(" OFFSET %d", $offset);
            }
        }

        return $wpdb->get_results($query);
    }

    /**
     * Update records
     */
    public function update($data, $conditions) {
        global $wpdb;

        list($prepared_data, $formats) = $this->prepare_data($data);
        $where = $this->parse_conditions($conditions);

        $result = $wpdb->update(
            $this->table_name,
            $prepared_data,
            $conditions,
            $formats,
            $this->get_condition_formats($conditions)
        );

        if ($result === false) {
            return new WP_Error(
                'db_update_error',
                __('Failed to update record', 'text-domain'),
                $wpdb->last_error
            );
        }

        return $result;
    }

    /**
     * Delete records
     */
    public function delete($conditions) {
        global $wpdb;

        $where = $this->parse_conditions($conditions);

        $result = $wpdb->delete(
            $this->table_name,
            $conditions,
            $this->get_condition_formats($conditions)
        );

        if ($result === false) {
            return new WP_Error(
                'db_delete_error',
                __('Failed to delete record', 'text-domain'),
                $wpdb->last_error
            );
        }

        return $result;
    }

    /**
     * Data preparation and sanitization
     */
    protected function prepare_data($data) {
        $prepared = [];
        $formats = [];

        foreach ($data as $key => $value) {
            $field_type = $this->field_types[$key] ?? '%s';
            
            // Sanitize based on field type
            switch ($field_type) {
                case '%d':
                    $prepared[$key] = intval($value);
                    break;
                case '%f':
                    $prepared[$key] = floatval($value);
                    break;
                default:
                    $prepared[$key] = sanitize_text_field($value);
            }
            
            $formats[] = $field_type;
        }

        return [$prepared, $formats];
    }

    /**
     * Validate data against required fields
     */
    protected function validate_data($data) {
        foreach ($this->required_fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return new WP_Error(
                    'validation_error',
                    sprintf(__('Field %s is required', 'text-domain'), $field)
                );
            }
        }
        return true;
    }

    /**
     * Condition parsing for safe queries
     */
    protected function parse_conditions($conditions) {
        global $wpdb;
        
        if (empty($conditions)) {
            return '';
        }

        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = $wpdb->prepare("{$key} = %s", $value);
        }

        return 'WHERE ' . implode(' AND ', $where);
    }

    protected function get_condition_formats($conditions) {
        $formats = [];
        foreach ($conditions as $key => $value) {
            $formats[] = $this->field_types[$key] ?? '%s';
        }
        return $formats;
    }
}