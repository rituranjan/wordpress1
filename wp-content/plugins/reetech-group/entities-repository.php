<?php
require_once 'generic-repository.php';

class EntitiesRepository extends WP_Repository {
    public function __construct() {
        parent::__construct('account_entities', [
            'primary_key' => 'EntityID',
            'required_fields' => ['Type', 'Name'],
            'field_types' => [
                'EntityID' => '%d',
                'Type' => '%s',
                'Name' => '%s',
                'Address' => '%s',
                'ContactInfo' => '%s',
                'CompanyName' => '%s',
                'ProductCategory' => '%s',
                'LoyaltyPoints' => '%d',
                'PropertyOwned' => '%s'
            ]
        ]);
    }

  
}


// <?php
// require_once 'entities-repository.php';

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

    public function findEntitiesByType($type) {
        return $this->repo->findBy(['Type' => $type]);
    }

    // DELETE example
    public function deleteEntity($entityId) {
        return $this->repo->delete(['EntityID' => $entityId]);
    }

    
}