

<?php
//namespace Reetech;
 require_once 'entities-repository.php';

class EntityExamples {
    private $repo;

    public function __construct() {
        $this->repo = new EntitiesRepository();
    }

    // CREATE example
    public function createEntity($data) {
        $result = $this->repo->create($data);
        
        if(is_wp_error($result)) {
            error_log('Create error: ' . $result->get_error_message());
            return false;
        }
        
        return $result;
    }

    // FIND examples
    public function findEntityByID($id) {
        return $this->repo->findBy(['EntityID' => $id]);
    }

    public function findEntitiesByAddress($address) {
        return $this->repo->findBy(['Address' => $address]);
    }

    // UPDATE examples
    public function updateEntityPoints($entityId, $points) {
        return $this->repo->updateBy(
            ['LoyaltyPoints' => $points],
            ['EntityID' => $entityId]
        );
    }

    public function updateEntityAddress($name, $oldAddress, $newAddress) {
        return $this->repo->updateBy(
            ['Address' => $newAddress],
            [
                'Name' => $name,
                'Address' => $oldAddress
            ]
        );
    }

    // DELETE example
    public function deleteEntity($entityId) {
        return $this->repo->delete(['EntityID' => $entityId]);
    }

    // COMPLEX EXAMPLE
    public function processCustomerUpdate($customerData) {
        // Find existing customer
        $existing = $this->repo->findBy([
            'Name' => $customerData['name'],
            'ContactInfo' => $customerData['email']
        ]);

        if(!empty($existing)) {
            // Update existing
            return $this->repo->updateBy(
                [
                    'LoyaltyPoints' => $customerData['points'],
                    'Address' => $customerData['address']
                ],
                [
                    'EntityID' => $existing[0]->EntityID
                ]
            );
        }

        // Create new
        return $this->repo->create([
            'Type' => 'Customer',
            'Name' => $customerData['name'],
            'ContactInfo' => $customerData['email'],
            'Address' => $customerData['address'],
            'LoyaltyPoints' => $customerData['points']
        ]);
    }
}