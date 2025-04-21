<?php
function generate_repository_class($table_name, $output_dir = __DIR__) {
    global $wpdb;

    // Validate table name
    if (empty($table_name) || !is_string($table_name)) {
        return "Error: Invalid table name provided.";
    }

    // Check if table exists
    $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
    if (!$table_exists) {
        return "Error: Table '{$table_name}' does not exist.";
    }

    // Fetch table columns and their types
    $columns = $wpdb->get_results("DESCRIBE {$table_name}");
    if (empty($columns)) {
        return "Error: Table '{$table_name}' has no columns.";
    }

    // Generate class name
    $class_name =$table_name.'_Repository';// ucfirst(str_replace('_', '', $table_name)) . 'Repository';

    // Prepare field types
    $field_types = [];
    foreach ($columns as $column) {
        $type = strpos($column->Type, 'int') !== false ? '%d' : '%s';
        $field_types[$column->Field] = $type;
    }

    // Generate required fields (excluding auto-increment primary key)
    $primary_key = '';
    $required_fields = [];
    foreach ($columns as $column) {
        if ($column->Key === 'PRI') {
            $primary_key = $column->Field;
        } elseif ($column->Null === 'NO') {
            $required_fields[] = $column->Field;
        }
    }

    // Validate primary key
    if (empty($primary_key)) {
        return "Error: Table '{$table_name}' does not have a primary key.";
    }

    // Generate the class code
    $field_types_code = '';
    foreach ($field_types as $field => $type) {
        $field_types_code .= "                '{$field}' => '{$type}',\n";
    }

    $required_fields_code = "'" . implode("', '", $required_fields) . "'";

    $class_code = <<<PHP
<?php
require_once 'generic-repository.php';

class {$class_name} extends WP_Repository {
    public function __construct() {
        parent::__construct('{$table_name}', [
            'primary_key' => '{$primary_key}',
            'required_fields' => [{$required_fields_code}],
            'field_types' => [
{$field_types_code}            ]
        ]);
    }

    // CREATE operation
    public function create(\$data) {
        if (empty(\$data) || !is_array(\$data)) {
            return new WP_Error('invalid_data', 'Invalid data provided for creation.');
        }
        return \$this->insert(\$data);
    }

    // READ operation
    public function findById(\$id) {
        if (empty(\$id)) {
            return new WP_Error('invalid_id', 'Invalid ID provided.');
        }
        return \$this->findBy(['{$primary_key}' => \$id]);
    }

    public function findAll(\$conditions = []) {
        if (!is_array(\$conditions)) {
            return new WP_Error('invalid_conditions', 'Conditions must be an array.');
        }
        return \$this->findBy(\$conditions);
    }

    // UPDATE operation
    public function update(\$data, \$conditions) {
        if (empty(\$data) || !is_array(\$data)) {
            return new WP_Error('invalid_data', 'Invalid data provided for update.');
        }
        if (empty(\$conditions) || !is_array(\$conditions)) {
            return new WP_Error('invalid_conditions', 'Invalid conditions provided for update.');
        }
        return \$this->updateBy(\$data, \$conditions);
    }

    // DELETE operation
    public function deleteById(\$id) {
        if (empty(\$id)) {
            return new WP_Error('invalid_id', 'Invalid ID provided for deletion.');
        }
        return \$this->delete(['{$primary_key}' => \$id]);
    }
}
PHP;

    // Validate output directory
    if (!is_dir($output_dir) && !mkdir($output_dir, 0755, true)) {
        return "Error: Failed to create output directory '{$output_dir}'.";
    }

    // Save the generated class to a file
    $file_path = rtrim($output_dir, '/') . '/' . $class_name . '.php';
    if (file_put_contents($file_path, $class_code) === false) {
        return "Error: Failed to save the class file to '{$file_path}'.";
    }

    return "Class '{$class_name}' has been generated and saved to '{$file_path}'.";
}

// // Example Usage
// $table_name = 'account_entities'; // Replace with your table name
// $output_dir = __DIR__ . '/repositories'; // Directory to save the generated class
// echo generate_repository_class($table_name, $output_dir);