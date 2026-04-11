<?php
header('Content-Type: application/json; charset=utf-8');

// 1. Cấu hình thông tin
$valid_user = "truyenhinhiptv";
$valid_pass = "67320";
$m3u_url = "https://raw.githubusercontent.com/thung65/Iptv-vietnam/refs/heads/main/ducnt123";

$user = $_GET['username'] ?? '';
$pass = $_GET['password'] ?? '';
$action = $_GET['action'] ?? '';

// 2. Xác thực
if ($user !== $valid_user || $pass !== $valid_pass) {
    die(json_encode(["message" => "Sai tai khoan"]));
}

// 3. Hàm đọc và xử lý file M3U từ GitHub
function get_m3u_data($url) {
    $content = file_get_contents($url);
    $lines = explode("\n", $content);
    $channels = [];
    $current_group = "Khác";

    foreach ($lines as $i => $line) {
        if (strpos($line, '#EXTINF') !== false) {
            // Lấy Group-title
            if (preg_match('/group-title="(.*?)"/', $line, $match)) {
                $current_group = $match[1];
            }
            // Lấy tên kênh
            $name = trim(substr($line, strrpos($line, ",") + 1));
            // Lấy link (dòng tiếp theo)
            $link = trim($lines[$i + 1]);
            
            if ($link) {
                $channels[] = [
                    "name" => $name,
                    "group" => $current_group,
                    "url" => $link,
                    "stream_id" => md5($name) // Tạo ID duy nhất cho kênh
                ];
            }
        }
    }
    return $channels;
}

// 4. Xử lý các yêu cầu từ App IPTV
$data = get_m3u_data($m3u_url);

if (!$action) {
    echo json_encode([
        "user_info" => ["status" => "Active", "exp_date" => "1900000000", "max_connections" => "10"],
        "server_info" => ["url" => "tatkhoi.id.vn", "port" => "80"]
    ]);
} 
elseif ($action == "get_live_categories") {
    $groups = array_unique(array_column($data, 'group'));
    $categories = [];
    
    // Luôn đưa ALL lên đầu
    $categories[] = ["category_id" => "all", "category_name" => "All"];
    
    $intl_item = null;
    foreach ($groups as $g) {
        if (strtolower($g) == 'international') {
            $intl_item = ["category_id" => base64_encode($g), "category_name" => $g];
        } elseif (strtolower($g) != 'all') {
            $categories[] = ["category_id" => base64_encode($g), "category_name" => $g];
        }
    }
    // Luôn đưa International xuống cuối
    if ($intl_item) $categories[] = $intl_item;
    
    echo json_encode($categories);
} 
elseif ($action == "get_live_streams") {
    $cat_id = $_GET['category_id'] ?? '';
    $streams = [];
    
    foreach ($data as $ch) {
        if ($cat_id == "all" || base64_encode($ch['group']) == $cat_id) {
            $streams[] = [
                "name" => $ch['name'],
                "stream_id" => $ch['stream_id'],
                "stream_icon" => "",
                "direct_source" => $ch['url'],
                "category_id" => base64_encode($ch['group'])
            ];
        }
    }
    echo json_encode($streams);
}

