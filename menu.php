<?php
require_once("database.php");

class Menu extends DataMapper
{
    public const table = "manus";
    public const pk = "manu_id";
    public const fields = ['manu_id', 'manu_name', 'price']; 

    public function __construct($data = null, $is_new = false)
    {
        parent::__construct(self::table, self::pk, self::fields);
        $this->data = $data;
        $this->is_new = $is_new;
    }
}

$menu = new Menu();
$method = $_SERVER['REQUEST_METHOD'];

if (isset($_GET['list'])) {
    echo json_encode($menu->list());
} else if ($method == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['manu_name']) || empty($data['price'])) {
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }

    try {
        
        $latestMenu = $menu->list();
        $lastId = !empty($latestMenu) ? max(array_column($latestMenu, 'manu_id')) : 0;
        $data['manu_id'] = $lastId + 1; 

        
        $menu->add($data);
        echo json_encode(["success" => "Menu added successfully", "data" => $data]);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}
