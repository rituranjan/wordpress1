<?php
require_once 'generic-repository.php';

class tbl_account_expenses_Repository extends WP_Repository {
    public function __construct() {
        parent::__construct('account_expenses', [
            'primary_key' => 'id',
            'required_fields' => ['vendor'],
            'field_types' => [
                'id' => '%d',
                'vendor' => '%s',
                'category' => '%d',
                'amount' => '%s',
                'paid' => '%d',
                'payment_date' => '%s',
                'bill_date' => '%s',
                'due_date' => '%s',
                'payment_source' => '%d',
                'type' => '%s',
                'created_at' => '%s',
                'created_by' => '%d',
            ]
        ]);
    }

    // CREATE operation
    public function create1($data) {
        if (empty($data) || !is_array($data)) {
            return new WP_Error('invalid_data', 'Invalid data provided for creation.');
        }
        return $this->create($data);
    }

    // READ operation
    public function findById($id) {
        if (empty($id)) {
            return new WP_Error('invalid_id', 'Invalid ID provided.');
        }
        return $this->findBy(['id' => $id]);
    }

    public function findAll($conditions = []) {
        if (!is_array($conditions)) {
            return new WP_Error('invalid_conditions', 'Conditions must be an array.');
        }
        return $this->findBy($conditions);
    }

    // UPDATE operation
    public function update($data, $conditions) {
        if (empty($data) || !is_array($data)) {
            return new WP_Error('invalid_data', 'Invalid data provided for update.');
        }
        if (empty($conditions) || !is_array($conditions)) {
            return new WP_Error('invalid_conditions', 'Invalid conditions provided for update.');
        }
        return $this->updateBy($data, $conditions);
    }

    // DELETE operation
    public function deleteById($id) {
        if (empty($id)) {
            return new WP_Error('invalid_id', 'Invalid ID provided for deletion.');
        }
        return $this->delete(['id' => $id]);
    }
}