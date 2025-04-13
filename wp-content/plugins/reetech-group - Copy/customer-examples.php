<?php
require_once 'customer.php';

// Initialize examples
$examples = new EntityExamples();

// Scenario 1: Create new entity
$newEntityId = $examples->createEntity([
    'Type' => 'Customer',
    'Name' => 'John Doe',
    'Address' => '123 Main St',
    'ContactInfo' => 'john@example.com',
    'LoyaltyPoints' => 500
]);

if($newEntityId) {
    echo "Created new entity ID: $newEntityId\n";
}

// Scenario 2: Find by ID
$entity = $examples->findEntityByID($newEntityId);
print_r($entity);

// Scenario 3: Update using multiple criteria
$updateResult = $examples->updateEntityAddress(
    'John Doe',
    '123 Main St',
    '456 Oak Ave'
);

if($updateResult) {
    echo "Updated address successfully\n";
}

// Scenario 4: Batch update
$examples->updateEntityPoints($newEntityId, 750);

// Scenario 5: Complex workflow
$customerData = [
    'name' => 'Alice Smith',
    'email' => 'alice@example.com',
    'address' => '789 Pine Rd',
    'points' => 1000
];

$result = $examples->processCustomerUpdate($customerData);
if(is_wp_error($result)) {
    echo "Error: " . $result->get_error_message();
} else {
    echo "Customer processed successfully\n";
}

// Scenario 6: Delete entity
if($examples->deleteEntity($newEntityId)) {
    echo "Entity deleted successfully\n";
}