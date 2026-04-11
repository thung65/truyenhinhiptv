<?php
/**
 * CLEARKEY DRM API - BY TRẦN TẤT KHÔI
 * Cập nhật: 11/04/2026 - Đồng bộ 100% theo danh sách M3U mới
 */

header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

$key_storage = [
    // --- Nhóm Sự kiện trực tiếp 360 ---
    "360_bong_da"      => "c17f1b571e985f15817351163763bf64:61951ba946496cbfeede334b54a182f8",
    "360_the_thao"     => "c17f1b571e985f15817351163763bf64:61951ba946496cbfeede334b54a182f8",
    "360_c1_chau_au"   => "c17f1b571e985f15817351163763bf64:61951ba946496cbfeede334b54a182f8",
    "360_bundesliga"   => "70d033252d025d719ba70a37acc0f5c8:6ca465d8b3018f1604da6d43d3395a6d",
    "360_cuong_nhiet"  => "ad06f4610c564dc78f5955b71fa85fe8:0ebfe0ecc37ddb67b2bc9580e1b8fbed",
    "360_san_co"       => "3b0f9e1892864641aa17f02b2e7b0b2d:6c06f96a6296d05442466d5b09e58192",
    "360_phut_90"      => "072f0a0d4eda40cbb04dfadf521a2b9f:2fb11373b36e018573d3b07132df050e",
    "360_golf"         => "72a793a108f04d68858f2075616b6b98:76650cf89c991b0d26e9c50de2087891",
    "360_cong_thuc_1"  => "70d033252d025d719ba70a37acc0f5c8:6ca465d8b3018f1604da6d43d3395a6d",

    // --- NHÓM QUỐC TẾ (Dựa theo danh sách M3U bạn gửi) ---
    "hbo"              => "09ddfe3d63863cafaeb79d0546b098ab:3de0f38dcf014827dfd5bec38743c6a2",
    "axn"              => "9d29f87efdec3c9fab368f724a62ad0e:6f1c09c035eab36323d60d1454db3d20",
    "cinemax"          => "acb4c23471063327adc732e283c0847f:e9868f5f473d0fd8699ede48d531c2b0",
    "cinemaworld"      => "ee7915564d7439d09bd3556ffccc87a4:b35e12a75a42a6f9184723a90ff42d9c",
    "abc_australia"    => "9a09a73ec1dc348584e2f130e27da667:06f8d1a6feaffbdab92adf347e7b469c",
    "animal_planet"    => "ec6f072c7125377a9bc0ae61598095f4:1d5388e0781415ebcec9914f5ad75875",
    "tlc"              => "b6908629732639ada4814a6208296d9c:7ca9bf03623f77b5e2f16df0b53f274d",
    "cnn"              => "714cc8ed05a03abb9ac61bd4bbd1d8a0:1acf58ff8d4cd87c2d3c12d22248efb1",
    "dmax"             => "53b26f904ae03a20b56477cfb9c5dca2:0c64ccfb978e7390bd33344075492aec",
    "cartoonito"       => "b1f0d759e914369db388b3b0dc815971:5678d317e17007a88a9b9539e4526512",
    "discovery"        => "eb4160ea553a321d899553e4e796fec2:bea5a07157e0c4d17b11ab399517f952",
    "discovery_asia"   => "934907b134be3963a6263a453846924c:788e2835fb98568aed2f47bbdc091515",
    "cartoon_network"  => "3c20166660a93a75ac77db81567389f7:3cc1add43aecce3fe31c9c6a2a5b8c21",
    "dreamworks"       => "67dae20527c63dadaaae609aa91577cb:59328f621d56767bc5ff9404a8940683",
    "bbc_cbeebies"     => "cca73a006b4b39a595207ceb5ed9ca0a:b833d1f40c261ef78896f97e06f80cdc",
    "bbc_earth"        => "58b949986ed13294bc01b0f330abc527:23e8c5f2fe202906ac2d6554d9527299",
    "bbc_lifestyle"    => "58b949986ed13294bc01b0f330abc527:23e8c5f2fe202906ac2d6554d9527299",
    "bbc_news"         => "c7ba46086dda345a929e29bf155e459c:d374b8f39904bf572689ea347ae86591",
    "wbtv"             => "086d09a40bff3a00aa6dd4dbaf9c13b2:34f1908cfe2e05ee060046d40f14aec9",
    "bloomberg"        => "1071393f8fc237f3a9a28028142110f7:7a0f8c3c72e0ffff811d534c929c57c2",
    "fashion_tv"       => "c1d9f25701023508bfa6737e3a8c7001:30c3613e9b06e0f7cc201014f31bf5d8",
    "box_sctv"         => "a7c942778e874d43be92b8d0a0cd11b4:6d54358306571658ffdb952c6560688b"
];

function b64($hex) {
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(hex2bin($hex)));
}

$id = isset($_GET['id']) ? strtolower(trim($_GET['id'])) : '';

if (array_key_exists($id, $key_storage)) {
    list($kid_hex, $key_hex) = explode(':', $key_storage[$id]);
    echo json_encode([
        "keys" => [
            [
                "kty" => "oct",
                "kid" => b64($kid_hex),
                "k"   => b64($key_hex)
            ]
        ],
        "type" => "temporary"
    ], JSON_PRETTY_PRINT);
} else {
    http_response_code(404);
    echo json_encode(["error" => "ID '$id' khong tim thay."]);
}
?>

