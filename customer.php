<?php
require_once("database.php");

class Customer extends DataMapper
{
    public const table = "customers";
    public const pk = "id";
    public const fields = ['id', 'name', 'city'];

    public function __construct($data = null, $is_new = false)
    {
        parent::__construct(self::table, self::pk, self::fields);
        $this->data = $data;
        $this->is_new = $is_new;
    }
}

$customer = new Customer();
$method = $_SERVER['REQUEST_METHOD'];

if (isset($_GET['list'])) {
    echo json_encode($customer->list());
} else if ($method == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['name']) || empty($data['city'])) {
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }

    try {
      
        $latestCustomer = $customer->list();
        $lastId = !empty($latestCustomer) ? max(array_column($latestCustomer, 'id')) : 0;
        $data['id'] = $lastId + 1;
        
       
        $customer->add($data);
        echo json_encode(["success" => "Customer added successfully", "data" => $data]);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}
