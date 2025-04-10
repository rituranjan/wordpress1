<?php
namespace Reetech;

use WP_REST_Response;
use WP_REST_Request;
use WP_Error;

class RestApi {
    private static $namespace = 'reetech-group/v1';

    public static function init() {
        add_action('rest_api_init', [__CLASS__, 'register_routes']);
    }

    public static function register_routes() {
        register_rest_route(self::$namespace, '/invoices', [
            'methods' => 'POST',
            'callback' => [__CLASS__, 'handle_invoice_submission'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route(self::$namespace, '/invoices-summary', [
            'methods' => 'GET',
            'callback' => [__CLASS__, 'get_invoices_summary'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('items/v1', '/all', [
            'methods' => 'GET',
            'callback' => [__CLASS__, 'get_cached_items'],
            'permission_callback' => '__return_true'
        ]);
        register_rest_route(self::$namespace, '/invoice', [
            'methods' => 'GET',
            'callback' => [__CLASS__, 'get_invoice_data'],
            'permission_callback' => '__return_true'
        ]);
    }

    public static function handle_invoice_submission1(WP_REST_Request $request) {

    
        // Implementation from original code
    }

    public static function get_invoices_summary1(WP_REST_Request $request) {
        // Implementation from original code
    }

    public static function get_cached_items() {
        return CacheManager::get_cached_data('account_item_master_all', [DatabaseManager::class, 'fetch_items'], 12 * HOUR_IN_SECONDS);
    }


   // function handle_invoice_submission(WP_REST_Request $request) {
    public static function handle_invoice_submission(WP_REST_Request $request) {
        global $wpdb;
        
         $parameters = $request->get_json_params();
       
    
    
        require_once 'entities-repository.php';
    
        $examples = new EntityExamples();
    
        if (empty($parameters)) {
            return new WP_Error('invalid_data', 'Invalid invoice data', array('status' => 400));
        }
        
        $created_id= intval($parameters['to']['id']??0);
        if($created_id==0){
            $created_id = $examples->createEntity([
                'Type' => 'Customer',
                'Name' => sanitize_text_field($parameters['to']['name'] ?? ''),
                'Address' => sanitize_textarea_field($parameters['to']['address'] ?? ''),
                'ContactInfo' => 'john@example.com',
                'LoyaltyPoints' => 500
            ]);
       // $created_id = $entities_repo->create($new_entity);
        if (is_wp_error($created_id)) {
            error_log('Creation error: ' . $created_id->get_error_message());
        } else {
            echo "Created entity ID: $created_id\n";
        }}
        // else {
        //     $created_id
        // }
    
        
        
        // Process the invoice data
        $invoice_data = array(
            'from_name' => sanitize_text_field($parameters['from']['name'] ?? ''),
            'from_address' => sanitize_textarea_field($parameters['from']['address'] ?? ''),
            'to_id' => $created_id,// intval($parameters['to']['id'] ?? 0),
           // 'to_name' => sanitize_text_field($parameters['to']['name'] ?? ''),
            //'to_address' => sanitize_textarea_field($parameters['to']['address'] ?? ''),
            'invoice_number' => sanitize_text_field($parameters['invoice_number'] ?? ''),
            'invoice_date' => sanitize_text_field($parameters['invoice_date'] ?? ''),
            'due_date' => sanitize_text_field($parameters['due_date'] ?? ''),
            'subtotal' => floatval($parameters['subtotal'] ?? 0),
            'total' => floatval($parameters['total'] ?? 0),
            'status' => 'pending',
            'created_at' => current_time('mysql')
        );
        
        $table_name = $wpdb->prefix . 'invoices';
        
        // Check for existing invoice by invoice_number
        $existing_invoice = null;
        if (!empty($invoice_data['invoice_number'])) {
            $existing_invoice = $wpdb->get_row($wpdb->prepare(
                "SELECT id FROM $table_name WHERE invoice_number = %s",
                $invoice_data['invoice_number']
            ));
        }
        
        if ($existing_invoice) {
            // Update existing invoice
            $invoice_id = $existing_invoice->id;
            
            // Remove created_at from data to prevent overwrite
            unset($invoice_data['created_at']);
            
            $result = $wpdb->update(
                $table_name,
                $invoice_data,
                array('id' => $invoice_id)
            );
            
            if ($result === false) {
                return new WP_REST_Response([
                    'success' => false,
                    'message' => 'Database error updating invoice: ' . $wpdb->last_error,
                    'query' => $wpdb->last_query
                ], 500);
            }
            
            // Delete existing items
            $items_table = $wpdb->prefix . 'invoice_items';
            $delete_result = $wpdb->delete($items_table, array('invoice_id' => $invoice_id));
            
            if ($delete_result === false) {
                error_log('Failed to delete existing invoice items: ' . $wpdb->last_error);
            }
            
        } else {
            // Insert new invoice
            $result = $wpdb->insert($table_name, $invoice_data);
            
            if ($result === false) {
                return new WP_REST_Response([
                    'success' => false,
                    'message' => 'Database error inserting invoice: ' . $wpdb->last_error,
                    'query' => $wpdb->last_query
                ], 500);
            }
            
            $invoice_id = $wpdb->insert_id;
        }
        
        // Save line items if invoice was created/updated successfully
        if ($invoice_id && !empty($parameters['items'])) {
            $items_table = $wpdb->prefix . 'invoice_items';
            
            foreach ($parameters['items'] as $item) {
                $item_result = $wpdb->insert($items_table, array(
                    'invoice_id' => $invoice_id,
                    'description' => sanitize_textarea_field($item['description'] ?? ''),
                    'unit_price' => floatval($item['unit_price'] ?? 0),
                    'quantity' => floatval($item['quantity'] ?? 0),
                    'tax_rate' => sanitize_text_field($item['tax_rate'] ?? ''),
                    'amount' => floatval($item['amount'] ?? 0),
                    'item_type' =>  sanitize_textarea_field($item['type'] ?? '')
                ));
                
                if ($item_result === false) {
                    error_log('Failed to insert invoice item: ' . $wpdb->last_error);
                }
            }
        }
        
        return new WP_REST_Response(array(
            'success' => true,
            'invoice_id' => $invoice_id,
            'message' => $existing_invoice ? 'Invoice updated successfully' : 'Invoice created successfully',
            'data' => $invoice_data
        ), 200);
    }

    
//function get_invoices_summary(WP_REST_Request $request) {
public static function get_invoices_summary(WP_REST_Request $request) {
    global $wpdb;
    
    // Get DataTables parameters
    $per_page = $request->get_param('per_page') ?: 10;
    $page = $request->get_param('page') ?: 1;
    $search = $request->get_param('search');
    $orderby = $request->get_param('orderby') ?: 'invoice_date';
    $order = $request->get_param('order') ?: 'DESC';
    
    // Validate order direction
    $order = in_array(strtoupper($order), ['ASC', 'DESC']) ? strtoupper($order) : 'DESC';
    
    // Validate orderby field
    $allowed_columns = ['invoice_number', 'customer', 'date', 'total', 'balance', 'status','id'];
    $orderby = in_array($orderby, $allowed_columns) ? $orderby : 'invoice_date';
    
    // Map orderby to actual database columns
    $column_mapping = [
        'customer' => 'to_name',
        'date' => 'invoice_date',
        'balance' => 'balance_due'
    ];
    $orderby = $column_mapping[$orderby] ?? $orderby;
    
    // Base query
    $table_name = $wpdb->prefix . 'invoices';
    $query = "SELECT 
                id,
                invoice_number,
                to_name AS customer,
                invoice_date AS date,
                total,
                balance_due AS balance,
                status
              FROM $table_name";
    
    // Where clauses
    $where_clauses = [];
    $query_params = [];
    
    // Search filter
    if ($search) {
        $where_clauses[] = "(invoice_number LIKE %s OR to_name LIKE %s)";
        $query_params[] = '%' . $wpdb->esc_like($search) . '%';
        $query_params[] = '%' . $wpdb->esc_like($search) . '%';
    }
    
    // Additional filters
    if ($request->get_param('status')) {
        $where_clauses[] = "status = %s";
        $query_params[] = sanitize_text_field($request->get_param('status'));
    }
    
    if ($request->get_param('date_from')) {
        $where_clauses[] = "invoice_date >= %s";
        $query_params[] = sanitize_text_field($request->get_param('date_from'));
    }
    
    if ($request->get_param('date_to')) {
        $where_clauses[] = "invoice_date <= %s";
        $query_params[] = sanitize_text_field($request->get_param('date_to'));
    }
    
    // Combine where clauses
    if (!empty($where_clauses)) {
        $query .= " WHERE " . implode(" AND ", $where_clauses);
    }
    
    // Add sorting
    $query .= " ORDER BY $orderby $order";
    
    // Add pagination
    $offset = ($page - 1) * $per_page;
    $query .= " LIMIT %d OFFSET %d";
    $query_params[] = $per_page;
    $query_params[] = $offset;
    
    // Prepare and execute
    if (!empty($query_params)) {
        $query = $wpdb->prepare($query, $query_params);
    }
    
    $invoices = $wpdb->get_results($query);
    
    // Get total count
    $count_query = "SELECT COUNT(*) FROM $table_name";
    if (!empty($where_clauses)) {
        $count_query .= " WHERE " . implode(" AND ", $where_clauses);
        $count_query = $wpdb->prepare($count_query, array_slice($query_params, 0, count($query_params) - 2));
    }
    $total_items = $wpdb->get_var($count_query);
    
    // Format response
    return new WP_REST_Response([
        'success' => true,
        'data' => $invoices,
        'pagination' => [
            'total_items' => (int)$total_items,
            'per_page' => (int)$per_page,
            'current_page' => (int)$page,
            'total_pages' => ceil($total_items / $per_page)
        ]
    ], 200);
}

public static function get_invoices_summary(WP_REST_Request $request) {
//function get_invoice_data(WP_REST_Request $request) {
    global $wpdb;
    $invoice_id = $request->get_param('id');

    // Get main invoice data
    $table_name = $wpdb->prefix . 'invoices';
    $invoice = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE id = %d", 
        $invoice_id
    ));

    if (!$invoice) {
        return new WP_Error('no_invoice', 'Invoice not found', array('status' => 404));
    }

    // Get line items
    $items_table = $wpdb->prefix . 'invoice_items';
    $items = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $items_table WHERE invoice_id = %d",
        $invoice_id
    ));

    // $examples = new EntityExamples();
    // $customer = $examples->findEntityByID(9)
    

    // Format response
    $response = array(
        'from' => array(
            'name' => $invoice->from_name,
            'address' => $invoice->from_address
        ),
        'to' => array(
            'id' => $invoice->to_id,
            'name' =>"AAA",// $customer->name,
            'address' =>"bbb",// $customer->address
        ),
        'invoice_number' => $invoice->invoice_number,
        'invoice_date' => $invoice->invoice_date,
        'due_date' => $invoice->due_date,
        'subtotal' => floatval($invoice->subtotal),
        'total' => floatval($invoice->total),
        'items' => array(),
        'notes' => $invoice->notes
    );

    foreach ($items as $item) {
        $response['items'][] = array(
            'description' => $item->description,
            'unit_price' => floatval($item->unit_price),
            'quantity' => floatval($item->quantity),
            'tax_rate' => $item->tax_rate,
            'amount' => floatval($item->amount),
            'item'=> $item->item_type,
        );
    }

    return new WP_REST_Response($response, 200);
}

// Callback function to get invoice data
public static function get_invoice_data(WP_REST_Request $request) {

//function get_invoice_data(WP_REST_Request $request) {
    global $wpdb;
    $invoice_id = $request->get_param('id');

    // Get main invoice data
    $table_name = $wpdb->prefix . 'invoices';
    $invoice = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE id = %d", 
        $invoice_id
    ));

    if (!$invoice) {
        return new WP_Error('no_invoice', 'Invoice not found', array('status' => 404));
    }

    // Get line items
    $items_table = $wpdb->prefix . 'invoice_items';
    $items = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $items_table WHERE invoice_id = %d",
        $invoice_id
    ));

    // $examples = new EntityExamples();
    // $customer = $examples->findEntityByID(9)
    

    // Format response
    $response = array(
        'from' => array(
            'name' => $invoice->from_name,
            'address' => $invoice->from_address
        ),
        'to' => array(
            'id' => $invoice->to_id,
            'name' =>"AAA",// $customer->name,
            'address' =>"bbb",// $customer->address
        ),
        'invoice_number' => $invoice->invoice_number,
        'invoice_date' => $invoice->invoice_date,
        'due_date' => $invoice->due_date,
        'subtotal' => floatval($invoice->subtotal),
        'total' => floatval($invoice->total),
        'items' => array(),
        'notes' => $invoice->notes
    );

    foreach ($items as $item) {
        $response['items'][] = array(
            'description' => $item->description,
            'unit_price' => floatval($item->unit_price),
            'quantity' => floatval($item->quantity),
            'tax_rate' => $item->tax_rate,
            'amount' => floatval($item->amount),
            'item'=> $item->item_type,
        );
    }

    return new WP_REST_Response($response, 200);
}

} 
